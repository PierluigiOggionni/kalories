<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MealType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder


            ->add('mealDateTime','AppBundle\Form\Type\DateTimePickerType', array(
            'label' => 'label.meal_time'

        ))

            ->add('text','Symfony\Component\Form\Extension\Core\Type\TextType', array('label'=>'label.description'))
            ->add('calories','Symfony\Component\Form\Extension\Core\Type\IntegerType', array("label"=>"Calories"))
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Meal'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_meal';
    }


}
