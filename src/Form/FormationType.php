<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Formation;
use App\Entity\Playlist;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Formulaire des formations.
 * 
 */
class FormationType extends AbstractType
{
    /**
     * Fonction permettant la création du formulaire.
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('publishedAt', DateType::class, [
                'widget' => 'single_text',
                'data' => isset($options['data']) &&
                    $options['data']->getPublishedAt() != null ? $options['data']->getPublishedAt() : new \DateTime('now'),
                'label' => 'Date de publication (*)',
                'required' => true
            ])
            ->add('title', null, [
                'label' => 'Titre (*)',
                'required' => true
            ])
            ->add('description', null, [
                'label' => 'Description',
                'required' => false
            ])
            ->add('videoId', null, [
                'label' => 'Id de la vidéo (*)',
                'required' => true
            ])
            ->add('playlist', EntityType::class, [
                'class' => Playlist::class,
                'choice_label' => 'name',
                'label' => 'Playlist (*)',
                'required' => true
            ])
            ->add('categories', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'name',
                'multiple' => true,
                'label' => 'Catégories',
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Confirmer'
            ])
        ;
    }
    
    /**
     * Fonction permettant de configurer les options.
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
