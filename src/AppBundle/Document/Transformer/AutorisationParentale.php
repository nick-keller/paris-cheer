<?php

namespace AppBundle\Document\Transformer;

use AppBundle\Document\DocumentGenerator;
use AppBundle\Entity\Athlete;
use AppBundle\Enum\Gender;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AutorisationParentale extends AbstractDocumentDataTransformer
{

    /**
     * {@inheritdoc}
     */
    protected function build(array $option)
    {
        /** @var Athlete $athlete */
        $athlete = $option['athlete'];

        return [
            'club' => 'Paris Cheer',
            'parent_name' => $athlete->getEmergencyName(),
            'athlete_name' => (string) $athlete,
            'dob_day' => $athlete->getBirthday()->format('d'),
            'dob_month' => $athlete->getBirthday()->format('m'),
            'dob_year' => $athlete->getBirthday()->format('Y'),
            'birth_place' => $athlete->getBirthPlace(),
            'sport' => 'cheerleading',
            'city' => $athlete->getCity(),
            'day' => (new \DateTime())->format('d'),
            'month' => (new \DateTime())->format('m'),
            'year' => (new \DateTime())->format('Y'),
        ];
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(['athlete'])
            ->setAllowedTypes('athlete', Athlete::class);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($filename)
    {
        return $filename == DocumentGenerator::AUTORISATION_PARENTALE_MINEUR || $filename == DocumentGenerator::AUTORISATION_PARENTALE_SC;
    }
}
