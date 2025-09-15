<?php

namespace App\Controller;

use App\Entity\UserTable;
use App\Entity\AdminTable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;

class LuckyController extends AbstractController
{
    /**
     * @Route("/lucky/number", name="app_lucky_number", methods={"GET"})
     */
    public function number(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(UserTable::class)->findAll();
        return $this->render('lucky/index.html.twig', [
            'users' => $users,  
        ]);
    }
    
    
    /**
     * @Route("/lucky/save", name="app_lucky_save", methods={"POST"})
     */
    public function saveName(Request $request, EntityManagerInterface $entityManager): Response
    {
        
        $nomeDigitado = $request->request->get('name');
        $emailDigitado = $request->request->get('email');
        $telephoneNumber = $request->request->get('telephoneNumber');
        
        //dd($nomeDigitado, $emailDigitado, $telephoneNumber);
        $user = new UserTable();
        $user->setName($nomeDigitado);
        $user->setEmail($emailDigitado);
        $user->setTelephoneNumber($telephoneNumber);
        $entityManager->getRepository(UserTable::class)->add($user, true);
        //dd($user);
        

        return $this->redirectToRoute('app_lucky_number');
    }

    /**
     * @Route("/lucky/saveAdmin", name="app_lucky_saveAdmin", methods={"POST"})
     */
    public function saveAdmin(Request $request, EntityManagerInterface $entityManager): Response
    {
        $nameAdmin = $request->request->get('nameAdmin');
        $emailAdmin = $request->request->get('emailAdmin');
        $telephoneAdmin = $request->request->get('telephoneAdmin');

        $admin = new AdminTable();
        $admin->setNameAdmin($nameAdmin);
        $admin->setEmailAdmin($emailAdmin);
        $admin->setTelephoneAdmin($telephoneAdmin);
        $entityManager->getRepository(AdminTable::class)->add($admin, true);

        return $this->redirectToRoute('app_lucky_number');
    }
 
}