<?php

namespace App\Form;

use App\Entity\Artiste;
use App\Entity\Departement;
use App\Entity\Festival;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class FestivalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('lieu', EntityType::class, [
                "class" => Departement::class,
                "choice_label" => "nom",
                "multiple" => false
            ])
            ->add('artistes', EntityType::class, [
                "class" => Artiste::class,
                "choice_label" => "nom",
                "multiple" => true,
                "required" => false,
                'expanded'=>true,
                'by_reference'=>false
            ])
            ->add("affiche", FileType::class, [
                "label" => "Affiche du festival",
                "mapped" => false,
                "required" => false,
                "constraints" => [
                    new File([
                        'maxSize' => '1024k',
                        'maxSizeMessage' => "Le fichier est trop volumineux (max: {{ limit }})",
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Choisissez une image valide',
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Festival::class,
        ]);
    }
}
