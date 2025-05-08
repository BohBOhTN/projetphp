<?php

class DemandeReparation {
    private $conn;
    private $table = 'demande_reparation';

    public $id_demande;
    public $id_utilisateur;
    public $id_equipement;
    public $description;
    public $statut;
    public $date_creation;
    public $date_mise_a_jour;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create($idUtilisateur, $idEquipement, $description) {
        $query = "INSERT INTO " . $this->table . " (id_utilisateur, id_equipement, description, statut, date_creation) VALUES (:idUtilisateur, :idEquipement, :description, 'en_attente', NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':idUtilisateur', $idUtilisateur);
        $stmt->bindParam(':idEquipement', $idEquipement);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
    }

    public function updateStatus($idDemande, $statut) {
        $query = "UPDATE " . $this->table . " SET statut = :statut, date_mise_a_jour = NOW() WHERE id_demande = :idDemande";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':statut', $statut);
        $stmt->bindParam(':idDemande', $idDemande);
        $stmt->execute();
    }
}