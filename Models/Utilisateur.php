<?php

class Utilisateur {
    private $conn;
    private $table = 'utilisateur';

    public $id_utilisateur;
    public $nom;
    public $prenom;
    public $email;
    public $mot_de_passe;
    public $role;
    public $date_creation;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getByEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_utilisateur = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePassword($id, $newPassword) {
        $query = "UPDATE " . $this->table . " SET mot_de_passe = :newPassword WHERE id_utilisateur = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':newPassword', $newPassword);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function updateProfile($id, $nom, $prenom, $email) {
        $query = "UPDATE " . $this->table . " SET nom = :nom, prenom = :prenom, email = :email WHERE id_utilisateur = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function getRoles($idUtilisateur) {
        $query = "SELECT role FROM utilisateur_role WHERE id_utilisateur = :idUtilisateur";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':idUtilisateur', $idUtilisateur);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function assignRole($idUtilisateur, $role) {
        $query = "INSERT INTO utilisateur_role (id_utilisateur, role) VALUES (:idUtilisateur, :role)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':idUtilisateur', $idUtilisateur);
        $stmt->bindParam(':role', $role);
        $stmt->execute();
    }

    public function getRolesByUserId($idUtilisateur) {
        $query = "SELECT role FROM utilisateur_role WHERE id_utilisateur = :idUtilisateur";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':idUtilisateur', $idUtilisateur);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}