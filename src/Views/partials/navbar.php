<?php

/**
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
            <a href="/login">Connexion</a>
            <a href="/users">Utilisateurs</a>
            <a href="/agencies">Agences</a>
            <a href="/trips">Trajets</a>
        <?php elseif (isset($currentUser) && $currentUser->getIsAdmin() === true): ?>
            <a href="/users">Utilisateurs</a>
            <a href="/agencies">Agences</a>
            <a href="/trips">Trajets</a>
            <p>Bonjour <?php echo $currentUser->getlastName() . ' ' . $currentUser->getfirstName() ?></p>
            <button>Déconnexion</button>
        <?php else: ?>
            <a href="#">Créer un trajet</a>
            <p>Bonjour <?php echo $currentUser->getlastName() . ' ' . $currentUser->getfirstName() ?></p>
            <button>Déconnexion</button>
        <?php endif;






        ?>
    </div>
</nav>