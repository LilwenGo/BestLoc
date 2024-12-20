<?php
namespace BestLoc\Entity;

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Persistable;

class User implements Persistable {
    private ObjectId $id;
    private string $email;
    private string $password;

    public function __construct(string $email, string $password) {
        $this->id = new ObjectId();
        $this->email = $email;
        $this->password = $password;
    }

    public function bsonSerialize(): array {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'password' => $this->password
        ];
    }

    public function bsonUnserialize($data): void {
        $this->id = $data['id'];
        $this->email = $data['email'];
        $this->password = $data['password'];
    }

    public function getId(): ObjectId {
        return $this->id;
    }

    public function getemail(): string {
        return $this->email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setId(ObjectId $id): void{
        $this->id = $id;
    }

    public function setemail(string $email): void{
        $this->email = $email;
    }

    public function setPassword(string $password): void{
        $this->password = $password;
    }
}