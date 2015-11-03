<?php

namespace SMP3Bundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use SMP3Bundle\Controller\APIBaseController;
use SMP3Bundle\Entity\User;
use SMP3Bundle\Form\UserType;

/**
 * @RouteResource("user")
 */
class UserController extends APIBaseController implements ClassResourceInterface {

    function getCurrentAction() {
        return $this->handleView($this->view($this->getUser(), 200));
    }

    function getAction(User $user) {
        return $this->handleView($this->view($user, 200));
    }

    function getAllAction() {

        return $this->handleView($this->view(
                                $this->getDoctrine()->getManager()->getRepository('SMP3Bundle:User')->findAll()
                        ), 200);
    }

    protected function setUserData(Request $request, User $user) {
        $data = json_decode($request->getContent());

        $user->setNN('username', $data->username);
        $user->setNN('path', $data->path);
        $user->setNN('email', $data->email);
        
        if(isset($data->password1)) {
            $user->setPlainPassword($data->password1);
        }
        
    }
    
    
    function deleteAction(User $user) {
        $this->em->remove($user);
        $this->em->flush();
        return $this->handleView($this->view('OK'));
    }
    
    function putAction(Request $request, User $user) {

        $this->setUserData($request, $user);
        $this->em->persist($user);
        $this->em->flush();
        
        return $this->handleView($this->view('OK'));
    }

    function postAction(Request $request) {
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->createUser();
        $this->setUserData($request, $user);
        $user->setEnabled(true);
        $userManager->updateUser($user, true);
        return $this->handleView($this->view('OK'));
    }

}
