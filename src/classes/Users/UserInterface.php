<?php
namespace Users;

//Ce bout de code on l'a vue en classe dans le cours "2.1 - 02.01-bases-de-donnees-et-pdo-avance"

interface UserInterface {
    public function getUsers(): array;
    public function addUser(User $user): int;
    public function removeUser(int $id): bool;
}