<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipment List</title>
</head>
<body>
    <h1>Equipment List</h1>
    <a href="/app/equipement/add">Add New Equipment</a>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Reference</th>
                <th>Type</th>
                <th>Status</th>
                <th>Purchase Date</th>
                <th>Location</th>
                <th>Actions</th>
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
                    <td>
                        <a href="/app/equipement/edit?id=<?= $equipment['id_equipement'] ?>">Edit</a>
                        <a href="/app/equipement/delete?id=<?= $equipment['id_equipement'] ?>" onclick="return confirm('Are you sure you want to delete this equipment?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>