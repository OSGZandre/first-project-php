<?php

namespace App\Repository;

use App\Entity\UserTable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

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
    private $passwordHasher;

    public function __construct(ManagerRegistry $registry, PasswordHasherFactoryInterface $passwordHasherFactory)
    {
        parent::__construct($registry, UserTable::class);
        $this->conn = $this->getEntityManager()->getConnection();
        $this->passwordHasher = $passwordHasherFactory->getPasswordHasher('default');
    }

    public function listarUser()
    {
        $sql = "SELECT id, userName, email, telephoneNumber FROM userTable";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAllAssociative();
    }

    public function inserirUser($data)
    {
        $sql = "INSERT INTO userTable (userName, email, telephoneNumber, userPassword) VALUES (:userName, :email, :telephoneNumber, :userPassword)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':userName', $data['userName']);
        $stmt->bindValue(':email', $data['email']);
        $stmt->bindValue(':telephoneNumber', $data['telephoneNumber']);
        $stmt->bindValue(':userPassword', $this->passwordHasher->hash($data['userPassword']));
        return $stmt->executeQuery();
    }

    public function buscarPorEmail(string $email): ?array
    {
        $sql = "SELECT id, userName, email, telephoneNumber, userPassword FROM userTable WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':email', $email);
        $result = $stmt->executeQuery();
        return $result->fetchAssociative() ?: null;
    }

    public function verifyPassword(string $plainPassword, string $hashedPassword): bool
    {
        return $this->passwordHasher->verify($hashedPassword, $plainPassword);
    }

    public function buscarUserPorId($id)
    {
        $sql = "SELECT id, userName, email, telephoneNumber FROM userTable WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $result = $stmt->executeQuery();
        return $result->fetchAssociative();
    }

    public function editarUser($data)
    {
        if (!empty($data['userPassword'])) {
            $sql = "UPDATE userTable SET userName = :userName, email = :email, telephoneNumber = :telephoneNumber, userPassword = :userPassword WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':userPassword', $this->passwordHasher->hash($data['userPassword']));
        } else {
            $sql = "UPDATE userTable SET userName = :userName, email = :email, telephoneNumber = :telephoneNumber WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
        }

        $stmt->bindValue(':userName', $data['userName']);
        $stmt->bindValue(':email', $data['email']);
        $stmt->bindValue(':telephoneNumber', $data['telephoneNumber']);
        $stmt->bindValue(':id', $data['id'], \PDO::PARAM_INT);
        return $stmt->executeQuery();
    }

    public function removerUser(int $id)
    {
        $sql = "DELETE FROM userTable WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        return $stmt->executeQuery();
    }
}