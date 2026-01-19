<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

$userId = $_SESSION['user_id'];
$errors = [];

if (isset($_GET['complete'])) {
    $id = (int) $_GET['complete'];
    $stmt = $pdo->prepare("UPDATE reminders SET status='completed' WHERE id=? AND user_id=?");
    $stmt->execute([$id, $userId]);
    set_flash('success', 'Reminder marked completed.');
    header('Location: /vaccines/reminders.php');
    exit();
}

if (isset($_GET['pending'])) {
    $id = (int) $_GET['pending'];
    $stmt = $pdo->prepare("UPDATE reminders SET status='pending' WHERE id=? AND user_id=?");
    $stmt->execute([$id, $userId]);
    set_flash('success', 'Reminder marked pending.');
    header('Location: /vaccines/reminders.php');
    exit();
}

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = $pdo->prepare('DELETE FROM reminders WHERE id=? AND user_id=?');
    $stmt->execute([$id, $userId]);
    set_flash('success', 'Reminder deleted.');
    header('Location: /vaccines/reminders.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title'] ?? '');
    $date = $_POST['reminder_date'] ?? '';

    if (!$title || !$date) {
        $errors[] = 'Title and date are required.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare('INSERT INTO reminders (user_id, title, reminder_date) VALUES (?,?,?)');
        $stmt->execute([$userId, $title, $date]);
        set_flash('success', 'Reminder added.');
        header('Location: /vaccines/reminders.php');
        exit();
    }
}

$reminders = fetch_reminders($pdo, $userId);
include_once __DIR__ . '/../includes/header.php';
?>
<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">New Reminder</div>
            <div class="card-body">
                <?php if ($errors): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0"><?php foreach ($errors as $err): ?><li><?php echo $err; ?></li><?php endforeach; ?></ul>
                    </div>
                <?php endif; ?>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reminder Date</label>
                        <input type="date" name="reminder_date" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Your Reminders</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if ($reminders): ?>
                            <?php foreach ($reminders as $r): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($r['title']); ?></td>
                                    <td><?php echo htmlspecialchars($r['reminder_date']); ?></td>
                                    <td><span class="badge bg-<?php echo $r['status'] === 'completed' ? 'success' : 'warning'; ?>"><?php echo $r['status']; ?></span></td>
                                    <td class="text-end">
                                        <?php if ($r['status'] === 'pending'): ?>
                                            <a class="btn btn-sm btn-success" href="?complete=<?php echo $r['id']; ?>">Mark Done</a>
                                        <?php else: ?>
                                            <a class="btn btn-sm btn-secondary" href="?pending=<?php echo $r['id']; ?>">Mark Pending</a>
                                        <?php endif; ?>
                                        <a class="btn btn-sm btn-danger" href="?delete=<?php echo $r['id']; ?>" onclick="return confirm('Delete reminder?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center p-3">No reminders yet.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once __DIR__ . '/../includes/footer.php'; ?>
