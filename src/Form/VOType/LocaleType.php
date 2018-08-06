<?php
declare(strict_types=1);

namespace App\Form\VOType;

use InvalidArgumentException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\ValueObject\Locale;

class LocaleType extends AbstractType implements DataTransformerInterface
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
        return 'LOCALE';
    }

    /**
     * {@inheritdoc}
     */
    public function transform($data)
    {
        if (null === $data || !($data instanceof Locale)) {
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

            $locale = Locale::fromStringOrDefault($data);

            return $locale;

        } catch (InvalidArgumentException $e) {
            throw new TransformationFailedException();
        }
    }
}