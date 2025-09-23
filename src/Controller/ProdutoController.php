<?php
namespace App\Controller;

use App\Repository\ProdutoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
class ProdutoController extends AbstractController
{

    private $produtoRepository;

    public function __construct(ProdutoRepository $produtoRepository)
    {
        $this->produtoRepository = $produtoRepository;
    }
    /**
     * @Route("/produto/novo", name="produto_novo", methods={"GET", "POST"})
     */
    public function novo(Request $request, ProdutoRepository $produtoRepository, SessionInterface $session): Response
    {
        $produto = null;
        $errors = [];

        if ($request->query->get("idProduto")) {
            $produto = $produtoRepository->buscaProdutoPorId($request->query->get("idProduto"));
        }

        if ($request->isMethod('POST')) {
            $data = [
                'nameProduto' => $request->request->get('nameProduto'),
                'preco' => $request->request->get('preco'),
                'estoque' => $request->request->get('estoque'),
                'idProduto' => $request->request->get('idProduto'),
            ];

            if (empty($data['nameProduto'])) {
                $errors['nameProduto'] = 'O nome do produto é obrigatório.';
            }
            if (empty($data['estoque'])) {
                $errors['estoque'] = 'O estoque é obrigatório.';
            }
            if (!empty($data['preco']) && !is_numeric($data['preco'])) {
                $errors['preco'] = 'O preço deve ser um número válido.';
            }

            if (empty($errors)) {
                    $produtoRepository->inserirProduto($data);
                return $this->redirectToRoute('produto_novo');
            }
        }
        if (!$session->has('user_id')) {
            $this->addFlash('error', 'Você precisa estar logado');
            return $this->redirectToRoute('app_login');
        }
        $produtos = $produtoRepository->listaProdutos();
        
        return $this->render('produto/index.html.twig', [
            'produto' => $produto,
            'produtos' => $produtos,
            'errors' => $errors,
            'user_name' => $session->get('user_name'),
            'user_email' => $session->get('user_email'),
        ]);
    }

    /**
     * @Route("/produto/editar/{idProduto}", name="produto_edit", methods={"GET", "POST"})
     */
    public function editProduto(Request $request, $idProduto): Response
    {
        if($request->isMethod('POST')) 
            {
                $data = [
                'nameProduto' => $request->request->get('nameProduto'),
                'preco' => $request->request->get('preco'),
                'estoque' => $request->request->get('estoque'),
                'idProduto' => $idProduto,
                ];

             $this->produtoRepository->atualizarProduto($data);

             return $this->redirectToRoute('produto_novo');
            }
            $produto = $this->produtoRepository->buscaProdutoPorId($idProduto);

        return $this->render('produto/edit.html.twig', [
            'produto' => $produto,
        ]);
    }

    /**
     * @Route("/produto/delete/{idProduto}", name="produto_delete", methods={"GET"})
     */
    public function deleteProduto(int $idProduto, ProdutoRepository $produtoRepository): Response
    {
        $produtoRepository->removerProduto($idProduto);
        return $this->redirectToRoute('produto_novo');
    }
}