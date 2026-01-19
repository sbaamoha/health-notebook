<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /user/profile.php');
    exit();
}

$userId = (int) $_SESSION['user_id'];
$blood = sanitize($_POST['blood_type'] ?? '');
$chronic = sanitize($_POST['chronic_diseases'] ?? '');
$allergies = sanitize($_POST['allergies'] ?? '');
$medications = sanitize($_POST['medications'] ?? '');
$contact = sanitize($_POST['emergency_contact'] ?? '');

$existing = fetch_health_data($pdo, $userId);
if ($existing) {
    $stmt = $pdo->prepare('UPDATE health_data SET blood_type=?, chronic_diseases=?, allergies=?, medications=?, emergency_contact=? WHERE user_id=?');
    $stmt->execute([$blood, $chronic, $allergies, $medications, $contact, $userId]);
} else {
    $stmt = $pdo->prepare('INSERT INTO health_data (user_id, blood_type, chronic_diseases, allergies, medications, emergency_contact) VALUES (?,?,?,?,?,?)');
    $stmt->execute([$userId, $blood, $chronic, $allergies, $medications, $contact]);
}

set_flash('success', 'Health information updated.');
header('Location: /user/profile.php');
exit();
