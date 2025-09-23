<?php

namespace App\Controller;

use App\Repository\UserTableRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $userRepository;

    public function __construct(UserTableRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/lucky/number", name="app_lucky_number", methods={"GET"})
     */
    public function number(): Response
    {
        $users = $this->userRepository->listarUser();
        return $this->render('lucky/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/lucky/save", name="app_lucky_register", methods={"POST"})
     */
    public function register(Request $request): Response
    {
        $data = [
            'userName' => $request->request->get('userName'),
            'email' => $request->request->get('email'),
            'telephoneNumber' => $request->request->get('telephoneNumber'),
            'userPassword' => $request->request->get('userPassword'),
        ];

        $this->userRepository->inserirUser($data);

        $this->addFlash('success', 'Cadastro realizado com sucesso! Faça login.');
        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/login", name="app_login", methods={"GET", "POST"})
     */
    public function login(Request $request, SessionInterface $session): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            $user = $this->userRepository->buscarPorEmail($email);

            if (!$user) {
                $this->addFlash('error', 'E-mail ou senha inválidos.');
                return $this->render('login/login.html.twig');
            }

            if ($this->userRepository->verifyPassword($password, $user['userPassword'])) {
                $session->set('user_id', $user['id']);
                $session->set('user_email', $user['email']);
                $session->set('user_name', $user['userName']);

                $session->migrate();

                $authToken = bin2hex(random_bytes(16));
                $session->set('auth_token', $authToken);

                $this->addFlash('success', 'Login realizado com sucesso!');
                return $this->redirectToRoute('app_admin');
            } else {
                $this->addFlash('error', 'E-mail ou senha inválidos.');
            }
        }

        return $this->render('login/login.html.twig'); // Não passa 'clear_local_storage' aqui
    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout(SessionInterface $session): Response
    {
        $session->invalidate();
        $this->addFlash('success', 'Logout realizado com sucesso!');
        return $this->render('login/login.html.twig', [
            'clear_local_storage' => false,
        ]);
    }
}