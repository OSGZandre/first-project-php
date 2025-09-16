<?php

namespace App\Controller;

use App\Repository\UserTableRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/lucky/save", name="app_lucky_save", methods={"POST"})
    */
    public function saveName(Request $request): Response
    {
        $data = [
            'userName' => $request->request->get('userName'),
            'email' => $request->request->get('email'),
            'telephoneNumber' => $request->request->get('telephoneNumber'),
        ];
        $this->userRepository->inserirUser($data);

        return $this->redirectToRoute('app_lucky_number');
    }
}