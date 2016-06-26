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
            ->add('slug', TextType::class, [ 'label' => 'URL'])
            ->add('metaDescription', TextType::class, [ 'label' => 'SEO описание'])
            ->add('metaKeyword', TextType::class, [ 'label' => 'SEO ключевые слова'])
//            ->add('preview', FileType::class, [ 'label' => 'Картинка', 'data_class' => null, 'required' => false])
//            ->add('category', null, [ 'label' => 'Категория'])
            ->add('specialties', null, [ 'label' => 'Специальности', 'attr' => ['class' => 'multiselect']])
            ->add('anons', TextareaType::class, [ 'label' => 'Анонс', 'required' => true])
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
            ))
            ->add('digest', ChoiceType::class, array(
                'choices' => array(
                    'Рассылать' => true,
                    'Не рассылать' => false
                ),
                'required'    => true,
                'label' => 'Рассылка'
            ))
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
