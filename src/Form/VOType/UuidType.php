<?php
declare(strict_types = 1);

namespace App\Form\VOType;

use App\Entity\ValueObject\Uuid;
use InvalidArgumentException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UuidType extends AbstractType implements DataTransformerInterface
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
        return 'UUID';
    }

    /**
     * {@inheritdoc}
     */
    public function transform($data)
    {
        if (null === $data || !($data instanceof Uuid)) {
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

            $uuid = Uuid::existing($data);

            return $uuid;

        } catch (InvalidArgumentException $e) {
            throw new TransformationFailedException();
        }
    }
}