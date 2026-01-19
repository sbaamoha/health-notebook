<?php
require_once __DIR__ . '/includes/functions.php';
require_login();

$health = fetch_health_data($pdo, $_SESSION['user_id']);
$vaccines = fetch_vaccinations($pdo, $_SESSION['user_id']);
$reminders = fetch_reminders($pdo, $_SESSION['user_id'], true);
$reports = fetch_reports($pdo, $_SESSION['user_id']);
include_once __DIR__ . '/includes/header.php';
?>
<div class="row mb-3">
    <div class="col-md-8"><h4>Hello, <?php echo htmlspecialchars($_SESSION['fullname'] ?? ''); ?></h4></div>
    <div class="col-md-4 text-md-end">
        <a class="btn btn-success" href="/export.php">Export My Health PDF</a>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between">
                <span>Health Profile</span>
                <a href="/user/profile.php" class="small">Edit</a>
            </div>
            <div class="card-body">
                <?php if ($health): ?>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Blood Type:</strong> <?php echo htmlspecialchars($health['blood_type']); ?></li>
                        <li class="list-group-item"><strong>Chronic Diseases:</strong> <?php echo htmlspecialchars($health['chronic_diseases']); ?></li>
                        <li class="list-group-item"><strong>Allergies:</strong> <?php echo htmlspecialchars($health['allergies']); ?></li>
                        <li class="list-group-item"><strong>Medications:</strong> <?php echo htmlspecialchars($health['medications']); ?></li>
                        <li class="list-group-item"><strong>Emergency Contact:</strong> <?php echo htmlspecialchars($health['emergency_contact']); ?></li>
                    </ul>
                <?php else: ?>
                    <p class="mb-0">No health info yet. <a href="/user/profile.php">Add now</a>.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between">
                <span>Upcoming Reminders</span>
                <a href="/vaccines/reminders.php" class="small">Manage</a>
            </div>
            <div class="card-body">
                <?php if ($reminders): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($reminders as $r): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold"><?php echo htmlspecialchars($r['title']); ?></div>
                                    <small class="text-muted">Due: <?php echo htmlspecialchars($r['reminder_date']); ?></small>
                                </div>
                                <span class="badge bg-<?php echo $r['status'] === 'completed' ? 'success' : 'warning'; ?>"><?php echo $r['status']; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="mb-0">No upcoming reminders.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between">
                <span>Recent Vaccinations</span>
                <a href="/vaccines/index.php" class="small">View all</a>
            </div>
            <div class="card-body">
                <?php if ($vaccines): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead><tr><th>Name</th><th>Date</th><th>Next Dose</th></tr></thead>
                            <tbody>
                                <?php foreach (array_slice($vaccines, 0, 5) as $v): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($v['vaccine_name']); ?></td>
                                        <td><?php echo htmlspecialchars($v['date_taken']); ?></td>
                                        <td><?php echo htmlspecialchars($v['next_dose_date']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="mb-0">No vaccination records.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between">
                <span>Medical Reports</span>
                <a href="/uploads/index.php" class="small">Manage</a>
            </div>
            <div class="card-body">
                <?php if ($reports): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach (array_slice($reports, 0, 5) as $r): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold"><?php echo htmlspecialchars($r['title']); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($r['upload_date']); ?></small>
                                </div>
                                <a href="<?php echo '/uploads/files/' . htmlspecialchars(basename($r['file_path'])); ?>" target="_blank" class="btn btn-outline-primary btn-sm">View</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="mb-0">No reports uploaded.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php include_once __DIR__ . '/includes/footer.php'; ?>
