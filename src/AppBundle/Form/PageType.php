<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, ['label' => 'Название'])
            ->add('slug', null, ['label' => 'URI'])
            ->add('metaTitle', null, ['label' => 'МЕТА заголовок'])
            ->add('metaDescription', null, ['label' => 'МЕТА описание'])
            ->add('metaKeyword', null, ['label' => 'МЕТА слова'])
            ->add('body', null, [ 'label' => 'Контент', 'attr' => ['class' => 'ckeditor']])
            ->add('enabled', null, ['label' => 'Активна'])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Page'
        ));
    }
}
