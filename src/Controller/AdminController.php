<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AdminTableRepository;
use App\Repository\UserTableRepository;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/admin", name="app_admin")
     */
    public function index(): Response
    {
        $users = $this->userRepository->listarUser();

        return $this->render('admin/index.html.twig', [
            'user' => $users,
        ]);
    }

    /**
     * @Route("/admin/edit/{id}", name="app_admin_edit", methods={"GET","POST"})
     */
    public function editUser(Request $request, int $id): Response
    {
        if ($request->isMethod('POST')) {
            $data = [
                'id' => $id,
                'userName' => $request->request->get('name'),
                'email' => $request->request->get('email'),
                'telephoneNumber' => $request->request->get('telephoneNumber'),
            ];

            $this->userRepository->editarUser($data);

            return $this->redirectToRoute('app_admin');
        }

        $user = $this->userRepository->buscarUserPorId($id);

        return $this->render('admin/edit.html.twig', [
            'user' => $user,
        ]);
    }
}