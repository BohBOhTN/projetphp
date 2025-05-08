<?php

require_once __DIR__ . '/../db.php';

class EquipementController {
    public function index() {
        // Start session only if not already active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Ensure the user is authenticated
        if (!isset($_SESSION['id_utilisateur'])) {
            header('Location: /app/auth/login');
            exit;
        }

        // Fetch all equipment from the database
        require_once __DIR__ . '/../Models/Equipement.php';
        $db = new Database();
        $conn = $db->connect();
        $equipement = new Equipement($conn);
        $equipments = $equipement->getAll()->fetchAll(PDO::FETCH_ASSOC);

        // Filter equipment based on user role
        if (!in_array('administrateur', $_SESSION['roles'])) {
            $equipments = array_filter($equipments, function ($equipment) {
                return $equipment['id_proprietaire'] == $_SESSION['id_utilisateur'];
            });
        }

        // Pass data to the view
        include __DIR__ . '/../Views/Equipement/index.php';
    }

    public function add() {
        // Start session only if not already active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Ensure the user is authenticated
        if (!isset($_SESSION['id_utilisateur'])) {
            header('Location: /app/auth/login');
            exit;
        }

        // Handle POST request to add equipment
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['reference']) || empty($_POST['type']) || empty($_POST['statut']) || empty($_POST['date_achat']) || empty($_POST['localisation'])) {
                echo 'All fields are required.';
                return;
            }

            $reference = $_POST['reference'];
            $type = $_POST['type'];
            $statut = $_POST['statut'];
            $dateAchat = $_POST['date_achat'];
            $localisation = $_POST['localisation'];

            // Add equipment to the database
            require_once __DIR__ . '/../Models/Equipement.php';
            $db = new Database();
            $conn = $db->connect();
            $equipement = new Equipement($conn);
            $equipement->add($reference, $type, $statut, $dateAchat, $localisation);

            echo 'Equipment added successfully.';
            return;
        }

        // Show the add equipment form
        include __DIR__ . '/../Views/Equipement/add.php';
    }

    public function edit() {
        // Start session only if not already active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Ensure the user is authenticated
        if (!isset($_SESSION['id_utilisateur'])) {
            header('Location: /app/auth/login');
            exit;
        }

        // Handle POST request to edit equipment
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['id_equipement']) || empty($_POST['reference']) || empty($_POST['type']) || empty($_POST['statut']) || empty($_POST['date_achat']) || empty($_POST['localisation'])) {
                echo 'All fields are required.';
                return;
            }

            $idEquipement = $_POST['id_equipement'];
            $reference = $_POST['reference'];
            $type = $_POST['type'];
            $statut = $_POST['statut'];
            $dateAchat = $_POST['date_achat'];
            $localisation = $_POST['localisation'];

            // Update equipment in the database
            require_once __DIR__ . '/../Models/Equipement.php';
            $db = new Database();
            $conn = $db->connect();
            $equipement = new Equipement($conn);
            $equipement->update($idEquipement, $reference, $type, $statut, $dateAchat, $localisation);

            echo 'Equipment updated successfully.';
            return;
        }

        // Fetch equipment data for editing
        if (!isset($_GET['id'])) {
            echo 'Equipment ID is required.';
            return;
        }

        $idEquipement = $_GET['id'];
        require_once __DIR__ . '/../Models/Equipement.php';
        $db = new Database();
        $conn = $db->connect();
        $equipement = new Equipement($conn);
        $equipment = $equipement->getById($idEquipement);

        // Pass data to the view
        include __DIR__ . '/../Views/Equipement/edit.php';
    }

    public function delete() {
        // Start session only if not already active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Ensure the user is authenticated
        if (!isset($_SESSION['id_utilisateur'])) {
            header('Location: /app/auth/login');
            exit;
        }

        // Handle GET request to delete equipment
        if (!isset($_GET['id'])) {
            echo 'Equipment ID is required.';
            return;
        }

        $idEquipement = $_GET['id'];

        // Delete equipment from the database
        require_once __DIR__ . '/../Models/Equipement.php';
        $db = new Database();
        $conn = $db->connect();
        $equipement = new Equipement($conn);
        $equipement->delete($idEquipement);

        echo 'Equipment deleted successfully.';
    }

    public function getByOwner() {
        // Start session only if not already active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Ensure the user is authenticated
        if (!isset($_SESSION['id_utilisateur'])) {
            header('Location: /app/auth/login');
            exit;
        }

        // Fetch equipment owned by the user
        require_once __DIR__ . '/../Models/Equipement.php';
        $db = new Database();
        $conn = $db->connect();
        $equipement = new Equipement($conn);
        $equipments = $equipement->getByOwner($_SESSION['id_utilisateur'])->fetchAll(PDO::FETCH_ASSOC);

        // Pass data to the view
        include __DIR__ . '/../Views/Equipement/index.php';
    }
}