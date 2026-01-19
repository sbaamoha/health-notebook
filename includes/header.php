<?php
require_once __DIR__ . '/functions.php';
$baseUrl = '';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Health Notebook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo $baseUrl; ?>/dashboard.php">My Health Notebook</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <?php if (is_logged_in()): ?>
            <li class="nav-item"><a class="nav-link" href="<?php echo $baseUrl; ?>/dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo $baseUrl; ?>/user/profile.php">Health Profile</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo $baseUrl; ?>/vaccines/index.php">Vaccinations</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo $baseUrl; ?>/vaccines/reminders.php">Reminders</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo $baseUrl; ?>/uploads/index.php">Reports</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo $baseUrl; ?>/export.php">Export PDF</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo $baseUrl; ?>/auth/logout.php">Logout</a></li>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="<?php echo $baseUrl; ?>/auth/login.php">Login</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo $baseUrl; ?>/auth/register.php">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container py-4">
    <?php if ($flash = get_flash()): ?>
        <div class="alert alert-<?php echo $flash['type']; ?>"><?php echo $flash['message']; ?></div>
    <?php endif; ?>
