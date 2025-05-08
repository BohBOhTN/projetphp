<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Equipment</title>
</head>
<body>
    <h1>Add Equipment</h1>
    <form action="/app/equipement/add" method="POST">
        <label for="reference">Reference:</label>
        <input type="text" id="reference" name="reference" required>
        <br>
        <label for="type">Type:</label>
        <input type="text" id="type" name="type" required>
        <br>
        <label for="statut">Status:</label>
        <select id="statut" name="statut" required>
            <option value="en_service">En Service</option>
            <option value="hors_service">Hors Service</option>
            <option value="en_reparation">En Réparation</option>
        </select>
        <br>
        <label for="date_achat">Purchase Date:</label>
        <input type="date" id="date_achat" name="date_achat" required>
        <br>
        <label for="localisation">Location:</label>
        <input type="text" id="localisation" name="localisation" required>
        <br>
        <button type="submit">Add Equipment</button>
    </form>
</body>
</html>