<?php
require_once __DIR__ . '/includes/functions.php';
require_login();

$autoload = __DIR__ . '/vendor/autoload.php';
if (!file_exists($autoload)) {
    die('PDF export requires Dompdf. Install with: composer require dompdf/dompdf');
}

require_once $autoload;
use Dompdf\Dompdf;

$userId = $_SESSION['user_id'];
$userStmt = $pdo->prepare('SELECT fullname, email, created_at FROM users WHERE id=?');
$userStmt->execute([$userId]);
$user = $userStmt->fetch();

$health = fetch_health_data($pdo, $userId);
$vaccines = fetch_vaccinations($pdo, $userId);
$reports = fetch_reports($pdo, $userId);

$html = '<h2>My Health Notebook</h2>';
$html .= '<p><strong>Name:</strong> ' . htmlspecialchars($user['fullname']) . '<br>';
$html .= '<strong>Email:</strong> ' . htmlspecialchars($user['email']) . '<br>';
$html .= '<strong>Joined:</strong> ' . htmlspecialchars($user['created_at']) . '</p>';

$html .= '<h3>Health Data</h3>';
if ($health) {
    $html .= '<table width="100%" border="1" cellspacing="0" cellpadding="5">';
    foreach ($health as $key => $value) {
        if ($key === 'id' || $key === 'user_id') { continue; }
        $label = ucwords(str_replace('_', ' ', $key));
        $html .= '<tr><td width="30%"><strong>' . htmlspecialchars($label) . '</strong></td><td>' . htmlspecialchars((string)$value) . '</td></tr>';
    }
    $html .= '</table>';
} else {
    $html .= '<p>No health data recorded.</p>';
}

$html .= '<h3>Vaccinations</h3>';
if ($vaccines) {
    $html .= '<table width="100%" border="1" cellspacing="0" cellpadding="5">';
    $html .= '<tr><th>Name</th><th>Date Taken</th><th>Next Dose</th><th>Notes</th></tr>';
    foreach ($vaccines as $v) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($v['vaccine_name']) . '</td>';
        $html .= '<td>' . htmlspecialchars($v['date_taken']) . '</td>';
        $html .= '<td>' . htmlspecialchars($v['next_dose_date']) . '</td>';
        $html .= '<td>' . htmlspecialchars($v['notes']) . '</td>';
        $html .= '</tr>';
    }
    $html .= '</table>';
} else {
    $html .= '<p>No vaccinations recorded.</p>';
}

$html .= '<h3>Medical Reports</h3>';
if ($reports) {
    $html .= '<ul>';
    foreach ($reports as $r) {
        $html .= '<li>' . htmlspecialchars($r['title']) . ' - Uploaded: ' . htmlspecialchars($r['upload_date']) . '</li>';
    }
    $html .= '</ul>';
} else {
    $html .= '<p>No medical reports.</p>';
}

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('my-health-notebook.pdf', ['Attachment' => true]);
exit();
