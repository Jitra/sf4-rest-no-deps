<?php
declare(strict_types = 1);

namespace App\Form\VOType;

use App\Entity\ValueObject\Hash;
use InvalidArgumentException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HashType extends AbstractType implements DataTransformerInterface
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
        return 'HASH';
    }

    /**
     * {@inheritdoc}
     */
    public function transform($data)
    {
        if (null === $data || !($data instanceof Hash)) {
            return [];
        }

        $data->__toString();
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($data)
    {

        if (!$data) {
            return null;
        }

        try {

            $uuid = Hash::existing($data);

            return $uuid;

        } catch (InvalidArgumentException $e) {
            throw new TransformationFailedException();
        }
    }
}