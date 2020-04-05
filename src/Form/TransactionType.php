<?php

namespace App\Form;

use App\Entity\Contact;
use App\Form\Transaction;
use App\Requisition;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as NativeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class TransactionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'taxableSupplyDate',
                NativeType\DateType::class,
                [
                    'widget' => 'single_text',
                ]
            )
            ->add(
                'contact',
                EntityType::class,
                [
                    'class' => Contact::class,
                    'query_builder' => function (EntityRepository $repository) {
                        return $repository->createQueryBuilder('c');
                    },
                    'choice_label' => 'name',
                    'multiple' => false,
                    'expanded' => true,
                    'error_bubbling' => false,
                ]
            )
            ->add('description', NativeType\TextType::class)
            ->add(
                'rows',
                NativeType\CollectionType::class,
                [
                    'entry_type' => Transaction\RowType::class,
                    'entry_options' => ['label' => false],
                    'allow_add' => true,
                ]
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Requisition\TransactionAddOrEdit::class,
            ]
        );
    }
}
