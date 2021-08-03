<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\Request;

class LoginService
{
    static function loginUser(Request $request): void
    {
        $session = $request->getSession();
        $session->set('login', $_POST);
    }

    static function isUserLogged(Request $request): bool
    {
        $session = $request->getSession();
        if ($session->get('login')) {
            return true;
        }
        return false;
    }
}