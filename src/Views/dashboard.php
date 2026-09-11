<?php

/**
 * @var list<\App\DTO\TripDetails>  $trips
 * @var \App\Models\User|null       $currentUser
 */

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style/global.css">
    <title>Touche pas au klaxon - Dashboard</title>
</head>

<body>
    <header>
        <?php require __DIR__ . '/partials/navbar.php' ?>
    </header>

    <table>
        <caption>
            <h2>Trajets proposés</h2>
        </caption>
        <thead>
            <tr>
                <th>Départ</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Destination</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Places</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($trips as $trip):
            ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

            <?php endforeach; ?>
        </tbody>

    </table>
</body>

</html>