<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
            ->add('slug', null, [ 'label' => 'URL'])
            ->add('preview', FileType::class, [ 'label' => 'Картинка', 'data_class' => null, 'required' => false])
            ->add('slider', FileType::class, [ 'label' => 'Картинка (слайдер)', 'data_class' => null, 'required' => false])
            ->add('important', ChoiceType::class, array(
                'choices' => array(
                    'Обычная' => false,
                    'Важнаая' => true,
                ),
                'required'    => false,
                'label' => 'Важность'
            ))
            ->add('specialties', null, [ 'label' => 'Специальности', 'attr' => ['class' => 'multiselect']])
            ->add('category', null, [ 'label' => 'Категория'])

            ->add('city', null, [ 'label' => 'Город'])
            ->add('adrs', null, [ 'label' => 'Адрес'])
            ->add('start', DateType::class, [ 'label' => 'Дата начала'])
            ->add('end',  DateType::class, [ 'label' => 'Дата окончания'])

            ->add('contacts', TextareaType::class, [ 'label' => 'Контакная информация'])
            ->add('source', null, [ 'label' => 'Сайт', 'required' => false ])
            ->add('anons', null, [ 'label' => 'Анонс' ])
            ->add('body', null, [ 'label' => 'Контент', 'attr' => ['class' => 'ckeditor']])
            ->add('main', ChoiceType::class, array(
                'choices' => array(
                    'Да' => true,
                    'Нет' => false
                ),
                'required'    => false,
                'label' => 'Баннер на главной'
            ))
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
