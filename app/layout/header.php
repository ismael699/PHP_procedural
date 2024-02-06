<header class="navbar">
    <p class="navbar-brand">
        <a href="<?= $rootUrl; ?>">My first app PHP</a>
    </p>
    <ul class="navbar-links">
        <li class="navbar-link">Accueil</li>
        <li class="navbar-link">Articles</li>
    </ul>
    <ul class="navbar-links navbar-btn">
        <li class="navbar-link">
            <?php if (!empty($_SESSION['LOGGED_USER'])) : ?>
                <?php if (in_array('ROLE_ADMIN', $_SESSION['LOGGED_USER']['roles'])) : ?>
                    <div class="dropdown">
                        <a class="btn btn-secondary">Admin</a>
                            <div class="dropdown-content">
                                <a href="/admin/users">Users</a>
                                <a href="/admin/articles">Articles</a>
                            </div>
                    </div>
                <?php endif; ?>
                <a href="/logout.php" class="btn btn-danger">Se deconnecter</a>
            <?php else : ?>
                <a href="/register.php" class="btn btn-light">S'inscrire</a>
                <a href="/login.php" class="btn btn-secondary">Se connecter</a>
            <?php endif; ?>
        </li>
    </ul>
</header>