<?php

namespace SMP3Bundle\Controller;

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
    
    function putAction(User $user) {
        return $this->handleView($this->view('test'));
    }
    
    function postAction() {
       
        $formFactory = $this->container->get('fos_user.registration.form.factory');
        $userManager = $this->container->get('fos_user.user_manager');
        $dispatcher = $this->container->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $jsonData = json_decode($request->getContent(), true); // "true" to get an associative array

        if ('POST' === $request->getMethod()) {
            $form->bind($jsonData);

            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                $userManager->updateUser($user);

                $response = new Response("User created.", 201);               

                return $response;
            }
        }

        $view = View::create($form, 400);
        return $this->get('fos_rest.view_handler')->handle($view);
    
    }

}
