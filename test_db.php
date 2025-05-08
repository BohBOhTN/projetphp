<?php

require_once 'db.php';
require_once 'Models/Utilisateur.php';
require_once 'Models/Equipement.php';
require_once 'Models/DemandeReparation.php';
require_once 'Models/DemandeTechnicien.php';

// Initialize database connection
$db = new Database();
$conn = $db->connect();

if ($conn) {
    echo "Database connection successful!\n";

    // Test Utilisateur model
    $utilisateur = new Utilisateur($conn);
    $users = $utilisateur->getAll();
    echo "\nUsers:\n";
    while ($row = $users->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }

    // Test Equipement model
    $equipement = new Equipement($conn);
    $equipments = $equipement->getAll();
    echo "\nEquipments:\n";
    while ($row = $equipments->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }

    // Test DemandeReparation model
    $demandeReparation = new DemandeReparation($conn);
    $demandes = $demandeReparation->getAll();
    echo "\nRepair Requests:\n";
    while ($row = $demandes->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }

    // Test DemandeTechnicien model
    $demandeTechnicien = new DemandeTechnicien($conn);
    $assignments = $demandeTechnicien->getAll();
    echo "\nTechnician Assignments:\n";
    while ($row = $assignments->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
} else {
    echo "Database connection failed.";
}