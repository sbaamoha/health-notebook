<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$stmt = $pdo->prepare('SELECT * FROM vaccinations WHERE id = ? AND user_id = ? LIMIT 1');
$stmt->execute([$id, $_SESSION['user_id']]);
$vaccine = $stmt->fetch();

if (!$vaccine) {
    set_flash('danger', 'Record not found.');
    header('Location: /vaccines/index.php');
    exit();
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['vaccine_name'] ?? '');
    $dateTaken = $_POST['date_taken'] ?? null;
    $nextDose = $_POST['next_dose_date'] ?? null;
    $notes = sanitize($_POST['notes'] ?? '');

    if (!$name || !$dateTaken) {
        $errors[] = 'Vaccine name and date taken are required.';
    }

    if (empty($errors)) {
        $update = $pdo->prepare('UPDATE vaccinations SET vaccine_name=?, date_taken=?, next_dose_date=?, notes=? WHERE id=? AND user_id=?');
        $update->execute([$name, $dateTaken, $nextDose, $notes, $id, $_SESSION['user_id']]);
        set_flash('success', 'Vaccine updated.');
        header('Location: /vaccines/index.php');
        exit();
    }
}

include_once __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Edit Vaccine</div>
            <div class="card-body">
                <?php if ($errors): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $err): ?><li><?php echo $err; ?></li><?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Vaccine Name</label>
                        <input type="text" name="vaccine_name" class="form-control" value="<?php echo htmlspecialchars($vaccine['vaccine_name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date Taken</label>
                        <input type="date" name="date_taken" class="form-control" value="<?php echo htmlspecialchars($vaccine['date_taken']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Next Dose Date</label>
                        <input type="date" name="next_dose_date" class="form-control" value="<?php echo htmlspecialchars($vaccine['next_dose_date']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"><?php echo htmlspecialchars($vaccine['notes']); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="/vaccines/index.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once __DIR__ . '/../includes/footer.php'; ?>
