<?php

namespace App\Repository;

use App\Entity\Produto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProdutoRepository extends ServiceEntityRepository
{
    private $conn;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produto::class);
        $this->conn = $this->getEntityManager()->getConnection();
    }

    public function listaProdutos()
    {
        $sql = "SELECT idProduto, nameProduto, preco, estoque FROM produto";

        $query = $this->conn->query($sql);
        return $query->fetchAllAssociative();
    }

    public function buscaProdutoPorId($idProduto)
    {
        $sql = "SELECT idProduto, nameProduto, preco, estoque FROM produto WHERE idProduto = $idProduto" ;

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAssociative();
    }

    public function inserirProduto($data)
    {
        $sql = "INSERT INTO produto (nameProduto, preco, estoque) VALUES (:nameProduto, :preco, :estoque)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':nameProduto', $data['nameProduto']);
        $stmt->bindValue(':preco', $data['preco']);
        $stmt->bindValue(':estoque', $data['estoque']);

        return $stmt->execute();
    }

    public function atualizarProduto($data)
    {
        $sql = "UPDATE produto SET nameProduto = :nameProduto, preco = :preco, estoque = :estoque WHERE idProduto = :idProduto";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':nameProduto', $data['nameProduto']);
        $stmt->bindValue(':preco', $data['preco']);
        $stmt->bindValue(':estoque', $data['estoque']);
        $stmt->bindValue(':idProduto', $data['idProduto']);

        return $stmt->execute();
    }

    public function removerProduto(int $idProduto)
    {
        $sql = 'DELETE FROM produto WHERE idProduto = :idProduto';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':idProduto', $idProduto);
        $stmt->execute();
    }
}
