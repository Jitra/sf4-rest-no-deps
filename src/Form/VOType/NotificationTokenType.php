<?php
declare(strict_types=1);

namespace App\Form\VOType;

use App\Entity\ValueObject\Hash;
use App\Entity\ValueObject\NotificationToken;
use InvalidArgumentException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotificationTokenType extends AbstractType implements DataTransformerInterface
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
        return 'NOTIFICATION_TOKEN';
    }

    /**
     * @inheritdoc
     */
    public function transform($token): string
    {
        /** @var NotificationToken $token */
        if (null === $token) {
            return '';
        }

        return $token->__toString();
    }

    /**
     * @inheritdoc
     */
    public function reverseTransform($token): ?NotificationToken
    {
        if (!$token) {
            return null;
        }

        try {
            $notificationToken = new NotificationToken($token);

            return $notificationToken;

        } catch (\InvalidArgumentException $e) {
            throw new TransformationFailedException();
        }
    }
}