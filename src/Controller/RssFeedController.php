<?php

namespace App\Controller;

use App\Manager\RssFluxManager;
use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RssFeedController
 * @package App\Controller
 */
class RssFeedController extends AbstractController
{
    /**
     * @Route("/")
     *
     * @param Request $request
     * @param UserManager $userManager
     * @return Response
     */
    public function login(Request $request, UserManager $userManager): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            try {
                $user = $userManager->getUser($email, $password);

                if (null !== $user) {
                    $session = new Session();

                    $token = hash("sha256", $email.$password);
                    $session->set('token', $token);

                    return $this->redirect('/rss_flux');
                }
            } catch (\Exception $exception) {
                $errorMessage = 'Error during user login';
            }
        }

        return $this->render("login/login.html.twig", ['errorMessage' => $errorMessage ?? '']);
    }

    /**
     * @Route("/register")
     *
     * @param Request $request
     * @param UserManager $userManager
     * @return Response
     */
    public function register(Request $request, UserManager $userManager): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            try {
                $userManager->createUser($email, $password);
                return $this->redirect("/");
            } catch (\Exception $exception) {
                $errorMessage = 'Error during user creation';
            }

        }

        return $this->render("login/register.html.twig", ['errorMessage' => $errorMessage ?? '']);
    }

    /**
     * @Route("/checkEmail")
     * @param Request $request
     * @param UserManager $userManager
     * @return JsonResponse
     * @throws \Exception
     */
    public function checkEmail(Request $request, UserManager $userManager): JsonResponse
    {
        $email = $request->get('email');

        $userExist = false;
        if ($userManager->getUser($email)) {
            $userExist = true;
        }

        return new JsonResponse(['userExist' => $userExist]);
    }

    /**
     * @Route("/rss_flux")
     *
     * @param Request $request
     * @param RssFluxManager $rssFluxManager
     * @return Response
     */
    public function rssFlux(Request $request, RssFluxManager $rssFluxManager)
    {
        $session = new Session();

        if (null === $session->get('token')) {
            $this->redirect('/');
        }

        $mostUsedWords = $rssFluxManager->read();

        return $this->render('rss/mostUsedWords.html.twig', ['mostUsedWords' => $mostUsedWords]);
    }
}