<?php

namespace SMP3Bundle\Form;

use Symfony\Component\Form\AbstractType;

class UserType extends AbstractType {

     public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options) {
         $builder
                ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
                ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
                ->add('plainPassword', 'repeated', array(
                    'type' => 'password',
                    'options' => array('translation_domain' => 'FOSUserBundle'),
                    'first_options' => array('label' => 'form.password'),
                    'second_options' => array('label' => 'form.password_confirmation'),
                    'invalid_message' => 'fos_user.password.mismatch',
                ))
            ;
               
    }
    
    public function getName() {
        return 'fos_user_rest_registration';
    }

}
