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
    <link rel="stylesheet" href="/css/app.css">
    <title>Touche pas au klaxon - Dashboard</title>
</head>

<body>
    <header>
        <?php require __DIR__ . '/partials/navbar.php' ?>
    </header>

    <table>
        <caption>
            <?php if ($currentUser !== null): ?>
                <h2>Trajets proposés</h2>
            <?php else : ?>
                <h2>Pour obtenir plus d'informations sur un trajet, veuillez vous connecter</h2>
            <?php endif; ?>
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
                    <td><?= htmlspecialchars($trip->departureAgency->getName()) ?></td>
                    <td><?= htmlspecialchars($trip->trip->getDepartureAt()->format('d/m/Y')) ?></td>
                    <td><?= htmlspecialchars($trip->trip->getDepartureAt()->format('H:m')) ?></td>
                    <td><?= htmlspecialchars($trip->arrivalAgency->getName()) ?></td>
                    <td><?= htmlspecialchars($trip->trip->getArrivalAt()->format('d/m/Y')) ?></td>
                    <td><?= htmlspecialchars($trip->trip->getArrivalAt()->format('H:m')) ?></td>
                    <td><?= htmlspecialchars($trip->trip->getAvailablePlaces()) ?></td>
                    <td><?php
                        if ($currentUser !== null): ?>
                            <input type="button" value="voir">
                            <?php if ($currentUser->getId() === $trip->trip->getAuthorId()): ?>
                                <input type="button" value="modifier"><input type="button" value="supprimer">
                    </td>
            <?php endif;
                        endif; ?>
                </tr>

            <?php endforeach; ?>
        </tbody>

    </table>
</body>

</html>