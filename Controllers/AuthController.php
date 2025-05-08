<?php

require_once __DIR__ . '/../db.php';

class AuthController {
    public function showLogin() {
        echo 'Rendering login page...'; // Debugging message to confirm rendering
        include __DIR__ . '/../Views/Auth/login.php';
    }

    public function login() {
        // Check if the request is a POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Start session only if not already active
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Validate input
            if (empty($_POST['email']) || empty($_POST['password'])) {
                echo 'Email and password are required.';
                return;
            }

            $email = $_POST['email'];
            $password = $_POST['password'];

            // Fetch user from database
            require_once __DIR__ . '/../Models/Utilisateur.php';
            $db = new Database();
            $conn = $db->connect();
            $utilisateur = new Utilisateur($conn);
            $user = $utilisateur->getByEmail($email);

            if ($user && $password === $user['mot_de_passe']) { // Direct comparison since passwords are stored in plain text
                // Fetch user roles from the database
                $rolesQuery = "SELECT role FROM utilisateur_role WHERE id_utilisateur = :id_utilisateur";
                $rolesStmt = $conn->prepare($rolesQuery);
                $rolesStmt->bindParam(':id_utilisateur', $user['id_utilisateur']);
                $rolesStmt->execute();
                $roles = $rolesStmt->fetchAll(PDO::FETCH_COLUMN);

                // Check if the user is a technician
                if (in_array('technicien', $roles)) {
                    $_SESSION['roles'][] = 'technicien';
                }

                // Store user info and roles in session
                $_SESSION['id_utilisateur'] = $user['id_utilisateur'];
                $_SESSION['roles'] = $roles;
                $_SESSION['nom'] = $user['nom'];

                // Debugging: Output session variables
                echo '<pre>Session Variables:';
                print_r($_SESSION);
                echo '</pre>';
                // End debugging

                // Redirect based on role
                if ($user['role'] === 'administrateur') {
                    header('Location: /app/dashboard');
                } else {
                    header('Location: /app/dashboard');
                }
                exit;
            } else {
                echo 'Invalid email or password.';
            }
        } else {
            // If not a POST request, show the login form
            $this->showLogin();
        }
    }

    public function logout() {
        // Start session only if not already active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header('Location: /app/auth/login');
        exit;
    }

    public function register() {
        include __DIR__ . '/../Views/Auth/register.php';
    }

    public function changePassword() {
        // Start session only if not already active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Ensure the user is authenticated
        if (!isset($_SESSION['id_utilisateur'])) {
            header('Location: /app/auth/login');
            exit;
        }

        // Handle POST request to change password
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['current_password']) || empty($_POST['new_password']) || empty($_POST['confirm_password'])) {
                echo 'All fields are required.';
                return;
            }

            $currentPassword = $_POST['current_password'];
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];

            // Fetch user from database
            require_once __DIR__ . '/../Models/Utilisateur.php';
            $db = new Database();
            $conn = $db->connect();
            $utilisateur = new Utilisateur($conn);
            $user = $utilisateur->getById($_SESSION['id_utilisateur']);

            // Verify current password
            if ($user['mot_de_passe'] !== $currentPassword) {
                echo 'Current password is incorrect.';
                return;
            }

            // Check if new password matches confirmation
            if ($newPassword !== $confirmPassword) {
                echo 'New password and confirmation do not match.';
                return;
            }

            // Update password in the database
            $utilisateur->updatePassword($_SESSION['id_utilisateur'], $newPassword);
            echo 'Password changed successfully.';
            return;
        }

        // Show the change password form
        include __DIR__ . '/../Views/Auth/change_password.php';
    }

    public function forgotPassword() {
        include __DIR__ . '/../Views/Auth/forgot_password.php';
    }
}