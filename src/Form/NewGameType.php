<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class NewGameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('player_count', NumberType::class, [
                'label' => 'Number of players:',
                'html5' => true,
                'attr' => [
                    'min' => 1,
                    'max' => 5,
                ]
            ])
            ->add('play', SubmitType::class, [
                'label' => 'Play!',
            ]);
    }
}