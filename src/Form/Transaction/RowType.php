<?php

namespace App\Form\Transaction;

use App\Entity\Account;
use App\Entity\Transaction;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as NativeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RowType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', NativeType\TextType::class)
            ->add('amount', NativeType\NumberType::class)
            ->add(
                'debtorsAccount',
                EntityType::class,
                [
                    'class' => Account::class,
                    'choice_label' => 'name',
                    'error_bubbling' => false,
                ]
            )
            ->add(
                'creditorsAccount',
                EntityType::class,
                [
                    'class' => Account::class,
                    'choice_label' => 'name',
                    'error_bubbling' => false,
                ]
            )
            ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Transaction\Row::class,
        ]);
    }
}
