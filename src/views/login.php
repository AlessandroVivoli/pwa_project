<?php

/**
 * @var string|null
 */
$redirectURI = (isset($_SESSION['redirectTo'])) ? $_SESSION['redirectTo'] : null;

$registerSuccess = isset($_SESSION['registerSuccess']);
$rememberUser = isset($_POST['remember']);

if (isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    unset($_POST);
    unset($_REQUEST);

    /**
     * @var mysqli|null
     */
    $mysql = null;

    try {
        $mysql = new mysqli($_ENV["HOSTNAME"], $_ENV["USERNAME"], $_ENV["PASSWORD"], $_ENV["DB"], $_ENV["PORT"]);
        $stmt = $mysql->prepare('SELECT  * FROM select_user WHERE username=?');
        $stmt->bind_param('s', $username);
        $stmt->execute();

        $result = $stmt->get_result();

        /**
         * @var User|string|null
         */
        $user = null;

        while ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                $user = new User($row['uuid'], $row['username'], $row['level']);
                break;
            }
        }

        if (!$user) {
            $_SERVER['loginInvalid'] = 'Login failed! You provided invalid credentials!';
        } else {
            $user = json_encode($user);
            print($user);

            $_SESSION['user'] = $user;

            if ($rememberUser) {
                setcookie("user", $user, time() + (86400 * 30), '/');
            }

            header("Location: $redirectURI");
        }

        $mysql->close();
    } catch (mysqli_sql_exception $e) {
        $_SERVER['loginInvalid'] = 'Login failed! Something went wrong.';

        error_log($e->getMessage());

        if ($mysql != null) $mysql->close();
    }
}
?>
<div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
    <form class="needs-validation" id="loginForm" method="post" novalidate>
        <div class="form-outline mb-4">
            <input type="text" name="username" id="username" class="form-control" autocomplete="username" placeholder="Username" required>
            <div class="invalid-feedback">
                Username is required
            </div>
        </div>
        <div class="form-outline mb-4">
            <input type="password" name="password" id="password" class="form-control" autocomplete="current-password" placeholder="Password" required>
            <div class="invalid-feedback">
                Password is required
            </div>
        </div>
        <div class="row mb-4">
            <div class="col d-flex justify-content-center">
                <div class="form-check">
                    <input type="checkbox" value="" id="remember" name="remember" class="form-check-input">
                    <label for="remember" class="text-nowrap form-check-label">Remember me</label>
                </div>
            </div>
            <div class="col">
                <a href="#!" class="link text-decoration-none fw-semibold text-nowrap">Forgot password?</a>
            </div>
        </div>
        <div class="d-grid mb-4">
            <button type="submit" class="btn btn-primary">Sign in</button>
        </div>
        <div class="text-center">
            <p>Not a member? <a href="/register" class="link text-decoration-none fw-semibold text-nowrap">Register</a></p>
        </div>
    </form>
</div>
<div class="toast-container top-0 end-0 p-3">
    <div class="toast align-items-center text-bg-success border-0" id="registerSuccess" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                Registered successfully!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
<div class="toast-container top-0 end-0 p-3">
    <div class="toast align-items-center text-bg-danger border-0" id="loginInvalid" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <?php
                if (isset($_SERVER['loginInvalid'])) {
                    echo $_SERVER['loginInvalid'];
                }
                ?>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
<script>
    const loginForm = $('#loginForm').get(0);
    const registerToast = $('#registerSuccess').get(0);
    const registerToastBs = bootstrap.Toast.getOrCreateInstance(registerToast);

    const loginToast = $('#loginInvalid').get(0);
    const loginToastBs = bootstrap.Toast.getOrCreateInstance(loginToast);

    loginForm.addEventListener('submit', event => {
        if (!loginForm.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }

        loginForm.classList.add('was-validated');
    });



    <?php if ($registerSuccess) { ?>
        registerToastBs.show();
    <?php
        unset($_SESSION['registerSuccess']);
    }
    ?>

    <?php if (isset($_SERVER['loginInvalid'])) { ?>
        loginToastBs.show();
    <?php
    } ?>
</script>