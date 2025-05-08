<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Technician</title>
</head>
<body>
    <h1>Assign Technician</h1>
    <form action="/app/tech/assign" method="POST">
        <label for="id_demande">Request ID:</label>
        <input type="number" id="id_demande" name="id_demande" required>
        <br>
        <label for="id_utilisateur">Technician ID:</label>
        <input type="number" id="id_utilisateur" name="id_utilisateur" required>
        <br>
        <button type="submit">Assign Technician</button>
    </form>
</body>
</html>