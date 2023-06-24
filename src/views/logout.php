<?php
if (isset($_SESSION['user'])) {
    session_unset();
    setcookie('user', "", time() - 3600);
    header("Location: /");
} else {
?>
    <div class="container-fluid container-lg my-auto text-center">
        <h1>You're not logged in!</h1>
    </div>
<?php
}
?>