<?php

/**
 * @var \App\Models\User|null       $currentUser
 * @var list<\App\Models\User>      $users
 */

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Touche pas au klaxon - Utilisateurs</title>
</head>

<body>
    <header>
        <?php require __DIR__ . '/partials/navbar.php' ?>
    </header>

    <table>
        <caption>

            <h2>Ensemble des trajets</h2>

        </caption>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Admin</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($users as $user):
            ?>
                <tr>
                    <td><?= htmlspecialchars($user->getlastName()) ?></td>
                    <td><?= htmlspecialchars($user->getfirstName()) ?></td>
                    <td><?= htmlspecialchars($user->getEmail()) ?></td>
                    <td><?= htmlspecialchars($user->getPhoneNumber()) ?></td>
                    <td><?php if ($user->getIsAdmin() === true): ?>
                            Oui
                        <?php else: ?>
                            Non
                        <?php endif; ?>
                    </td>
                    <td>
                        <input type="button" value="modifier">
                        <input type="button" value="supprimer">
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>

    </table>


</body>

</html>