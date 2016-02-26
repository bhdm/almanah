<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseModuleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, ['label' => 'Название модуля'])
            ->add('description', null, ['label' => 'Краткое описание'])
            ->add('type', ChoiceType::class, array(
                'choices' => array(
                    'Видео' => 'VIDEO',
                    'Текст' => 'TEXT',
                    'Тест' => 'TEST'
                ),
                'required'    => true,
                'label' => 'Тип модуля'
            ))
            ->add('enabled', ChoiceType::class, array(
                'choices' => array(
                    'Активная' => true,
                    'Неактивна' => false
                ),
                'required'    => true,
                'label' => 'Состояние'
            ))
            ->add('file', FileType::class, ['label' => 'Файл(Pdf,video)', 'data_class' => null, 'required' => false])
            ->add('text', TextareaType::class, [ 'label' => 'Контент', 'attr' => ['class' => 'ckeditor']])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\CourseModule'
        ));
    }
}
