<?php

namespace App\Repository;

use App\Entity\UserTable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserTable>
 *
 * @method UserTable|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserTable|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserTable[]    findAll()
 * @method UserTable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserTableRepository extends ServiceEntityRepository
{
    private $conn;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserTable::class);
        $this->conn = $this->getEntityManager()->getConnection();
    }

    public function listarUser()
    {
        $sql = "SELECT id, userName, email, telephoneNumber FROM userTable";

        $query = $this->conn->query($sql);
        return $query->fetchAllAssociative();
    }

    public function inserirUser($data)
    {
        $sql = "INSERT INTO userTable (userName, email, telephoneNumber) VALUES (:userName, :email, :telephoneNumber)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':userName', $data['userName']);
        $stmt->bindValue(':email', $data['email']);
        $stmt->bindValue(':telephoneNumber', $data['telephoneNumber']);

        return $stmt->execute();
    }

    public function buscarUserPorId($id)
    {
        $sql = "SELECT id, userName, email, telephoneNumber FROM userTable WHERE id = $id" ;

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAssociative();
    }
    public function editarUser($data)
    {
        $sql = "UPDATE userTable SET userName = :userName, email = :email, telephoneNumber = :telephoneNumber WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':userName', $data['userName']);
        $stmt->bindValue(':email', $data['email']);
        $stmt->bindValue(':telephoneNumber', $data['telephoneNumber']);
        $stmt->bindValue(':id', $data['id']);

        return $stmt->execute();
    }
}