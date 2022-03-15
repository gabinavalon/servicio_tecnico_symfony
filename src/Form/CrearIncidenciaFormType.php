<?php

namespace App\Form;

use App\Entity\Incidencia;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use \Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Usuario;
use App\Entity\Cliente;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CrearIncidenciaFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
                ->add('titulo', TextType::class, ['label'=> 'Titulo de incidencia: '])
                ->add('fecha_creacion', DateType::class, ['label'=> 'Fecha: '])
                ->add('estado', ChoiceType::class, [
                    'choices' => [
                        'Iniciada' => 'Iniciada',
                        'En proceso' => 'En proceso',
                        'Resuelta' => 'Resuelta',
                    ],
                ] , ['label'=> 'Estado de incidencia: '])
                ->add('usuario', EntityType::class, [
                    'class' => Usuario::class,
                    'choice_label' => 'nombre'
                ], ['label'=> 'Usuario creador: '])
                ->add('cliente', EntityType::class, [
                    'class' => Cliente::class,
                    'choice_label' => 'nombre'
                ], ['label'=> 'Cliente afectado: '])
                ->add('save', SubmitType::class, ['label'=> 'Enviar incidencia']) 
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Incidencia::class,
        ]);
    }

}
