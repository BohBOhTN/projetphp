<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session only if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fetch roles from the session
$roles = isset($_SESSION['roles']) ? $_SESSION['roles'] : [];

// Check for specific roles dynamically
if (in_array('administrateur', $roles)) {
    echo '<p>Welcome, Administrator!</p>';
} elseif (in_array('employe', $roles)) {
    echo '<p>Welcome, Employee!</p>';
} else {
    echo '<p>Welcome, User!</p>';
}

// Debugging: Output roles for verification
error_log('User Roles: ' . print_r($roles, true));

// Debugging: Check if data is passed correctly
if (!isset($users, $equipments, $repairRequests)) {
    echo '<p>Data not passed to the view. Please check the controller.</p>';
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
</head>
<body>
    <h1>Tableau de Bord</h1>

    <!-- Remove Users section for non-admin users -->
    <?php if (in_array('administrateur', $roles)): ?>
    <section>
        <h2>Utilisateurs</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Date de Création</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($users as $user):
                    // Fetch roles for the user
                    $userRoles = isset($user['roles']) ? implode(', ', $user['roles']) : 'No roles';
                ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id_utilisateur']) ?></td>
                        <td><?= htmlspecialchars($user['nom']) ?></td>
                        <td><?= htmlspecialchars($user['prenom']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($userRoles) ?></td>
                        <td><?= htmlspecialchars($user['date_creation']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
    <?php endif; ?>

    <section>
        <h2>Équipements</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Référence</th>
                    <th>Type</th>
                    <th>Statut</th>
                    <th>Date d'Achat</th>
                    <th>Localisation</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($equipments as $equipment): ?>
                    <tr>
                        <td><?= htmlspecialchars($equipment['id_equipement']) ?></td>
                        <td><?= htmlspecialchars($equipment['reference']) ?></td>
                        <td><?= htmlspecialchars($equipment['type']) ?></td>
                        <td><?= htmlspecialchars($equipment['statut']) ?></td>
                        <td><?= htmlspecialchars($equipment['date_achat']) ?></td>
                        <td><?= htmlspecialchars($equipment['localisation']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section>
        <h2>Demandes de Réparation</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Utilisateur</th>
                    <th>ID Équipement</th>
                    <th>Description</th>
                    <th>Statut</th>
                    <th>Date de Création</th>
                    <th>Date de Mise à Jour</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($repairRequests as $request): ?>
                    <tr>
                        <td><?= htmlspecialchars($request['id_demande']) ?></td>
                        <td><?= htmlspecialchars($request['id_utilisateur']) ?></td>
                        <td><?= htmlspecialchars($request['id_equipement']) ?></td>
                        <td><?= htmlspecialchars($request['description']) ?></td>
                        <td><?= htmlspecialchars($request['statut']) ?></td>
                        <td><?= htmlspecialchars($request['date_creation']) ?></td>
                        <td><?= htmlspecialchars($request['date_mise_a_jour']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</body>
</html>