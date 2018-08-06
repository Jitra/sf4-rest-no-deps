<?php
declare(strict_types=1);

namespace App\Form\VOType;
use Carbon\Carbon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CarbonType extends AbstractType implements DataTransformerInterface
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
        return 'CARBON';
    }

    /**
     * {@inheritdoc}
     */
    public function transform($data):string
    {

        if (null === $data || !($data instanceof Carbon)) {
            return '';
        }

        return $data->toTimeString();
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($data): ?Carbon
    {

        if (!$data) {
            return null;
        }

        try {

            $carbon = new Carbon($data);

            return $carbon;
        } catch (\Exception $e) {
            throw new TransformationFailedException();
        }
    }
}