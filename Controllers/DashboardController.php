<?php

require_once __DIR__ . '/../Models/Utilisateur.php';
require_once __DIR__ . '/../Models/Equipement.php';
require_once __DIR__ . '/../Models/DemandeReparation.php';
require_once __DIR__ . '/../db.php';

class DashboardController {
    public function index() {
        // Initialize database connection
        $db = new Database();
        $conn = $db->connect();

        if (!$conn) {
            echo '<p>Database connection failed. Please check your configuration.</p>';
            exit;
        }

        // Fetch data from models
        $utilisateurModel = new Utilisateur($conn);
        $equipementModel = new Equipement($conn);
        $demandeReparationModel = new DemandeReparation($conn);

        $users = $utilisateurModel->getAll()->fetchAll(PDO::FETCH_ASSOC);
        $equipments = $equipementModel->getAll()->fetchAll(PDO::FETCH_ASSOC);
        $repairRequests = $demandeReparationModel->getAll()->fetchAll(PDO::FETCH_ASSOC);

        // Fetch roles for each user
        foreach ($users as &$user) {
            $user['roles'] = $utilisateurModel->getRolesByUserId($user['id_utilisateur']);
        }

        // Filter data based on the logged-in user
        // Start session only if not already active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $loggedInUserId = $_SESSION['id_utilisateur'];

        // Fetch all roles from the session
        $roles = isset($_SESSION['roles']) ? $_SESSION['roles'] : [];

        // Check if the user has a specific role
        if (in_array('administrateur', $roles)) {
            // Admin-specific logic
        } elseif (in_array('employe', $roles)) {
            // Employee-specific logic
        }

        // Filter repair requests for the logged-in user
        if (!in_array('administrateur', $roles)) {
            $repairRequests = array_filter($repairRequests, function ($request) use ($loggedInUserId) {
                return $request['id_utilisateur'] == $loggedInUserId;
            });
        }

        // Pass filtered data to the view
        include __DIR__ . '/../Views/Dashboard/index.php';
    }

    public function stats() {
        include __DIR__ . '/../Views/Dashboard/stats.php';
    }

    public function settings() {
        include __DIR__ . '/../Views/Dashboard/settings.php';
    }
}