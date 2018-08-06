<?php
declare(strict_types = 1);

namespace App\Form;

use App\Entity\ValueObject\Locale;
use App\Form\VOType\DeviceInfoType;
use App\Form\VOType\EmailAddressType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Form\VOType\LocaleType;
use Symfony\Component\Validator\Constraints\NotNull;

class LoginFormType extends BaseFormType
{
    protected $formClass = LoginData::class;

    public function buildForm(FormBuilderInterface $builder, array $options) :void
    {
        $builder
            ->add('login', EmailAddressType::class, [
                'constraints' => [
                    new NotNull()
                ],
                'invalid_message' => 'Invalid login', // customize transformer exception
                'empty_data'  => null,
            ])
            ->add('password', TextType::class, [
                'constraints' => [
                    new NotNull()
                ],
                'empty_data'  => null,
            ])
            ->add('deviceInfo', DeviceInfoType::class, [])
            ->add('locale', LocaleType::class,[
                'empty_data' => (string)Locale::default()
            ])
        ;


    }
}