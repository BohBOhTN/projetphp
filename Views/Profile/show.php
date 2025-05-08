<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<body>
    <h1>Profile</h1>
    <p><strong>Name:</strong> <?= htmlspecialchars($user['nom']) ?></p>
    <p><strong>First Name:</strong> <?= htmlspecialchars($user['prenom']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <a href="/app/profile/edit">Edit Profile</a>
</body>
</html>