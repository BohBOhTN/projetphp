<?php
// Fetch equipment owned by the user
require_once __DIR__ . '/../../Models/Equipement.php';
$db = new Database();
$conn = $db->connect();
$equipement = new Equipement($conn);
$equipments = $equipement->getByOwner($_SESSION['id_utilisateur'])->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Repair Request</title>
</head>
<body>
    <h1>Create Repair Request</h1>
    <form method="POST" action="/app/demande/create">
        <label for="id_equipement">Select Equipment:</label>
        <select name="id_equipement" id="id_equipement" required>
            <?php foreach ($equipments as $equipment): ?>
                <option value="<?= htmlspecialchars($equipment['id_equipement']) ?>">
                    <?= htmlspecialchars($equipment['reference']) ?> - <?= htmlspecialchars($equipment['type']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea>

        <button type="submit">Submit</button>
    </form>
</body>
</html>