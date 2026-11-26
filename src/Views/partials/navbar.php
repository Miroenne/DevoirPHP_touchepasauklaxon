<?php

/**
 * @var \App\Models\User|null       $currentUser
 */
?>

<nav>
    <div>
        <h1><a href="/">Touche pas au klaxon</a></h1>
    </div>
    <div>
        <?php
        if ($currentUser === null): ?>
            <a href="/login">Connexion</a>
        <?php elseif (isset($currentUser) && $currentUser->getIsAdmin() === true): ?>
            <a href="/users">Utilisateurs</a>
            <a href="/agencies">Agences</a>
            <a href="/trips">Trajets</a>
            <p>Bonjour <?php echo $currentUser->getfirstName() . ' ' . $currentUser->getlastName() ?></p>
            <form method="POST" action="/logout">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrfToken'] ?? '') ?>">
                <button type="submit">Déconnexion</button>
            </form>
        <?php else: ?>
            <a href="#">Créer un trajet</a>
            <p>Bonjour <?php echo $currentUser->getfirstName() . ' ' . $currentUser->getlastName() ?></p>
            <form method="POST" action="/logout">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrfToken'] ?? '') ?>">
                <button type="submit">Déconnexion</button>
            </form>
        <?php endif;






        ?>
    </div>
</nav>