<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Repair Requests</title>
</head>
<body>
    <h1>Manage Repair Requests</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Equipment ID</th>
                <th>Description</th>
                <th>Status</th>
                <th>Creation Date</th>
                <th>Last Update</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($demandes as $demande): ?>
                <tr>
                    <td><?= htmlspecialchars($demande['id_demande']) ?></td>
                    <td><?= htmlspecialchars($demande['id_utilisateur']) ?></td>
                    <td><?= htmlspecialchars($demande['id_equipement']) ?></td>
                    <td><?= htmlspecialchars($demande['description']) ?></td>
                    <td><?= htmlspecialchars($demande['statut']) ?></td>
                    <td><?= htmlspecialchars($demande['date_creation']) ?></td>
                    <td><?= htmlspecialchars($demande['date_mise_a_jour']) ?></td>
                    <td>
                        <a href="/app/demande/edit?id=<?= $demande['id_demande'] ?>">Edit</a>
                        <a href="/app/demande/delete?id=<?= $demande['id_demande'] ?>" onclick="return confirm('Are you sure you want to delete this request?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>