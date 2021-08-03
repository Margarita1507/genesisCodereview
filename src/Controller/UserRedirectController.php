<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserCreateFormType;
use App\Form\UserLoginFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\LoginService;
use App\Service\BTCService;

class UserRedirectController extends AbstractController
{
    /**
     * @Route("/user/create", name="user_create")
     */
    public function create(Request $request): Response
    {
        $form = $this->createForm(UserCreateFormType::class, new User());

        $form->handleRequest($request);

        if($form->isSubmitted()) {
            $user = $form->getData();
            if ($user->isCreate()) {
                return $this->render('user/create.html.twig', [
                    'form' => $form->createView(),
                    'emailRegAlready' => 'This email is already registered, please try to login',
                ]);
            } else {
                $user->writeUser();
            }
            LoginService::loginUser($request);
            return $this->redirectToRoute('btcRate');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/user/login", name="user_login")
     */
    public function login(Request $request): Response
    {
        if(LoginService::isUserLogged($request)) {
            session_destroy();
        }

        $form = $this->createForm(UserLoginFormType::class);

        $form->handleRequest($request);

        if($form->isSubmitted()) {
            $user = $form->getData();
            if( $user->isLogin()) {
                LoginService::loginUser($request);
                return $this->redirectToRoute('btcRate');
            } else {
                return $this->render('user/login.html.twig', [
                    'form' => $form->createView(),
                    'incorrect' => 'Incorrect email or password',
                ]);
            }
        }

        return $this->render('user/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/btcRate", name="btcRate")
     */
    public function btcRate(Request $request): Response
    {
        if (LoginService::isUserLogged($request)) {
            return $this->render('user/btcRate.html.twig', ['btcRate' => BTCService::currentBtcRate(),]);
        }

        return $this->redirectToRoute('user_login');
    }
}
