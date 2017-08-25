<?php

namespace AppBundle\Document\Transformer;

use AppBundle\Document\DocumentGenerator;
use AppBundle\Entity\Athlete;
use AppBundle\Enum\Gender;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageSignature extends AbstractDocumentDataTransformer
{

    /**
     * {@inheritdoc}
     */
    protected function build(array $option)
    {
        /** @var Athlete $athlete */
        $athlete = $option['athlete'];

        return [
            'athlete_name' => (string) $athlete,
            'legal_responsible' => $athlete->getEmergencyName(),
            'alone' => false, //TODO
            'accompanied' => false, //TODO
            'accompanying' => '', //TODO
            'date' => (new \DateTime())->format('d/m/Y')
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
        return $filename == DocumentGenerator::PAGE_SIG_ADULT || $filename == DocumentGenerator::PAGE_SIG_MINEUR;
    }
}
