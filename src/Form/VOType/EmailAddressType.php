<?php
declare(strict_types = 1);

namespace App\Form\VOType;

use App\Entity\ValueObject\EmailAddress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailAddressType extends AbstractType implements DataTransformerInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'compound' => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'EMAIL';
    }

    /**
     * @inheritdoc
     */
    public function transform($email) : string
    {
        /** @var EmailAddress $email */
        if (null === $email) {
            return '';
        }

        return $email->__toString();
    }

    /**
     * @inheritdoc
     */
    public function reverseTransform($email) : ?EmailAddress
    {
        if (!$email) {
            return null;
        }

        try{

            $emailAddress = new EmailAddress($email);

            return $emailAddress;

        }catch (\InvalidArgumentException $e){
            throw new TransformationFailedException();
        }
    }
}