<?php

class DemandeTechnicien {
    private $conn;
    private $table = 'demande_technicien';

    public $id_demande;
    public $id_utilisateur;
    public $date_attribution;
    public $date_debut;
    public $date_fin;
    public $action_technicien;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function assignTechnician($idDemande, $idUtilisateur) {
        $query = "INSERT INTO demande_technicien (id_demande, id_utilisateur) VALUES (:idDemande, :idUtilisateur)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':idDemande', $idDemande);
        $stmt->bindParam(':idUtilisateur', $idUtilisateur);
        $stmt->execute();
    }

    public function recordAction($idDemande, $idUtilisateur, $action) {
        $query = "UPDATE demande_technicien SET action_technicien = :action, date_fin = NOW() WHERE id_demande = :idDemande AND id_utilisateur = :idUtilisateur";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':action', $action);
        $stmt->bindParam(':idDemande', $idDemande);
        $stmt->bindParam(':idUtilisateur', $idUtilisateur);
        $stmt->execute();
    }

    public function getPendingRequests($technicianId) {
        $query = "SELECT dr.* FROM demande_reparation dr
                  JOIN demande_technicien dt ON dr.id_demande = dt.id_demande
                  WHERE dt.id_utilisateur = :technicianId AND dr.statut = 'attribuee'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':technicianId', $technicianId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
}