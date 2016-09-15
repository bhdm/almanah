<?php

namespace AppBundle\Form;

use AppBundle\Entity\Publication;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class UserNewType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [ 'label' => 'Заголовок материала'])
            ->add('anons', TextareaType::class, [ 'label' => 'Анонс (небольшое вступление)', 'required' => true])
            ->add('body', TextareaType::class, [ 'label' => 'Контент (текст материала)', 'attr' => ['class' => 'froala']])
            ->add('source', TextType::class, [ 'label' => 'Источник (ссылка на первоисточник, если есть)', 'required' => false])
            ->add('type', ChoiceType::class, array(
                'choices' => array(
                    'Публикация' => Publication::PUBLICATIONS,
                    'Новость' => Publication::NEWS,
                    'Клиническое иследование' => Publication::CLINIC
                ),
                'required'    => true,
                'label' => 'Тип публикации'
            ))
            ->add('submit', SubmitType::class, ['label' => 'Опубликовать', 'attr' => ['class' => 'btn-primary']]);
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
