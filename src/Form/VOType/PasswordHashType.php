<?php
declare(strict_types=1);

namespace App\Form\VOType;

use App\Entity\ValueObject\EmailAddress;
use App\Entity\ValueObject\PasswordHash;
use App\Entity\ValueObject\PhoneNumber;
use App\Entity\ValueObject\SaltHash;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordHashType extends AbstractType implements DataTransformerInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

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
        return 'PASSWORD';
    }

    /**
     * @inheritdoc
     */
    public function transform($email): string
    {
        return '********';
    }

    /**
     * @inheritdoc
     */
    public function reverseTransform($email): ?PasswordHash
    {
        if (!$email) {
            return null;
        }

        try {

            $passwordHash = PasswordHash::createUsingPasswordEncoder($this->encoder, $email, new SaltHash());

            return $passwordHash;

        } catch (\InvalidArgumentException $e) {
            throw new TransformationFailedException();
        }
    }
}