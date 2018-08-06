<?php
declare(strict_types = 1);

namespace App\Form\VOType;

use App\Entity\ValueObject\EmailAddress;
use App\Entity\ValueObject\PhoneNumber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhoneNumberType extends AbstractType implements DataTransformerInterface
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
        return 'PHONE_NUMBER';
    }

    /**
     * @inheritdoc
     */
    public function transform($email) : string
    {
        /** @var PhoneNumber $email */
        if (null === $email) {
            return '';
        }

        return $email->__toString();
    }

    /**
     * @inheritdoc
     */
    public function reverseTransform($email) : ?PhoneNumber
    {
        if (!$email) {
            return null;
        }

        try{

            $phoneNumber = new PhoneNumber($email);

            return $phoneNumber;

        }catch (\InvalidArgumentException $e){
            throw new TransformationFailedException();
        }
    }
}