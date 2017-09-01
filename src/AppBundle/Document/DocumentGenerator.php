<?php

namespace AppBundle\Document;

use AppBundle\Document\Transformer\DocumentDataTransformerInterface;
use AppBundle\Entity\Athlete;
use AppBundle\Program\ProgramEligibilityVoter;
use mikehaertl\pdftk\Pdf;

class DocumentGenerator
{
    const FFFA_LICENCE = 'fffa-licence.pdf';
    const AUTORISATION_PARENTALE_MINEUR = 'autorisation-parentale-mineur.pdf';
    const AUTORISATION_PARENTALE_SC = 'autorisation-parentale-sc.pdf';
    const PAGE_SIG_ADULT = 'page-signature-adult.pdf';
    const PAGE_SIG_MINEUR = 'page-signature-mineur.pdf';

    const PDF_ROOT_DIR = __DIR__ . '/../../../web/documents/';
    const SAVE_DIRECTORY = __DIR__ . '/../../../web/documents/athletes/';

    /** @var DocumentDataTransformerInterface[] */
    private $transformers = [];
    /** @var ProgramEligibilityVoter */
    private $voter;

    /**
     * DocumentGenerator constructor.
     *
     * @param ProgramEligibilityVoter $voter
     */
    public function __construct(ProgramEligibilityVoter $voter)
    {
        $this->voter = $voter;
    }

    public function addTransformer(DocumentDataTransformerInterface $transformer)
    {
        $this->transformers[] = $transformer;
    }

    /**
     * @param string $filename
     * @param array  $options
     * @param string $name
     * @return string The path of the newly created pdf file
     */
    public function generate(string $filename, array $options, string $name) : string {
        foreach ($this->transformers as $transformer) {
            if ($transformer->supports($filename)) {
                $pdf = new Pdf(self::PDF_ROOT_DIR . $filename);

                $savePath = self::SAVE_DIRECTORY . "$name-$filename";
                $pdf->fillForm($transformer->transform($options))
                    ->flatten()
                    ->saveAs($savePath);

                return $savePath;
            }
        }

        throw new \InvalidArgumentException('No DocumentDataTransformer found for ' . $filename);
    }

    /**
     * Get the list of documents the $athlete is eligible to.
     *
     * @param Athlete $athlete
     * @return string[]
     */
    public function getDocumentsList(Athlete $athlete) : array
    {
        $documents = [self::FFFA_LICENCE];

        $program = $this->voter->findBySlug($athlete->getProgram());
        $category = null !== $program ? $program->getCategory($athlete) : null;

        if ($category === 'junior_senior') {
            $documents[] = self::AUTORISATION_PARENTALE_SC;
        } else if ($athlete->isMinor()) {
            $documents[] = self::AUTORISATION_PARENTALE_MINEUR;
        }

        if ($athlete->isMinor()) {
            $documents[] = self::PAGE_SIG_MINEUR;
        } else {
            $documents[] = self::PAGE_SIG_ADULT;
        }

        return $documents;
    }

    public function getDocuments(Athlete $athlete)
    {
        $documents = array_map(function($document) use($athlete) {
            return $this->generate($document, ['athlete' => $athlete], $athlete->getId());
        }, $this->getDocumentsList($athlete));

        $pdf = new Pdf($documents);
        $pdf->saveAs(self::SAVE_DIRECTORY . $athlete->getId() . '.pdf');
    }
}
