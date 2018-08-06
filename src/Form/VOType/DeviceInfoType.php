<?php

namespace App\Form\VOType;

use App\Entity\ValueObject\DeviceInfo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Exception\UncategorizedValidationException;

class DeviceInfoType extends AbstractType implements DataTransformerInterface
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', TextType::class, [
                'empty_data' => 'other'
            ])
            ->add('pushNotificationToken', NotificationTokenType::class)
            ->add('deviceId', TextType::class, [
                'empty_data' => ''
            ]);

        $builder->addModelTransformer($this);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'compound' => true,
            'allow_extra_fields' => true,
            'error_bubbling' => false, // prevent bubbling outside of field
            'error_mapping' => [
                '.' => 'DEVICE_INFO',
            ]
        ]);
    }

    public function getName()
    {
        return 'DEVICE_INFO';
    }

    /**
     * @inheritdoc
     */
    public function transform($data) : ?array
    {
        if (null === $data || !($data instanceof DeviceInfo)) {
            return $data;
        }

        return [
            'type' => $data->getType(),
            'pushNotificationToken' => $data->getPushNotificationToken(),
            'deviceId' => $data->getDeviceId()
        ];
    }

    /**
     * @inheritdoc
     */
    public function reverseTransform($data) : ? DeviceInfo
    {
        if (!is_array($data)) {
            return null;
        }
        /**
         * Constraints:
         * type string = valid obj
         * ?pushNotificationToken string len=1 = optional props
         * ?deviceId = optional props
         */

        $deviceInfo = DeviceInfo::autoDetect($data['type']);

        $deviceInfo->setPushNotificationToken($data['pushNotificationToken']);
        $deviceInfo->setDeviceId($data['deviceId']);

        return $deviceInfo;

    }
}