<?php

namespace App\Form;

use App\Entity\Playlist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Formulaire des playlists.
 */
class PlaylistType extends AbstractType
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
            ->add('name', null, [
                'label' => 'Nom  (*)',
                'required' => true
            ])
            ->add('description', null, [
                'attr' => [
                    'rows' => 20,
                    'cols' => 50
                ],
                'label' => 'Description',
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
            'data_class' => Playlist::class,
        ]);
    }
}
