<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class PublicationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [ 'label' => 'Название'])
            ->add('preview', FileType::class, [ 'label' => 'Картинка', 'data_class' => null, 'required' => false])
            ->add('video', FileType::class, [ 'label' => 'Видео', 'data_class' => null, 'required' => false])
            ->add('category', null, [ 'label' => 'Категория'])
            ->add('specialties', null, [ 'label' => 'Специальности', 'attr' => ['class' => 'multiselect']])

            ->add('allowCommentary', ChoiceType::class, array(
                'choices' => array(
                    'Запретить' => false,
                    'Разрешить' => true,
                ),
                'required'    => false,
                'label' => 'Комментарии'
            ))

            ->add('slug', TextType::class, [ 'label' => 'URI'])
            ->add('anons', TextareaType::class, [ 'label' => 'Анонс', 'required' => false])
            ->add('body', TextareaType::class, [ 'label' => 'Контент', 'attr' => ['class' => 'ckeditor']])
            ->add('source', TextType::class, [ 'label' => 'Источник', 'required' => false])
            ->add('created', null, [ 'label' => 'Дата создания'])
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
            'data_class' => 'AppBundle\Entity\Publication'
        ));
    }
}
