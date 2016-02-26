<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartnerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, ['label' => 'Название партнера'])
            ->add('type', ChoiceType::class, array(
                'choices' => array(
                    'Медицинские организации' => 'Медицинские организации',
                    'Медицинские сайты' => 'Медицинские сайты',
                    'Специализированные издания' => 'Специализированные издания',
                    'Организаторы мероприятий' => 'Организаторы мероприятий'
                ),
                'required'    => true,
                'label' => 'Вид партнеры'
            ))
            ->add('link', null, ['label' => 'Ссылка'])
            ->add('body', TextareaType::class, ['label' => 'Описание', 'attr' => ['class' => 'ckeditor']])
            ->add('shortDescription', TextareaType::class, ['label' => 'Краткое описание'])
            ->add('image', FileType::class, ['label' => 'Логотип', 'data_class' => null, 'required' => false])
            ->add('sort', IntegerType::class, ['label' => 'Порядок'])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Partner'
        ));
    }
}
