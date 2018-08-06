<?php
declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Translation\TranslatorInterface;

class InvalidFormException extends ValidationException
{
    /**
     * @var Form
     */
    protected $form;
    /**
     * @var string
     */
    protected $message = 'Form is Invalid';

    public function __construct(FormInterface $form)
    {
        $this->form = $form;
        parent::__construct('', '');

    }

    public function getErrors(): array
    {
        return $this->getFormErrors($this->form);
    }

    public function getTranslatedErrors(TranslatorInterface $translator): array
    {
        return $this->getFormErrors($this->form);

    }

    public function getForm(): FormInterface
    {
        return $this->form;
    }

    /**
     * @param FormInterface $form
     * @return string[][]
     */
    protected function getFormErrors(FormInterface $form): array
    {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            if ($form->isRoot()) {
                $errors['uncategorized'][] = $error->getMessage();
            } else {
                $errors[] = $error->getMessage();
            }
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $childName = $child->getName();
                $childErrors = $this->getFormErrors($child);
                if ($this->isAssoc($childErrors)) {
                    foreach ($childErrors as $childErrorName => $childError) {
                        $errors[$childName][$childErrorName] = $childError;
                    }
                } else {
                    $errors[$child->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }


    protected function isAssoc(array $arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}