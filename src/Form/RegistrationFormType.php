<?php

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use \Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class RegistrationFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
                ->add('nombre', TextType::class, [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Por favor, introduzca un nombre',
                        ])]])
                ->add('apellidos', TextType::class, [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Por favor, introduzca los apellidos',
                        ])]])
                ->add('email', TextType::class, [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Por favor, introduzca un email',
                        ])]])
                ->add('telefono', NumberType::class, [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Por favor, introduzca un telefono',
                        ])]])
                ->add('foto', FileType::class, [
                    'required' => true,
                    // unmapped fields can't define their validation using annotations
                    // in the associated entity, so you can use the PHP constraint classes
                    'constraints' => [
                        new File([
                            'maxSize' => '1024k',
                            'mimeTypes' => [
                                'image/png',
                                'image/jpg',
                                'image/jpeg'
                            ],
                            'mimeTypesMessage' => 'Utilice un formato válido (jpeg, png)',
                                ])
                    ],
                ])
                ->add('plainPassword', PasswordType::class, [
                    // instead of being set onto the object directly,
                    // this is read and encoded in the controller
                    'mapped' => false,
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Por favor, introduzca una contraseña',
                                ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Tu contraseña debe tener al menos  {{ limit }} caracteres',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                                ]),
                    ],
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }

}
