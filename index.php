<?php
require_once __DIR__ . '/includes/functions.php';
if (is_logged_in()) {
    header('Location: /dashboard.php');
    exit();
}
include_once __DIR__ . '/includes/header.php';
?>
<div class="row align-items-center">
    <div class="col-md-6">
        <h1 class="mb-3">Welcome to My Health Notebook</h1>
        <p class="lead">Keep your personal health records organized: vaccinations, reminders, and medical reports in one place.</p>
        <a href="/auth/register.php" class="btn btn-primary me-2">Get Started</a>
        <a href="/auth/login.php" class="btn btn-outline-primary">Login</a>
    </div>
    <div class="col-md-6 text-center">
        <img src="https://cdn.jsdelivr.net/gh/twitter/twemoji@14.0.2/assets/svg/2764.svg" alt="Heart" width="120" class="mb-3">
    </div>
</div>
<?php include_once __DIR__ . '/includes/footer.php'; ?>
