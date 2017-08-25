<?php

namespace AppBundle\Registration\Step;

use AppBundle\Entity\Athlete;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractStep implements StepInterface
{
    /** @var FormFactory */
    private $formFactory;

    /** @var ValidatorInterface */
    private $validator;

    /** @var Athlete */
    protected $athlete;

    /** @var FormInterface */
    protected $form;

    /**
     * AbstractStep constructor.
     *
     * @param FormFactory        $formFactory
     * @param ValidatorInterface $validator
     */
    public function __construct(FormFactory $formFactory, ValidatorInterface $validator)
    {
        $this->formFactory = $formFactory;
        $this->validator = $validator;
    }

    public function setAthlete(Athlete $athlete)
    {
        $this->athlete = $athlete;

        $this->form = $this->createForm($this->getFormType(), $athlete);
    }

    public function handle(Request $request)
    {
        $this->form->handleRequest($request);
    }

    public function isValid(Athlete $athlete = null)
    {
        if (null === $athlete) {
            return $this->form->isSubmitted() && $this->form->isValid();
        }

        $violationList = $this->validator->validate($athlete, null, $this->getValidationGroup());

        return $violationList->count() === 0;
    }

    public function getViewData()
    {
        return [
            'athlete' => $this->athlete,
            'form' => $this->form->createView(),
        ];
    }

    public function isSkippable(Athlete $athlete)
    {
        return false;
    }

    /**
     * @param string $type
     * @param null   $data
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function createForm($type, $data = null)
    {
        return $this->formFactory->create($type, $data, []);
    }

    /**
     * @return string
     */
    protected abstract function getFormType();

    /**
     * @return string[]
     */
    protected abstract function getValidationGroup();
}
