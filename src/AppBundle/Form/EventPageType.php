<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventPageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, ['label' => 'Тип страницы', 'choices' => ['Страница с меню' => 0, 'Страница без меню' => 1, 'Поп-ап'=> 2]])
            ->add('title', TextType::class, ['label' => 'Название'])
            ->add('slug', TextType::class, ['label' => 'URL', 'attr'=>['class' => 'slug'] ])
            ->add('menu', TextType::class, ['label' => 'Название в меню'])
            ->add('form', ChoiceType::class, ['label' => 'Форма', 'choices' => ['Нет' => 0, 'Форма вопроса'=> 1]])
            ->add('formEmail', TextType::class, ['label' => 'Email отправки', 'required'=> false])
            ->add('ord', TextType::class, ['label' => 'Приоритет'])
            ->add('content', TextareaType::class, ['label' => 'Контент страницы', 'attr' => ['class' => 'ckeditor']])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\EventPage'
        ));
    }
}
