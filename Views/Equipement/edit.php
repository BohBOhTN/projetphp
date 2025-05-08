<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Equipment</title>
</head>
<body>
    <h1>Edit Equipment</h1>
    <form action="/app/equipement/edit" method="POST">
        <input type="hidden" name="id_equipement" value="<?= htmlspecialchars($equipment['id_equipement']) ?>">
        <label for="reference">Reference:</label>
        <input type="text" id="reference" name="reference" value="<?= htmlspecialchars($equipment['reference']) ?>" required>
        <br>
        <label for="type">Type:</label>
        <input type="text" id="type" name="type" value="<?= htmlspecialchars($equipment['type']) ?>" required>
        <br>
        <label for="statut">Status:</label>
        <input type="text" id="statut" name="statut" value="<?= htmlspecialchars($equipment['statut']) ?>" required>
        <br>
        <label for="date_achat">Purchase Date:</label>
        <input type="date" id="date_achat" name="date_achat" value="<?= htmlspecialchars($equipment['date_achat']) ?>" required>
        <br>
        <label for="localisation">Location:</label>
        <input type="text" id="localisation" name="localisation" value="<?= htmlspecialchars($equipment['localisation']) ?>" required>
        <br>
        <button type="submit">Save Changes</button>
    </form>
</body>
</html>