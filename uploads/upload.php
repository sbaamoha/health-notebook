<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title'] ?? '');
    $desc = sanitize($_POST['description'] ?? '');

    if (!$title) {
        $errors[] = 'Title is required.';
    }

    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Valid file is required.';
    } else {
        $file = $_FILES['file'];
        $allowed = ['application/pdf', 'image/jpeg', 'image/png', 'image/gif'];
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        if (!in_array($mime, $allowed, true)) {
            $errors[] = 'Only PDF or image files are allowed.';
        }
        if ($file['size'] > 2 * 1024 * 1024) {
            $errors[] = 'File size must be under 2MB.';
        }
    }

    if (empty($errors)) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $safeName = uniqid('report_', true) . '.' . $ext;
        $destination = __DIR__ . '/files/' . $safeName;
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $relativePath = 'uploads/files/' . $safeName;
            $stmt = $pdo->prepare('INSERT INTO medical_reports (user_id, title, file_path, description) VALUES (?,?,?,?)');
            $stmt->execute([$_SESSION['user_id'], $title, $relativePath, $desc]);
            set_flash('success', 'File uploaded successfully.');
            header('Location: /uploads/index.php');
            exit();
        } else {
            $errors[] = 'Failed to store the file.';
        }
    }
}

include_once __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Upload Medical Report</div>
            <div class="card-body">
                <?php if ($errors): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0"><?php foreach ($errors as $err): ?><li><?php echo $err; ?></li><?php endforeach; ?></ul>
                    </div>
                <?php endif; ?>
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">File (PDF / Image, max 2MB)</label>
                        <input type="file" name="file" class="form-control" accept="application/pdf,image/*" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                    <a href="/uploads/index.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once __DIR__ . '/../includes/footer.php'; ?>
