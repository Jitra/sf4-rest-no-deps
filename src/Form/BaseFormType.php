<?php
declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class BaseFormType extends AbstractType
{
    protected $formClass = '';

    const DEFAULT_GROUP = 'Default';

    const MESSAGE_NOT_BLANK = 'This value should not be blank';
    const MESSAGE_NOT_VALID = 'This value is not valid';

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => $this->formClass,
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ]);
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}