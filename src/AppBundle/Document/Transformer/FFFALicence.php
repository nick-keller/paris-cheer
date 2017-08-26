<?php

namespace AppBundle\Document\Transformer;

use AppBundle\Document\DocumentGenerator;
use AppBundle\Entity\Athlete;
use AppBundle\Enum\Gender;
use AppBundle\Program\ProgramEligibilityVoter;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FFFALicence extends AbstractDocumentDataTransformer
{
    /** @var ProgramEligibilityVoter */
    private $voter;

    /**
     * FFFALicence constructor.
     *
     * @param ProgramEligibilityVoter $voter
     */
    public function __construct(ProgramEligibilityVoter $voter)
    {
        $this->voter = $voter;
    }

    /**
     * {@inheritdoc}
     */
    protected function build(array $option)
    {
        /** @var Athlete $athlete */
        $athlete = $option['athlete'];

        $program = $this->voter->findBySlug($athlete->getProgram());
        $category = null !== $program ? $program->getCategory($athlete) : null;

        $data = [
            'club' => 'Paris Cheer',
            'first_name' => $athlete->getFirstName(),
            'last_name' => $athlete->getLastName(),
            'male' => $athlete->getGender() == Gender::Male,
            'female' => $athlete->getGender() == Gender::Female,
            'address_1' => substr($athlete->getAddress(), 0, 24),
            'address_2' => strlen($athlete->getAddress()) > 24 ? substr($athlete->getAddress(), 24) : '',
            'zipcode' => $athlete->getZipCode(),
            'city' => $athlete->getCity(),
            'phone' => $athlete->getPhone(),
            'email' => $athlete->getEmail(),
            'birthday' => $athlete->getBirthday()->format('dmY'),
            'birth_city' => $athlete->getBirthPlace(),
            'nationality' => $athlete->getNationality(),
            'jaf_false' => true,
            'licence_number' => $athlete->getLicenceId(),
            'adult_name' => !$athlete->isMinor() && !$athlete->needsMedicalCertificate() ? (string)$athlete : '',
            'minor_name' => $athlete->isMinor() && !$athlete->needsMedicalCertificate() ? $athlete->getEmergencyName() : '',
            'club_signature_date' => (new \DateTime())->format('dmY'),
            'doctor_sport_cheer' => $athlete->needsMedicalCertificate() && $category != 'junior_senior',
            'doctor_cheer_player' => $athlete->needsMedicalCertificate() && $category != 'junior_senior',
        ];

        if (null !== $category) {
            $data[$category] = true;
        }

        return $data;
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
        return $filename == DocumentGenerator::FFFA_LICENCE;
    }
}
