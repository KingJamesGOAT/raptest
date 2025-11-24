<?php
namespace Users;
use Database;

//Ce bout de code on l'a vue en classe dans le cours "2.1 - 02.01-bases-de-donnees-et-pdo-avance" 

require_once __DIR__ . '/../../outils/autoloader.php';


class UserManager implements UserInterface
{

    private $database;

    public function __construct()
    {
        $this->database = new Database();
    }

    public function getUsers(): array
    {
        // Définition de la requête SQL pour récupérer tous les utilisateurs
        $sql = "SELECT * FROM utilisateurs_wave";

        // Préparation de la requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);

        // Exécution de la requête SQL
        $stmt->execute();

        // Récupération de tous les utilisateurs
        $users = $stmt->fetchAll();

        // Retour de tous les utilisateurs
        return $users;
    }

    public function addUser(User $user): int
    {
        // Définition de la requête SQL pour ajouter un utilisateur
        $sql = "INSERT INTO utilisateurs_wave (
            email,
            nom_utilisateur,
            age,
            mot_de_passe
        ) VALUES (
            :email,
            :nom_utilisateur,
            :age,
            :mot_de_passe
        )";

        // Préparation de la requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);

        // Lien avec les paramètres
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':nomUtilisateur', $user->getNom_utilisateur());
        $stmt->bindValue(':age', $user->getAge());
        $stmt->bindValue(':motDePasse', $user->getMot_de_passe());

        // Exécution de la requête SQL pour ajouter un utilisateur
        $stmt->execute();

        // Récupération de l'identifiant de l'utilisateur ajouté
        $userId = $this->database->getPdo()->lastInsertId();

        // Retour de l'identifiant de l'utilisateur ajouté.
        return $userId;
    }

    public function removeUser(int $id): bool
    {
        // Définition de la requête SQL pour supprimer un utilisateur
        $sql = "DELETE FROM utilisateurs_wave WHERE id = :id";

        // Préparation de la requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);

        // Lien avec le paramètre
        $stmt->bindValue(':id', $id);

        // Exécution de la requête SQL pour supprimer un utilisateur
        return $stmt->execute();
    }
    
    public function updatePassword(int $id, string $nouveauMotDePasse): bool {
    $sql = "UPDATE utilisateurs_wave SET mot_de_passe = :mot_de_passe WHERE id = :id";

    $stmt = $this->database->getPdo()->prepare($sql);
    $stmt->bindValue(':mot_de_passe', $nouveauMotDePasse); 
    $stmt->bindValue(':id', $id);

    return $stmt->execute();
}

}
