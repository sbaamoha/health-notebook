<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

$vaccines = fetch_vaccinations($pdo, $_SESSION['user_id']);
include_once __DIR__ . '/../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Vaccinations</h4>
    <a href="/vaccines/add.php" class="btn btn-primary">Add Vaccine</a>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Date Taken</th>
                        <th>Next Dose</th>
                        <th>Notes</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($vaccines): ?>
                    <?php foreach ($vaccines as $v): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($v['vaccine_name']); ?></td>
                            <td><?php echo htmlspecialchars($v['date_taken']); ?></td>
                            <td><?php echo htmlspecialchars($v['next_dose_date']); ?></td>
                            <td><?php echo htmlspecialchars($v['notes']); ?></td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-secondary" href="/vaccines/edit.php?id=<?php echo $v['id']; ?>">Edit</a>
                                <form action="/vaccines/delete.php" method="post" class="d-inline" onsubmit="return confirm('Delete this record?');">
                                    <input type="hidden" name="id" value="<?php echo $v['id']; ?>">
                                    <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center p-3">No vaccinations recorded.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include_once __DIR__ . '/../includes/footer.php'; ?>
