<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, ['label' => 'Название курса'])
            ->add('author', null, ['label' => 'Автор'])
            ->add('specialties', null, ['label' => 'Специальности', 'attr' => ['class' => 'multiselect']])
            ->add('description', null, ['label' => 'Краткое описание'])
            ->add('body', null, ['label' => 'Полное описание', 'attr' => ['class' => 'ckeditor']])
            ->add('image', FileType::class, ['label' => 'Изображение', 'data_class' => null, 'required' => false])
            ->add('price', null, ['label' => 'Цена'])
            ->add('amountHour', null, ['label' => 'Кол-во часов'])
            ->add('enabled', ChoiceType::class, array(
                'choices' => array(
                    'Активная' => true,
                    'Неактивна' => false
                ),
                'required'    => true,
                'label' => 'Состояние'
            ))
            ->add('start', DateType::class, ['label' => 'Дата начала'])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Course'
        ));
    }
}
