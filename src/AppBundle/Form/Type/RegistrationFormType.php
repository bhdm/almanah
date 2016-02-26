<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', null, ['label' => 'E-mail']);
        $builder->add('username', HiddenType::class);
        $builder->add('lastName', null, ['label' => 'Фамилия']);
        $builder->add('firstName', null, ['label' => 'Имя']);
        $builder->add('surName', null, ['label' => 'Отчество']);
        $builder->add('birthDate', null, ['label' => 'Дата рождения','years' => range(2000,1920)]);
        $builder->add('sex', ChoiceType::class, array(
            'choices' => array(
                'Мужской' => 'M',
                'Женский' => 'W'
            ),
            'required'    => true,
            'label' => 'Пол'
        ));
        $builder->add('phone', null, ['label' => 'Телефон', 'attr' => ['class' => 'phone']]);
        $builder->add('plainPassword', RepeatedType::class, array(
            'type' => PasswordType::class,
            'invalid_message' => 'Пароли должны совпадать',
            'options' => array('attr' => array('class' => 'password-field')),
            'required' => true,
            'first_options'  => array('label' => 'Пароль'),
            'second_options' => array('label' => 'Повторите пароль'),
        ));

        $builder->add('university', TextType::class, [
            'label' => 'Университет',
            'data_class' => null,
            'attr' => [
                'class' => 'university',
                'data-placeholder' => 'Выберите университет'
            ]
        ]);
        $builder->add('specialty', null, [
            'label' => 'Специальность',
            'required' => true,
            'data_class' => null,
            'attr' => [
                'class' => 'specialty',
                'data-placeholder' => 'Выберите специальность'
            ]
        ]);
//        $builder->add('university', null, ['label' => 'Университет', 'attr' => ['class' => 'university', 'data-placeholder' => 'Выберите университет']]);
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

}