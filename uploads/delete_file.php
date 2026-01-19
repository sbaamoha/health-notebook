<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $stmt = $pdo->prepare('SELECT file_path FROM medical_reports WHERE id=? AND user_id=?');
    $stmt->execute([$id, $_SESSION['user_id']]);
    $file = $stmt->fetchColumn();

    if ($file) {
        $delete = $pdo->prepare('DELETE FROM medical_reports WHERE id=? AND user_id=?');
        $delete->execute([$id, $_SESSION['user_id']]);
        $fullPath = __DIR__ . '/../' . $file;
        if (is_file($fullPath)) {
            unlink($fullPath);
        }
        set_flash('success', 'Report deleted.');
    }
}
header('Location: /uploads/index.php');
exit();
