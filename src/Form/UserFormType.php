<?php
declare(strict_types = 1);

namespace App\Form;

use App\Form\VOType\EmailAddressType;
use App\Form\VOType\PasswordHashType;
use App\Form\VOType\PhoneNumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class UserFormType extends BaseFormType
{
    const CREATE_GROUP = 'app.user_form.create';
//    const UPDATE_GROUP = 'app.user_form.create';

    protected $formClass = UserData::class;

    public function buildForm(FormBuilderInterface $builder, array $options) :void
    {
        $builder

            ->add('email', EmailAddressType::class, [
                'constraints' => [
                    new NotNull(array('groups' => array(self::CREATE_GROUP, self::DEFAULT_GROUP)))
                ],
                'invalid_message' => 'Invalid email address', // customize transformer exception
                'empty_data'  => null,
            ])
            ->add('password', PasswordHashType::class, [
                'constraints' => [
                    new NotNull(array('groups' => array(self::CREATE_GROUP))),
                ],
                'empty_data'  => null,
            ])
            ->add('firstName', TextType::class, [ 'empty_data'  => ''])
            ->add('lastName', TextType::class, [ 'empty_data'  => ''])
            ->add('phoneNumber', PhoneNumberType::class, [])
        ;


    }
}