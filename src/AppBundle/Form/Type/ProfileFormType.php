<?php
namespace AppBundle\Form\Type;

use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', null, ['label' => 'E-mail']);
        $builder->add('username', HiddenType::class);
        $builder->add('lastName', null, ['label' => 'Фамилия']);
        $builder->add('firstName', null, ['label' => 'Имя']);
        $builder->add('surName', null, ['label' => 'Отчество']);
        $builder->add('birthDate', null, ['label' => 'Дата рождения','years' => range(2000,1920)]);

        $builder->add('country', null, ['label' => 'Страна', 'attr' => ['class' => 'county']]);
        $builder->add('city', TextType::class, ['label' => 'Город', 'attr' => ['class' => 'city']]);
        $builder->add('phone', null, ['label' => 'Телефон', 'attr' => ['class' => 'phone']]);

        $builder->add('specialty', null, [
            'label' => 'Специальность',
            'data_class' => null,
            'attr' => [
                'class' => 'specialty',
                'data-placeholder' => 'Выберите специальность'
            ],
            'required' => true
        ]);
        $builder->add('status', ChoiceType::class, array(
            'choices' => array(
                'практикующий врач' => 'практикующий врач',
                'студент' => 'студент',
                'медицинский представитель' => 'медицинский представитель',
                'не практикующий врач' => 'не практикующий врач',
                'не врач' => 'не врач'
            ),
            'expanded' => true,
            'multiple' => false,
            'required'    => true,
            'label' => 'Статус'
        ));
        $builder->remove('username');
        $builder->remove('current_password');
    }


    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_profile_edit';
    }

}