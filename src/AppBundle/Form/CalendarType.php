<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
            ->add('type', null, array('label' => 'Тип', 'required' => true, 'attr' => array('class' => 'calType')))
            ->add('month', ChoiceType::class, array('label' => 'Месяц', 'required' => false, 'attr' => array('class' => 'month'), 'choices' => array_flip(array('Jan' => 'Январь', 'Feb' => 'Февраль', 'Mar' => 'Март', 'Apr' => 'Апрель', 'May' => 'Май', 'Jun' => 'Июнь', 'Jul' => 'Июль', 'Aug' => 'Август', 'Sep' => 'Сентябрь', 'Oct' => 'Октябрь', 'Nov' => 'Ноябрь', 'Dec' => 'Декабрь'))))
            ->add('dayOfWeek', ChoiceType::class, array('label' => 'День недели', 'required' => false, 'attr' => array('class' => 'dayOfWeek'), 'choices' => array_flip(array('Monday' => 'Понедельник', 'Tuesday' => 'Вторник', 'Wednesday' => 'Среда', 'Thursday' => 'Четверг', 'Friday' => 'Пятница', 'Saturday' => 'Суббота', 'Sunday' => 'Воскресенье'))))
            ->add('dayNumber', ChoiceType::class, array('label' => 'Номер недели', 'required' => false, 'attr' => array('class' => 'dayNumber'), 'choices' => array_flip(array('first' => 'Первая', 'second' => 'Вторая', 'third' => 'Третья', 'fourth' => 'Четвертая'))))
            ->add('date', null, array('label' => 'Дата', 'required' => false, 'attr' => array('class' => 'date')))
            ->add('birthdate', BirthdayType::class, array('label' => 'Дата рождения', 'required' => false, 'attr' => array('class' => 'birthdate')))
            ->add('gone', BirthdayType::class, array('label' => 'Дата смерти', 'required' => false, 'attr' => array('class' => 'gone')))
            ->add('lastName', null, array('label' => 'Фамилия', 'required' => false, 'attr' => array('class' => 'lastName')))
            ->add('firstName', null, array('label' => 'Имя', 'required' => false, 'attr' => array('class' => 'firstName')))
            ->add('surName', null, array('label' => 'Отчество', 'required' => false, 'attr' => array('class' => 'surName')))
            ->add('title', TextareaType::class, array('label' => 'Заголовок', 'required' => false, 'attr' => array('class' => 'title')))
            ->add('anons', TextareaType::class, array('label' => 'Анонс', 'required' => false, 'attr' => array('class' => 'ckeditor anons')))
            ->add('text', null, array('label' => 'Описание', 'required' => false, 'attr' => array('class' => 'ckeditor text')))
            ->add('photo', FileType::class, array('label' => 'Изображение', 'required' => false, 'attr' => array('class' => 'photo')))
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
