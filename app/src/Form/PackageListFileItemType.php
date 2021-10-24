<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

class PackageListFileItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'file',
                FileType::class,
                [
                    'multiple' => true,
                    'mapped' => false,
                    'required' => true,
                    'constraints' => new All([
                        new File([
                                'maxSize' => '1024k',
                                'mimeTypes' => $options['mimeTypes'],
                                'mimeTypesMessage' => 'Incorrect file type',
                                'uploadNoFileErrorMessage' => 'File was not uploaded'
                            ]
                        )
                    ])]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'mimeTypes' => []
        ]);
    }
}
