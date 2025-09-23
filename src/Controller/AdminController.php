<?php

namespace App\Controller;

use App\Repository\AdminTableRepository;
use App\Repository\UserTableRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private $adminRepository;
    private $userRepository;

    public function __construct(AdminTableRepository $adminRepository, UserTableRepository $userRepository)
    {
        $this->adminRepository = $adminRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/admin", name="app_admin", methods={"GET"})
     */
    public function index(SessionInterface $session): Response
    {
        if (!$session->has('user_id')) {
            $this->addFlash('error', 'Você precisa estar logado');
            return $this->redirectToRoute('app_login');
        }

        $users = $this->userRepository->listarUser();

        return $this->render('admin/index.html.twig', [
            'users' => $users,
            'user_name' => $session->get('user_name'),
            'user_email' => $session->get('user_email'),
        ]);
    }

    /**
     * @Route("/admin/edit/{id}", name="app_admin_edit", methods={"GET", "POST"})
     */
    public function editUser(Request $request, int $id, SessionInterface $session): Response
    {
        if (!$session->has('user_id')) {
            $this->addFlash('error', 'Você precisa estar logado!');
            return $this->redirectToRoute('app_login');
        }

        $user = $this->userRepository->buscarUserPorId($id);

        if (!$user) {
            $this->addFlash('error', 'Usuário não encontrado.');
            return $this->redirectToRoute('app_admin');
        }

        if ($request->isMethod('POST')) {
            $data = [
                'id' => $id,
                'userName' => $request->request->get('userName'),
                'email' => $request->request->get('email'),
                'telephoneNumber' => $request->request->get('telephoneNumber'),
                'userPassword' => $request->request->get('userPassword'),
            ];

            $this->userRepository->editarUser($data);
            $this->addFlash('success', 'Usuário atualizado com sucesso!');
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/edit.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/admin/delete/{id}", name="app_admin_delete", methods={"GET"})
     */
    public function deleteUser(int $id, SessionInterface $session): Response
    {
        if (!$session->has('user_id')) {
            $this->addFlash('error', 'Você precisa estar logado!');
            return $this->redirectToRoute('app_login');
        }

        $user = $this->userRepository->buscarUserPorId($id);

        if (!$user) {
            $this->addFlash('error', 'Usuário não encontrado.');
            return $this->redirectToRoute('app_admin');
        }

        $this->userRepository->removerUser($id);
        $this->addFlash('success', 'Usuário removido com sucesso!');
        return $this->redirectToRoute('app_admin');
    }
}