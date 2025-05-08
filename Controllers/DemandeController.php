<?php

require_once __DIR__ . '/../db.php';

class DemandeController {
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

        // Fetch all repair requests from the database
        require_once __DIR__ . '/../Models/DemandeReparation.php';
        $db = new Database();
        $conn = $db->connect();
        $demandeReparation = new DemandeReparation($conn);
        $demandes = $demandeReparation->getAll()->fetchAll(PDO::FETCH_ASSOC);

        // Filter repair requests based on user role
        if (!in_array('administrateur', $_SESSION['roles'])) {
            $demandes = array_filter($demandes, function ($demande) {
                return $demande['id_utilisateur'] == $_SESSION['id_utilisateur'];
            });
        }

        // Pass data to the view
        include __DIR__ . '/../Views/Demande/index.php';
    }

    public function create() {
        // Start session only if not already active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Ensure the user is authenticated
        if (!isset($_SESSION['id_utilisateur'])) {
            header('Location: /app/auth/login');
            exit;
        }

        // Handle POST request to create a repair request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['id_equipement']) || empty($_POST['description'])) {
                echo 'All fields are required.';
                return;
            }

            $idEquipement = $_POST['id_equipement'];
            $description = $_POST['description'];

            // Add repair request to the database
            require_once __DIR__ . '/../Models/DemandeReparation.php';
            $db = new Database();
            $conn = $db->connect();
            $demandeReparation = new DemandeReparation($conn);
            $demandeReparation->create($_SESSION['id_utilisateur'], $idEquipement, $description);

            echo 'Repair request created successfully.';
            return;
        }

        // Fetch equipment owned by the user for the form
        require_once __DIR__ . '/../Models/Equipement.php';
        $db = new Database();
        $conn = $db->connect();
        $equipement = new Equipement($conn);
        $equipments = $equipement->getByOwner($_SESSION['id_utilisateur'])->fetchAll(PDO::FETCH_ASSOC);

        // Pass data to the view
        include __DIR__ . '/../Views/Demande/create.php';
    }

    public function manage() {
        // Start session only if not already active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Ensure the user is authenticated and has admin privileges
        if (!isset($_SESSION['id_utilisateur']) || !in_array('administrateur', $_SESSION['roles'])) {
            header('Location: /app/auth/login');
            exit;
        }

        // Fetch all repair requests for management
        require_once __DIR__ . '/../Models/DemandeReparation.php';
        $db = new Database();
        $conn = $db->connect();
        $demandeReparation = new DemandeReparation($conn);
        $demandes = $demandeReparation->getAll()->fetchAll(PDO::FETCH_ASSOC);

        // Pass data to the view
        include __DIR__ . '/../Views/Demande/manage.php';
    }

    public function list() {
        include __DIR__ . '/../Views/Demande/list.php';
    }

    public function view() {
        include __DIR__ . '/../Views/Demande/view.php';
    }
}