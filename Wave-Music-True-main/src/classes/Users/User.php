<?php

namespace Users;

use DateTime;

// use Users\User;

//Ce bout de code on l'a vue en classe dans le cours "2.1 - 02.01-bases-de-donnees-et-pdo-avance"

class User
{
    // Propriétés privées pour assurer l'encapsulation
    private string $email;
    private string $nom_utilisateur;
    private int $age;
    private string $mot_de_passe;
    private DateTime $date_creation;


    // Constructeur pour initialiser l'objet
    public function __construct(string $email, string $nom_utilisateur, int $age, string $mot_de_passe, DateTime $date_creation)
    {
        $this->email = $email;
        $this->nom_utilisateur = $nom_utilisateur;
        $this->age = $age;
        $this->mot_de_passe = $mot_de_passe;
        $this->date_creation = $date_creation;
    }

    // Getters pour accéder aux propriétés
    public function getEmail(): string
    {
        return $this->email;
    }

    public function getNom_utilisateur(): string
    {
        return $this->nom_utilisateur;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function getMot_de_passe(): string
    {
        return $this->mot_de_passe;
    }

    public function getDate_creation(): DateTime
    {
        return $this->date_creation;
    }


    // Setters pour modifier les propriétés
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setNom_utilisateur(string $nom_utilisateur): void
    {
        $this->nom_utilisateur = $nom_utilisateur;
    }

    public function setAge(int $age): void
    {
        if ($age >= 0) {
            $this->age = $age;
        }
    }

    public function setMot_de_passe(string $mot_de_passe): void
    {
        $this->mot_de_passe = $mot_de_passe;
    }

    // Méthode pour valider les données de l'utilisateur
    public function validate(): array
    {
        $errors = [];

        if (empty($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Un email valide est requis.";
        }

        if (empty($this->nom_utilisateur) || strlen($this->nom_utilisateur) < 2) {
            $errors[] = "Le nom d'utilisateur doit contenir au moins 2 caractères.";
        }

        if ($this->age < 0) {
            $errors[] = "L'âge doit être un nombre positif.";
        }
        
        if (strlen($this->mot_de_passe) < 8) {
    $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
}


        return $errors;
    }
}
