<?php

require_once __DIR__ . '/../db.php';

class TechController {
    public function assign() {
        // Start session only if not already active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Ensure the user is authenticated and has admin privileges
        if (!isset($_SESSION['id_utilisateur']) || $_SESSION['role'] !== 'administrateur') {
            header('Location: /app/auth/login');
            exit;
        }

        // Handle POST request to assign a technician
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['id_demande']) || empty($_POST['id_utilisateur'])) {
                echo 'Request ID and technician ID are required.';
                return;
            }

            $idDemande = $_POST['id_demande'];
            $idUtilisateur = $_POST['id_utilisateur'];

            // Assign technician to the repair request
            require_once __DIR__ . '/../Models/DemandeTechnicien.php';
            $db = new Database();
            $conn = $db->connect();
            $demandeTechnicien = new DemandeTechnicien($conn);
            $demandeTechnicien->assignTechnician($idDemande, $idUtilisateur);

            echo 'Technician assigned successfully.';
        }
    }

    public function list() {
        include __DIR__ . '/../Views/Tech/list.php';
    }

    public function details() {
        include __DIR__ . '/../Views/Tech/details.php';
    }

    public function viewAssignedRequests() {
        // Start session only if not already active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Ensure the user is authenticated and has technician privileges
        if (!isset($_SESSION['id_utilisateur']) || !in_array('technicien', $_SESSION['roles'])) {
            header('Location: /app/auth/login');
            exit;
        }

        // Fetch assigned repair requests for the technician
        require_once __DIR__ . '/../Models/DemandeTechnicien.php';
        $db = new Database();
        $conn = $db->connect();
        $demandeTechnicien = new DemandeTechnicien($conn);
        $assignedRequests = $demandeTechnicien->getAssignedRequests($_SESSION['id_utilisateur']);

        // Pass data to the view
        include __DIR__ . '/../Views/Tech/list.php';
    }

    public function listPendingRequests() {
        // Start session only if not already active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Ensure the user is authenticated and has technician privileges
        if (!isset($_SESSION['id_utilisateur']) || !in_array('technicien', $_SESSION['roles'])) {
            header('Location: /app/auth/login');
            exit;
        }

        // Fetch pending repair requests assigned to the technician
        require_once __DIR__ . '/../Models/DemandeTechnicien.php';
        $db = new Database();
        $conn = $db->connect();
        $demandeTechnicien = new DemandeTechnicien($conn);
        $pendingRequests = $demandeTechnicien->getPendingRequests($_SESSION['id_utilisateur']);

        // Pass data to the view
        include __DIR__ . '/../Views/Tech/list_pending.php';
    }

    public function startIntervention() {
        // Start session only if not already active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Ensure the user is authenticated and has technician privileges
        if (!isset($_SESSION['id_utilisateur']) || !in_array('technicien', $_SESSION['roles'])) {
            header('Location: /app/auth/login');
            exit;
        }

        // Handle POST request to start an intervention
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['id_demande'])) {
                echo 'Request ID is required.';
                return;
            }

            $idDemande = $_POST['id_demande'];

            // Start intervention in the database
            require_once __DIR__ . '/../Models/DemandeTechnicien.php';
            $db = new Database();
            $conn = $db->connect();
            $demandeTechnicien = new DemandeTechnicien($conn);
            $demandeTechnicien->startIntervention($idDemande, $_SESSION['id_utilisateur']);

            echo 'Intervention started successfully.';
        }
    }

    public function recordAction() {
        // Start session only if not already active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Ensure the user is authenticated and has technician privileges
        if (!isset($_SESSION['id_utilisateur']) || !in_array('technicien', $_SESSION['roles'])) {
            header('Location: /app/auth/login');
            exit;
        }

        // Handle POST request to record an action
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['id_demande']) || empty($_POST['action_technicien'])) {
                echo 'Request ID and action description are required.';
                return;
            }

            $idDemande = $_POST['id_demande'];
            $actionTechnicien = $_POST['action_technicien'];

            // Record action in the database
            require_once __DIR__ . '/../Models/DemandeTechnicien.php';
            $db = new Database();
            $conn = $db->connect();
            $demandeTechnicien = new DemandeTechnicien($conn);
            $demandeTechnicien->recordAction($idDemande, $_SESSION['id_utilisateur'], $actionTechnicien);

            echo 'Action recorded successfully.';
        }
    }

    public function recordRepairAction() {
        // Start session only if not already active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Ensure the user is authenticated and has technician privileges
        if (!isset($_SESSION['id_utilisateur']) || !in_array('technicien', $_SESSION['roles'])) {
            header('Location: /app/auth/login');
            exit;
        }

        // Handle POST request to record a repair action
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['id_demande']) || empty($_POST['action_technicien'])) {
                echo 'Request ID and action description are required.';
                return;
            }

            $idDemande = $_POST['id_demande'];
            $actionTechnicien = $_POST['action_technicien'];

            // Record repair action in the database
            require_once __DIR__ . '/../Models/DemandeTechnicien.php';
            $db = new Database();
            $conn = $db->connect();
            $demandeTechnicien = new DemandeTechnicien($conn);
            $demandeTechnicien->recordRepairAction($idDemande, $_SESSION['id_utilisateur'], $actionTechnicien);

            echo 'Repair action recorded successfully.';
        }
    }

    public function manageInterventions() {
        // Start session only if not already active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Ensure the user is authenticated and has technician privileges
        if (!isset($_SESSION['id_utilisateur']) || !in_array('technicien', $_SESSION['roles'])) {
            header('Location: /app/auth/login');
            exit;
        }

        // Fetch interventions for the technician
        require_once __DIR__ . '/../Models/DemandeTechnicien.php';
        $db = new Database();
        $conn = $db->connect();
        $demandeTechnicien = new DemandeTechnicien($conn);
        $interventions = $demandeTechnicien->getInterventions($_SESSION['id_utilisateur']);

        // Pass data to the view
        include __DIR__ . '/../Views/Tech/manage_interventions.php';
    }
}