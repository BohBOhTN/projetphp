<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repair Requests</title>
</head>
<body>
    <h1>Repair Requests</h1>
    <a href="/app/demande/create">Create New Request</a>
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
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>