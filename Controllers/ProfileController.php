<?php

require_once __DIR__ . '/../db.php';

class ProfileController {
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

        // Handle POST request to update profile
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['email'])) {
                echo 'All fields are required.';
                return;
            }

            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];

            // Update user profile
            require_once __DIR__ . '/../Models/Utilisateur.php';
            $db = new Database();
            $conn = $db->connect();
            $utilisateur = new Utilisateur($conn);
            $utilisateur->updateProfile($_SESSION['id_utilisateur'], $nom, $prenom, $email);

            echo 'Profile updated successfully.';
            return;
        }

        // Fetch user data for editing
        require_once __DIR__ . '/../Models/Utilisateur.php';
        $db = new Database();
        $conn = $db->connect();
        $utilisateur = new Utilisateur($conn);
        $user = $utilisateur->getById($_SESSION['id_utilisateur']);

        // Pass data to the view
        include __DIR__ . '/../Views/Profile/edit.php';
    }

    public function view() {
        include __DIR__ . '/../Views/Profile/view.php';
    }

    public function show() {
        // Start session only if not already active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Ensure the user is authenticated
        if (!isset($_SESSION['id_utilisateur'])) {
            header('Location: /app/auth/login');
            exit;
        }

        // Fetch user data
        require_once __DIR__ . '/../Models/Utilisateur.php';
        $db = new Database();
        $conn = $db->connect();
        $utilisateur = new Utilisateur($conn);
        $user = $utilisateur->getById($_SESSION['id_utilisateur']);

        // Pass data to the view
        include __DIR__ . '/../Views/Profile/show.php';
    }
}