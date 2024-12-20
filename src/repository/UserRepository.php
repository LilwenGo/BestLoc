<?php
namespace BestLoc\Repository;

use BestLocLib\Database\MongoDBConnexion;
use BestLoc\Entity\User;
use MongoDB\BSON\ObjectId;
class UserRepository {
    public function find(ObjectId $id): ?User {
        return MongoDBConnexion::getInstance()->getConnexion()->selectCollection('bestloc', 'users')->findOne(['_id' => $id]);
    }
    
    public function getByEmail(string $email): ?User {
        return MongoDBConnexion::getInstance()->getConnexion()->selectCollection('bestloc', 'users')->findOne(['email' => $email]);
    }

    public function create(string $email, string $password): array {
        $user = new User($email, $password);
        $result = MongoDBConnexion::getInstance()->getConnexion()->selectCollection('bestloc', 'users')->insertOne($user);
        return [
            'id' => $result->getInsertedId(),
            'rowCount' => $result->getInsertedCount()
        ];
    }
}