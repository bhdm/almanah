<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendarType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', null, ['label' => 'Фамилия'])
            ->add('lastName', null, ['label' => 'Имя'])
            ->add('surName', null, ['label' => 'Отчество'])
            ->add('anons', null, ['label' => 'Анонс'])
            ->add('text', null, ['label' => 'Текст'])
            ->add('title', null, ['label' => 'Заголовок'])
            ->add('date', null, ['label' => 'Дата'])
            ->add('birthdate', null, ['label' => 'Дата рождения'])
            ->add('gone', null, ['label' => 'Прошло'])
            ->add('photo', null, ['label' => 'Фото'])
            ->add('datetime', DateTimeType::class, ['label' => 'Дата'])
            ->add('dayOfWeek', null, ['label' => 'День недели'])
            ->add('dayNumber', null, ['label' => 'Номер дня'])
            ->add('month', null, ['label' => 'Месяц'])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Calendar'
        ));
    }
}
