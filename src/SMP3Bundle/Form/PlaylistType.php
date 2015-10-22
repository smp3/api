<?php

namespace SMP3Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use SMP3Bundle\Entity\User;

class PlaylistType extends AbstractType {
    protected $user;
    
    public function __construct(User $user) {
        $this->user = $user;
    }
    
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options) {
        $builder
                ->add('title')
               
                ;
               
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        
    }
    
    public function getName() {
        return 'playlist';
    }
}
