<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';

function sanitize(string $value): string
{
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

function is_logged_in(): bool
{
    return isset($_SESSION['user_id']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: /auth/login.php');
        exit();
    }
}

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function fetch_health_data(PDO $pdo, int $userId): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM health_data WHERE user_id = ? LIMIT 1');
    $stmt->execute([$userId]);
    $data = $stmt->fetch();
    return $data ?: null;
}

function fetch_vaccinations(PDO $pdo, int $userId): array
{
    $stmt = $pdo->prepare('SELECT * FROM vaccinations WHERE user_id = ? ORDER BY date_taken DESC');
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

function fetch_reminders(PDO $pdo, int $userId, bool $onlyPending = false): array
{
    $sql = 'SELECT * FROM reminders WHERE user_id = ?';
    $params = [$userId];
    if ($onlyPending) {
        $sql .= " AND status = 'pending' AND reminder_date >= CURDATE()";
    }
    $sql .= ' ORDER BY reminder_date ASC';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function fetch_reports(PDO $pdo, int $userId): array
{
    $stmt = $pdo->prepare('SELECT * FROM medical_reports WHERE user_id = ? ORDER BY upload_date DESC');
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}
