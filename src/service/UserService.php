<?php
namespace BestLoc\Service;

use BestLoc\Repository\UserRepository;
use BestLoc\Entity\User;
use MongoDB\BSON\ObjectId;

class UserService {
    private UserRepository $repository;

    public function __construct() {
        $this->repository = new UserRepository();
    }

    public function find(string $id): ?User {
        return $this->repository->find(new ObjectId($id));
    }

    public function getByEmail(string $email): ?User {
        return $this->repository->getByEmail($email);
    }

    public function create(string $email, string $password): ?ObjectId {
        $res = $this->repository->create(password_hash($email, PASSWORD_BCRYPT), $password);
        return $res['rowCount'] > 0 && $res['id'] ? $res['id'] : null;
    }
}