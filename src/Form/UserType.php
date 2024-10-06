<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];
        $roles = ['ROLE_ADMIN',
        'ROLE_STUDENT',
        'ROLE_TEACHER',
        'ROLE_LIBRARIAN',
        'ROLE_CLIENT'];
        $builder
        ->add('firstName')
        ->add('lastName')
        ->add('email')
        ->add('phone')
        ->add('fakNo')
        ->add('password');

        // ->add('wallet');
        if (in_array("ROLE_ADMIN",$user->getRoles())){
        $builder
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'admin' => $roles[0],
                    'student' => $roles[1],
                    'teacher' => $roles[2],
                    'librarian' => $roles[3],
                    'client' => $roles[4],  
                ],
                'multiple' => true,
                'expanded' => false,
            ])
                ->add('isVerified');
        };
        $builder
            ->add('save',SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'user'=>null,
        ]);
    }
}