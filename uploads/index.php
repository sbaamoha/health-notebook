<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

$reports = fetch_reports($pdo, $_SESSION['user_id']);
include_once __DIR__ . '/../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Medical Reports</h4>
    <a href="/uploads/upload.php" class="btn btn-primary">Upload Report</a>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Uploaded</th>
                        <th>Description</th>
                        <th>File</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($reports): ?>
                    <?php foreach ($reports as $r): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($r['title']); ?></td>
                            <td><?php echo htmlspecialchars($r['upload_date']); ?></td>
                            <td><?php echo htmlspecialchars($r['description']); ?></td>
                            <td><a href="<?php echo '/uploads/files/' . htmlspecialchars(basename($r['file_path'])); ?>" target="_blank">View</a></td>
                            <td class="text-end">
                                <form action="/uploads/delete_file.php" method="post" class="d-inline" onsubmit="return confirm('Delete this file?');">
                                    <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                                    <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center p-3">No reports uploaded.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include_once __DIR__ . '/../includes/footer.php'; ?>
