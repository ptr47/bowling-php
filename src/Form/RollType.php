<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class RollType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pins', NumberType::class, [
                'label' => 'Pins knocked down:',
                'html5' => true,
                'attr' => [
                    'min' => 0,
                    'max' => 10,
                ]
            ])
            ->add('roll', SubmitType::class, [
                'label' => 'Roll!',
            ]);
    }
}