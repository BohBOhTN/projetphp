<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Role</title>
</head>
<body>
    <h1>Assign Role</h1>
    <form action="/app/user/assign-role" method="POST">
        <label for="id_utilisateur">User ID:</label>
        <input type="number" id="id_utilisateur" name="id_utilisateur" required>
        <br>
        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="employe">Employe</option>
            <option value="technicien">Technicien</option>
            <option value="administrateur">Administrateur</option>
        </select>
        <br>
        <button type="submit">Assign Role</button>
    </form>
</body>
</html>