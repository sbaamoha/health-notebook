<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

$health = fetch_health_data($pdo, $_SESSION['user_id']);
include_once __DIR__ . '/../includes/header.php';
?>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Health Profile</div>
            <div class="card-body">
                <form action="/user/update_info.php" method="post">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Blood Type</label>
                            <input type="text" name="blood_type" class="form-control" value="<?php echo $health['blood_type'] ?? ''; ?>" placeholder="e.g. O+">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Emergency Contact</label>
                            <input type="text" name="emergency_contact" class="form-control" value="<?php echo $health['emergency_contact'] ?? ''; ?>" placeholder="Phone number">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Chronic Diseases</label>
                        <textarea name="chronic_diseases" class="form-control" rows="2"><?php echo $health['chronic_diseases'] ?? ''; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Allergies</label>
                        <textarea name="allergies" class="form-control" rows="2"><?php echo $health['allergies'] ?? ''; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Medications</label>
                        <textarea name="medications" class="form-control" rows="2"><?php echo $health['medications'] ?? ''; ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Save</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Tips</div>
            <div class="card-body small text-muted">
                Keep your emergency contact up to date. Use the reminders page to schedule checkups or medication refills.
            </div>
        </div>
    </div>
</div>
<?php include_once __DIR__ . '/../includes/footer.php'; ?>
