<?php

class Equipement {
    private $conn;
    private $table = 'equipement';

    public $id_equipement;
    public $reference;
    public $type;
    public $statut;
    public $date_achat;
    public $localisation;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function add($reference, $type, $statut, $dateAchat, $localisation) {
        $query = "INSERT INTO " . $this->table . " (reference, type, statut, date_achat, localisation) VALUES (:reference, :type, :statut, :dateAchat, :localisation)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':reference', $reference);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':statut', $statut);
        $stmt->bindParam(':dateAchat', $dateAchat);
        $stmt->bindParam(':localisation', $localisation);
        $stmt->execute();
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_equipement = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $reference, $type, $statut, $dateAchat, $localisation) {
        $query = "UPDATE " . $this->table . " SET reference = :reference, type = :type, statut = :statut, date_achat = :dateAchat, localisation = :localisation WHERE id_equipement = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':reference', $reference);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':statut', $statut);
        $stmt->bindParam(':dateAchat', $dateAchat);
        $stmt->bindParam(':localisation', $localisation);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id_equipement = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function getByOwner($idProprietaire) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_proprietaire = :idProprietaire";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':idProprietaire', $idProprietaire);
        $stmt->execute();
        return $stmt;
    }
}