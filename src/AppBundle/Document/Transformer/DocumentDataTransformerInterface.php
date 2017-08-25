<?php

namespace AppBundle\Document\Transformer;

/**
 * DocumentDataTransformers are used to transform complex application data into a flat array of data.
 * The output can then be used to fill a specific pdf, each transformer handles exactly one pdf.
 */
interface DocumentDataTransformerInterface
{
    /**
     * @param array $options The complex application data to transform
     * @return array The flat array of data to fill the pdf with
     */
    public function transform(array $options = []);

    /**
     * @param string $filename The name of the pdf file to check
     * @return bool Return true when the transformer supports this specific $filename
     */
    public function supports($filename);
}
