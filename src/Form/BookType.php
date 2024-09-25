<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Category;
use App\Form\CategoryType as FormCategoryType;
use Symfony\Component\Form\AbstractType;
use App\Form\CategoryType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use function PHPUnit\Framework\isEmpty;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $book = $options['data']->getTitle();
        $user = $options['user'];
        $builder
            ->add('cover', FileType::class, [
                'label' => 'Cover (IMG file)',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file',
                    ])
                ],
            ])
            ->add('title')
            ->add('description', TextareaType::class)
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false
            ])
            ->add('book', FileType::class, [
                'label' => 'Book (PDF file)',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])

                ],
            ])
            ->add('avtor', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'name',
                'required' => true,
                'multiple' => true,
                'expanded' => true,
                'by_reference' => true,

            ])

            ->add('releaseYear')
            ->add('publishingHouse')
            ->add('price');
            // ->add('views')
            // ->add('isFree');
        if ($user->getRoles()[0] == 'ROLE_LIBRARIAN'  or $user->getRoles()[0] == 'ROLE_ADMIN') {
            $builder
                ->add('validation')
                ->add('isPublish');
        }
        $builder
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
            'user' => null,
        ]);
    }
}
