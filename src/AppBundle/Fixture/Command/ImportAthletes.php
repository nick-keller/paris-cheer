<?php

namespace AppBundle\Fixture\Command;

use AppBundle\Entity\Athlete;
use AppBundle\Enum\Gender;
use Ferrandini\Urlizer;
use League\Csv\MapIterator;
use League\Csv\Reader;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportAthletes extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('athlete:import');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $athletes = Reader::createFromPath(__DIR__ . '/../Data/athletes-2016-2017.csv');
        $athletes->setHeaderOffset(0);

        $licences = Reader::createFromPath(__DIR__ . '/../Data/fffa-licences.csv');
        $licences->setHeaderOffset(0);
        $licences = $licences->getRecords();

        foreach ($athletes->getRecords() as $record) {
            $licence = $this->getLicence($record, $licences);

            if (null == $licence) {
                $output->writeln('<error>No licence found for athlete ' . $record['first_name'] . ' ' . $record['last_name'] . '</error>');
            }

            $athlete = new Athlete();
            $athlete
                ->setFirstName($record['first_name'])
                ->setLastName($record['last_name'])
                ->setEmail($record['email'])
                ->setBirthday(new \DateTime($record['birthday']))
                ->setPhone(preg_replace('/[^0-9]/', '', $record['phone']))
                ->setEmergencyName($record['emergency_name'])
                ->setEmergencyPhone(preg_replace('/[^0-9]/', '', $record['emergency_phone']))
                ->setEmergencyEmail($record['emergency_email'])
                ->setAddress($record['address'])
                ->setZipCode($record['zipcode'])
                ->setCity($record['city'])
                ->setGender($licence['gender'][0] === 'M' ? Gender::Male : Gender::Female)
                ->setLicenceId($licence['liscence'])
                ;

            $em->persist($athlete);
        }

        $em->flush();

        $output->writeln('Imported <info>' . count($athletes) . '</info> athletes');
    }

    private function getLicence(array $athlete, MapIterator $licences)
    {
        $bestMatch = null;
        $closestDistance = PHP_INT_MAX;

        foreach ($licences as $licence) {
            if ($licence['birthday'] == $athlete['birthday']) {
                $distance = levenshtein(Urlizer::urlize($licence['name']), Urlizer::urlize($athlete['last_name'] . ' ' . $athlete['first_name']));

                if ($distance < $closestDistance) {
                    $closestDistance = $distance;
                    $bestMatch = $licence;
                }
            }
        }

        return $bestMatch;
    }
}
