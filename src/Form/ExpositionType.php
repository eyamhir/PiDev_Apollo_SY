<?php

namespace App\Form;

use App\Entity\Portfolio;
use App\Entity\Exposition;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ExpositionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image_affiche', FileType::class, [
                'label' => 'Image de l\'affiche',
                'mapped' => false, // Indique que ce champ n'est pas mappé à une propriété de l'entité
                'required' => false, // Le champ n'est pas obligatoire
            ])
            ->add('titre')
            ->add('description')
            ->add('date_debut')
            ->add('date_fin')
            ->add('type_expo')
            ->add('lacalisation')
            ->add('portfolios', EntityType::class, [
                'class' => Portfolio::class,
                'choice_label' => 'nom_Artistique', // Le champ à afficher dans la liste déroulante
                // Autres options...
            ]);    
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exposition::class,
        ]);
    }
}
