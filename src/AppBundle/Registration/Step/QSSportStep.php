<?php

namespace AppBundle\Registration\Step;

use AppBundle\Entity\Athlete;
use AppBundle\Form\QsSportType;
use Symfony\Component\HttpFoundation\Request;

class QSSportStep extends AbstractStep
{
    public function getName()
    {
        return 'qs-sport';
    }

    public function setAthlete(Athlete $athlete)
    {
        $this->athlete = $athlete;
        $this->form = $this->createForm($this->getFormType());
    }

    public function handle(Request $request)
    {
        parent::handle($request);

        if ($this->form->isValid()) {
            $data = $this->form->getData();
            $numberOfYes = 0;

            foreach ($data as $questions) {
                foreach ($questions as $question) {
                    if ($question === true) {
                        $numberOfYes++;
                    }
                }
            }

            $this->athlete->setQsSportOnlyNos($numberOfYes === 0);
        }
    }

    public function isValid(Athlete $athlete = null)
    {
        if (null === $athlete) {
            $athlete = $this->athlete;
        }

        return is_bool($athlete->isQsSportOnlyNos()) || $athlete->getLicenceId() === null;
    }

    protected function getFormType()
    {
        return QsSportType::class;
    }

    protected function getValidationGroup() {}

    public function isSkippable(Athlete $athlete)
    {
        return $athlete->getLicenceId() === null;
    }

}
