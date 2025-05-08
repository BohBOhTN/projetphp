<?php

class UserController {
    public function add() {
        // Start session only if not already active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Ensure the user is authenticated and has admin privileges
        if (!isset($_SESSION['id_utilisateur']) || !in_array('administrateur', $_SESSION['roles'])) {
            header('Location: /app/auth/login');
            exit;
        }

        // Handle POST request to add a user
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['email']) || empty($_POST['mot_de_passe'])) {
                echo 'All fields are required.';
                return;
            }

            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $motDePasse = $_POST['mot_de_passe'];

            // Add user to the database
            require_once __DIR__ . '/../Models/Utilisateur.php';
            $db = new Database();
            $conn = $db->connect();
            $utilisateur = new Utilisateur($conn);
            $utilisateur->add($nom, $prenom, $email, $motDePasse);

            echo 'User added successfully.';
            return;
        }

        // Show the add user form
        include __DIR__ . '/../Views/User/add.php';
    }

    public function edit() {
        include __DIR__ . '/../Views/User/edit.php';
    }

    public function list() {
        include __DIR__ . '/../Views/User/list.php';
    }

    public function assignRole() {
        // Start session only if not already active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Ensure the user is authenticated and has admin privileges
        if (!isset($_SESSION['id_utilisateur']) || !in_array('administrateur', $_SESSION['roles'])) {
            header('Location: /app/auth/login');
            exit;
        }

        // Handle POST request to assign a role
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['id_utilisateur']) || empty($_POST['role'])) {
                echo 'User ID and role are required.';
                return;
            }

            $idUtilisateur = $_POST['id_utilisateur'];
            $role = $_POST['role'];

            // Assign role to the user
            require_once __DIR__ . '/../Models/Utilisateur.php';
            $db = new Database();
            $conn = $db->connect();
            $utilisateur = new Utilisateur($conn);
            $utilisateur->assignRole($idUtilisateur, $role);

            echo 'Role assigned successfully.';
        }
    }

    public function deactivate() {
        // Start session only if not already active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Ensure the user is authenticated and has admin privileges
        if (!isset($_SESSION['id_utilisateur']) || !in_array('administrateur', $_SESSION['roles'])) {
            header('Location: /app/auth/login');
            exit;
        }

        // Handle GET request to deactivate a user
        if (!isset($_GET['id'])) {
            echo 'User ID is required.';
            return;
        }

        $idUtilisateur = $_GET['id'];

        // Deactivate user in the database
        require_once __DIR__ . '/../Models/Utilisateur.php';
        $db = new Database();
        $conn = $db->connect();
        $utilisateur = new Utilisateur($conn);
        $utilisateur->deactivate($idUtilisateur);

        echo 'User deactivated successfully.';
    }
}