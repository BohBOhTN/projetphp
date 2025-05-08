<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Requests</title>
</head>
<body>
    <h1>Pending Repair Requests</h1>

    <?php if (!empty($pendingRequests)) : ?>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Date Created</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendingRequests as $request) : ?>
                    <tr>
                        <td><?= htmlspecialchars($request['id_demande']) ?></td>
                        <td><?= htmlspecialchars($request['description']) ?></td>
                        <td><?= htmlspecialchars($request['statut']) ?></td>
                        <td><?= htmlspecialchars($request['date_creation']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>No pending requests found.</p>
    <?php endif; ?>
</body>
</html>