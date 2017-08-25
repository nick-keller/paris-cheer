<?php

namespace AppBundle\Registration\Step;

use AppBundle\Form\TShirtType;

class TShirtStep extends AbstractStep
{

    public function getName()
    {
        return 't-shirt';
    }

    protected function getFormType()
    {
        return TShirtType::class;
    }


    protected function getValidationGroup()
    {
        return ['tshirt'];
    }

    public function getViewData()
    {
        $data = array_merge(parent::getViewData(), [
            'tshirts' => [
                ['model' => 'blue', 'name' => 'Royals'],
                ['model' => 'orange', 'name' => 'Take the crown'],
            ]
        ]);

        return $data;
    }
}
