<?php

namespace App\Form;

use App\Entity\OeuvreArt;
use App\Entity\Portfolio;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class OeuvreArt1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('image_oeuvre', FileType::class, [
                'label' => 'Image de l\'oeuvre',
                'mapped' => false, // Indique que ce champ n'est pas mappé à une propriété de l'entité
                'required' => false, // Le champ n'est pas obligatoire
            ])
            ->add('description')
            ->add('date_creation')
            ->add('dimension')
            ->add('prix')
            ->add('categorie')
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
            'data_class' => OeuvreArt::class,
        ]);
    }
}
