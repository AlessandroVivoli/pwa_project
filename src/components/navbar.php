<?php
$request = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;
?>

<nav class="navbar py-4 navbar-expand-lg bg-primary" data-bs-theme="light">
    <div class="container-lg container-fluid">
        <a href="/" class="navbar-brand position-absolute top-0"><img src="/assets/images/logo-sopitas-3.webp" alt="Sopitas" height="80px"></a>
        <div class="d-flex d-lg-none flex-row w-100 justify-content-end">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="/" class="nav-link <?php if ($request == '/' || $request == '') echo "active" ?> text-uppercase fw-bold">Home</a>
                </li>
                <li class="nav-item">
                    <a href="/music" class="nav-link <?php if ($request == '/music') echo "active" ?> text-uppercase fw-bold">Music</a>
                </li>
                <li class="nav-item">
                    <a href="/sport" class="nav-link <?php if ($request == '/sport') echo "active" ?> text-uppercase fw-bold">Sport</a>
                </li>
                <li class="nav-item">
                    <a href="/administration" class="nav-link <?php if ($request == '/administration') echo "active" ?> text-uppercase fw-bold">Administration</a>
                </li>
                <?php
                if (isset($_SESSION['user'])) {
                ?>
                    <li class="nav-item">
                        <a href="/logout" class="nav-link text-uppercase fw-bold">Logout</a>
                    </li>
                <?php
                }
                ?>
            </ul>
        </div>
    </div>
</nav>