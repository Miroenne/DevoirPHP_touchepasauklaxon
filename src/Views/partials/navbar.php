<?php

/**
 * @var list<\App\DTO\TripDetails>  $trips
 * @var \App\Models\User|null       $currentUser
 */

?>

<nav>
    <div>
        <h1>Touche pas au klaxon</h1>
    </div>
    <div>
        <?php
        if ($currentUser === null): ?>
            <a href="/test">Connexion</a>
        <?php elseif (isset($currentUser) && $currentUser->getIsAdmin() === true): ?>



        <?php else: ?>
            <button>Déconnexion</button>
        <?php endif;






        ?>
    </div>
</nav>