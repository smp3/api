<?php

namespace SMP3Bundle\Form;

use Symfony\Component\Form\AbstractType;
use SMP3Bundle\Entity\User;
use SMP3Bundle\Entity\PlaylistItem;

class PlaylistType extends AbstractType
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('title')
                ->add('items', 'collection', ['type' => new PlaylistItem(), 'required' => false])

                ;
    }

    public function getName()
    {
        return 'playlist';
    }
}
