<?php

if (isset($_POST['username'], $_POST['password'], $_POST['confirmPassword'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $level = 0;

    if ($password != $confirmPassword) {
        $_SERVER['invalidRegister'] = 'Registration failed! Your password and confirmed password are not the same!';
    } else {
        $password = password_hash($password, CRYPT_BLOWFISH);

        /**
         * @var mysqli|null
         */
        $mysql = null;

        try {
            $mysql = new mysqli($_ENV["HOSTNAME"], $_ENV["USERNAME"], $_ENV["PASSWORD"], $_ENV["DB"], $_ENV["PORT"]);
            $stmt = $mysql->prepare('INSERT INTO users (username, password, level) VALUES (?, ?, ?)');
            $stmt->bind_param('ssi', $username, $password, $level);
            try {
                $stmt->execute();


                $_SESSION['registerSuccess'] = true;
                header('Location: /login');
            } catch (mysqli_sql_exception $e) {
                switch ($e->getCode()) {
                    case 1062:
                        $_SERVER['invalidRegister'] = "Account with that username already exists!";
                        break;
                    default:
                        throw $e;
                        break;
                }
            }

            $mysql->close();
        } catch (mysqli_sql_exception $e) {
            $e->$_SERVER['invalidRegister'] = 'Registration failed! Something went wrong.';

            error_log($e->getMessage());

            if ($mysql != null) $mysql->close();
        }
    }
}
?>
<div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
    <form class="needs-validation" id="registerForm" method="post" novalidate>
        <div class="form-outline mb-4">
            <input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
            <div class="invalid-feedback">
                Username is required
            </div>
        </div>
        <div class="form-outline mb-4">
            <input type="text" name="password" id="password" class="form-control" placeholder="Password" required>
            <div class="invalid-feedback">
                Password is required
            </div>
        </div>
        <div class="form-outline mb-4">
            <input type="text" name="confirmPassword" id="confirmPassword" class="form-control" placeholder="Confirm Password" required>
            <div class="invalid-feedback">
                You need to confirm your password
            </div>
        </div>
        <div class="d-grid mb-4">
            <button type="submit" class="btn btn-primary">Sign up</button>
        </div>
        <div class="text-center">
            <p>Already a member? <a href="/login" class="link text-decoration-none fw-semibold text-nowrap">Login</a></p>
        </div>
    </form>
</div>
<div class="toast-container top-0 end-0 p-3">
    <div class="toast align-items-center text-bg-danger border-0" id="registerFailed" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <?php
                if (isset($_SERVER['invalidRegister'])) {
                    echo $_SERVER['invalidRegister'];
                }
                ?>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
<script>
    const registerForm = $('#registerForm').get(0);

    const passwordInput = $('#password');
    const confirmPasswordInput = $('#password');

    const toastRegister = $('#registerFailed').get(0);
    const toastRegisterBs = bootstrap.Toast.getOrCreateInstance(toastRegister);

    registerForm.addEventListener('submit', event => {
        if (!registerForm.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }

        registerForm.classList.add('was-validated');
    });

    <?php
    if (isset($_SERVER['invalidRegister'])) {
    ?>
        toastRegisterBs.show();
    <?php
    }
    ?>
</script>