<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Formation;
use App\Entity\Playlist;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

class FormationType extends AbstractType
{
    /**
     * Formulaire de formation
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('publishedAt', DateType::class, [
                'required' => true,
                'widget' => 'single_text',
                'data' => isset($options['data']) &&
                    $options['data']->getPublishedAt() != null ? $options['data']->getPublishedAt() : new \DateTime('now'),
                'label' => 'Date de publication (*)',
            ])
            ->add('title', TextType::class, [
                'required' => true,
                'label' => 'Nom de la formation (*)'
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Description'
            ])
            ->add('videoId', TextType::class, [
                'required' => true,
                'label' => 'Id de la vidéo (*)'
            ])
            ->add('playlist', EntityType::class, [
                'required' => true,
                'label' => 'Playlist (*)',
                'class' => Playlist::class,
                'choice_label' => 'name',
                'multiple' => false
            ])
            ->add('categories', EntityType::class, [
                'required' => false,
                'label' => 'Catégorie',
                'class' => Categorie::class,
                'choice_label' => 'name',
                'multiple' => true
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
