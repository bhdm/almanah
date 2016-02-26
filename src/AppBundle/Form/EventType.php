<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [ 'label' => 'Название'])
            ->add('type', ChoiceType::class, array(
                'choices' => array(
                    'Конгрессы и Съезды' => 'CONGRESS',
                    'Школы' => 'SCHOOL',
                    'Партнерcкое' => 'PARTNER'
                ),
                'required'    => true,
                'label' => 'Тип мероприятия'
            ))
            ->add('allowCommentary', ChoiceType::class, array(
                'choices' => array(
                    'Запретить' => false,
                    'Разрешить' => true,
                ),
                'required'    => false,
                'label' => 'Комментарии'
            ))
            ->add('specialties', null, [ 'label' => 'Специальности', 'attr' => ['class' => 'multiselect']])
//            ->add('owner', ChoiceType::class, array(
//                'choices' => array(
//                    'EAT' => 'EAT',
//                    'Партнеры' => 'Partner'
//                ),
//                'required'    => true,
//                'label' => 'Состояние'
//            ))
            ->add('city', null, [ 'label' => 'Город'])
            ->add('adrs', null, [ 'label' => 'Адрес'])
            ->add('start', DateType::class, [ 'label' => 'Дата начала'])
            ->add('time', TimeType::class, [ 'label' => 'Время начала'])
            ->add('end',  DateType::class, [ 'label' => 'Дата окончания'])
            ->add('partner', null, [ 'label' => 'Партнеры'])
            ->add('contacts', TextareaType::class, [ 'label' => 'Контакная информация'])
            ->add('slug', null, [ 'label' => 'URI'])
            ->add('body', null, [ 'label' => 'Контент', 'attr' => ['class' => 'ckeditor']])
            ->add('enabled', ChoiceType::class, array(
                'choices' => array(
                    'Активная' => true,
                    'Неактивна' => false
                ),
                'required'    => true,
                'label' => 'Состояние'
            ));
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Event'
        ));
    }
}
