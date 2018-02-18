<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class MealSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("start_date",'Symfony\Component\Form\Extension\Core\Type\DateType',
            array(
                'data'=> new \DateTime('-7 days' ),
                'attr'=> array('class'=> 'js-datepicker')
            ))
            ->add('end_date','Symfony\Component\Form\Extension\Core\Type\DateType',
                array(
                    'data'=> new \DateTime('now' ),
                    'attr'=> array('class'=> 'js-datepicker')
                ))
            ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults(array(

            'constraints'        => array(
                new Callback(array(
                    'callback' => array($this, 'checkDate'),
                )
            )
            )) );
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_meal_search_type';
    }
    public function checkDate($data, ExecutionContextInterface $context)
    {
        if ($data['start_date'] > $data['end_date']) {
            $context->buildViolation('Start date greater then end date!')
                ->addViolation();
        }
    }

}
