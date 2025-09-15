<?php
namespace App\Controller;

use App\Form\ProdutoType;
use App\Repository\ProdutoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class ProdutoController extends AbstractController
{
    /**
     * @Route("/produto/novo", name="produto_novo")
     */
    public function novo(Request $request, ProdutoRepository $produtoRepository): Response
    {
        $produto = null;

        if ($request->query->get("idProduto")) {
            $produto = $produtoRepository->buscaProdutoPorId($request->query->get("idProduto"));
        }

        $form = $this->createForm(ProdutoType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            
            if ($produto) {
                $data['idProduto'] = $produto['idProduto'];
                $produtoRepository->atualizarProduto($data);
            } else {
                $produtoRepository->inserirProduto($data);
            }
            return $this->redirectToRoute('produto_novo');
        }
        $produtos = $produtoRepository->listaProdutos();

        return $this->render('produto/index.html.twig', [
            'form' => $form->createView(),
            'produtos'=> $produtos
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