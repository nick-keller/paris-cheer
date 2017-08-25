<?php

namespace AppBundle\Document\Transformer;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * All DocumentDataTransformers should extend this class.
 * The app.document_data_transformer tag will automatically be added to your service and injected in DocumentGenerator.
 */
abstract class AbstractDocumentDataTransformer implements DocumentDataTransformerInterface
{
    /**
     * This implementation first validates the $option, builds the data, and then formats any boolean value.
     *
     * @param array $options
     * @return array
     */
    public final function transform(array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $options = $resolver->resolve($options);

        return array_map(function($value) {
            if (is_bool($value)) {
                return $value ? 'Yes' : 0;
            }

            return $value;
        }, $this->build($options));
    }

    /**
     * Build the data to fill the pdf with from a validated options array.
     *
     * @param array $option
     * @return array
     */
    protected abstract function build(array $option);

    /**
     * Implement this method to setup the OptionsResolver that will be used to validate the $option parameter sent to $this->build($option)
     *
     * @param OptionsResolver $resolver
     * @return null
     */
    protected abstract function configureOptions(OptionsResolver $resolver);
}
