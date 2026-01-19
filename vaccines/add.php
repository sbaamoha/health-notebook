<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

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
        $stmt = $pdo->prepare('INSERT INTO vaccinations (user_id, vaccine_name, date_taken, next_dose_date, notes) VALUES (?,?,?,?,?)');
        $stmt->execute([$_SESSION['user_id'], $name, $dateTaken, $nextDose, $notes]);
        set_flash('success', 'Vaccine added.');
        header('Location: /vaccines/index.php');
        exit();
    }
}

include_once __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Add Vaccine</div>
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
                        <input type="text" name="vaccine_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date Taken</label>
                        <input type="date" name="date_taken" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Next Dose Date</label>
                        <input type="date" name="next_dose_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="/vaccines/index.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once __DIR__ . '/../includes/footer.php'; ?>
