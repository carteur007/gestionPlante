<?php

namespace App\Form;

use App\Entity\Plante;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlanteBesoinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('especePlante') 
            ->add('dateAchat')
            ->add('imagePlante', FileType::class, [
                'label' => 'Image(pnp,jpg,jpeg)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'mimeTypes' => ['image/png','image/jpg','image/jpeg'],
                        'maxSizeMessage' => 'La taille du fichier ne peux depasse 10M',
                        'mimeTypesMessage' => 'Formats acceptes(png,jpeg,jpg)',
                    ])
                ],
            ])
            ->add('quantiteEau',IntegerType::class,[
                'mapped'=> false,
            ])
            ->add('frecArrosage',IntegerType::class,[
                'mapped'=> false,
            ]);
            /*
            ->add('imagePlante')
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
            ])*/;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plante::class,
        ]);
    }
}
