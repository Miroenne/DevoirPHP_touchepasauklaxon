<?php

/**
 * @var list<\App\Models\Agency>    $agencies
 * @var \App\Models\User|null       $currentUser
 */

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/app.css">
    <title>Touche pas au klaxon - Agences</title>
</head>

<body>
    <header>
        <?php require __DIR__ . '/partials/navbar.php' ?>
    </header>

    <table>
        <caption>

            <h2>Liste des agences</h2>

        </caption>
        <thead>
            <tr>
                <th>Agence</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($agencies as $agency):
            ?>
                <tr>
                    <td><?= htmlspecialchars($agency->getName()) ?></td>
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