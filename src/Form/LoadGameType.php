<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class LoadGameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lane_id', TextType::class, [
                'label' => 'Id of game:',
                'attr' => [
                    'required' => true,
                ]
            ])
            ->add('load', SubmitType::class, [
                'label' => 'Load game!',
            ]);
    }
}