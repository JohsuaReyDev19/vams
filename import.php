<?php
// import.php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error']);
    exit;
}

// Basic checks
$allowedExt = ['xlsx'];
$info = pathinfo($_FILES['file']['name']);
$ext = strtolower($info['extension'] ?? '');
if (!in_array($ext, $allowedExt)) {
    echo json_encode(['success' => false, 'message' => 'Only .xlsx files are allowed']);
    exit;
}

require './import_studentList/db.php'; // creates $pdo
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as PhpDate;

$uploaded = $_FILES['file']['tmp_name'];
// Load spreadsheet
try {
    $spreadsheet = IOFactory::load($uploaded);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to read spreadsheet: ' . $e->getMessage()]);
    exit;
}

$sheet = $spreadsheet->getActiveSheet();
$rows = $sheet->toArray(null, true, true, true); // returns rows with keys A,B,C...
if (count($rows) < 2) {
    echo json_encode(['success' => false, 'message' => 'Spreadsheet appears empty or has no data rows.']);
    exit;
}

// Helper: normalize header string to a canonical key
function norm($s) {
    $s = trim(strtolower((string)$s));
    $s = preg_replace('/[^a-z0-9]+/','', $s);
    return $s;
}

// Define mapping of normalized header -> DB column name
$mapCandidates = [
    'code' => 'code',
    'id' => '_id',
    'lastname' => 'last_name',
    'firstname' => 'first_name',
    'middlename' => 'middle_name',
    'sex' => 'sex',
    'course' => 'course',
    'year' => 'year',
    'units' => 'units',
    'section' => 'section',
    'email' => 'email',
    'contactno' => 'contact_no',
    'contactnumber' => 'contact_no',
    'birth' => 'birth',
    'dateofbirth' => 'birth',
];

// Read header row
$headerRow = reset($rows);
$colMap = [];
foreach ($headerRow as $colLetter => $cellValue) {
    $n = norm($cellValue);
    if ($n === '') continue;
    if (isset($mapCandidates[$n])) {
        $colMap[$colLetter] = $mapCandidates[$n];
    } else {
        if (strpos($n, 'last') !== false && strpos($n, 'name') !== false) $colMap[$colLetter] = 'last_name';
        elseif (strpos($n, 'first') !== false && strpos($n, 'name') !== false) $colMap[$colLetter] = 'first_name';
        elseif (strpos($n, 'middle') !== false) $colMap[$colLetter] = 'middle_name';
        elseif (strpos($n, 'contact') !== false || strpos($n, 'phone') !== false) $colMap[$colLetter] = 'contact_no';
        elseif (strpos($n, 'birth') !== false || strpos($n, 'dateofbirth') !== false) $colMap[$colLetter] = 'birth';
        elseif (strpos($n, 'code') !== false) $colMap[$colLetter] = 'code';
        elseif (strpos($n, 'course') !== false) $colMap[$colLetter] = 'course';
        elseif (strpos($n, 'sex') !== false || strpos($n, 'gender') !== false) $colMap[$colLetter] = 'sex';
        elseif (strpos($n, 'year') !== false) $colMap[$colLetter] = 'year';
        elseif (strpos($n, 'unit') !== false) $colMap[$colLetter] = 'units';
        elseif (strpos($n, 'section') !== false) $colMap[$colLetter] = 'section';
        elseif (strpos($n, 'email') !== false) $colMap[$colLetter] = 'email';
    }
}

// We won't insert ID if provided
$fields = array_unique(array_values($colMap));
$fields = array_values(array_filter($fields, fn($f) => $f !== '_id'));

if (empty($fields)) {
    echo json_encode(['success' => false, 'message' => 'Could not detect any recognizable columns in the header.']);
    exit;
}

/* === Duplicate year check === */
$yearColLetter = array_search('year', $colMap, true);
if ($yearColLetter !== false) {
    $yearValues = [];
    $rowIndex = 1;
    foreach ($rows as $r) {
        if ($rowIndex++ === 1) continue; // skip header
        $year = trim((string)$r[$yearColLetter]);
        if ($year !== '') $yearValues[] = $year;
    }
    $yearValues = array_values(array_unique($yearValues));

    if (!empty($yearValues)) {
        $placeholders = implode(',', array_fill(0, count($yearValues), '?'));
        $stmtCheck = $pdo->prepare("SELECT DISTINCT year FROM student_list WHERE year IN ($placeholders)");
        $stmtCheck->execute($yearValues);
        $existingYears = $stmtCheck->fetchAll(PDO::FETCH_COLUMN);

        if (!empty($existingYears)) {
            echo json_encode([
                'success' => false,
                'message' => 'Import blocked: Year(s) already exist - ' . implode(', ', $existingYears)
            ]);
            exit;
        }
    }
}

// Prepare insert
$cols_sql = implode(',', array_map(fn($c) => "`$c`", $fields));
$placeholders = implode(',', array_map(fn($c) => ':' . $c, $fields));
$sql = "INSERT INTO student_list ($cols_sql) VALUES ($placeholders)";
$stmt = $pdo->prepare($sql);

$inserted = 0;
$skipped = [];
$rowIndex = 1;

foreach ($rows as $r) {
    if ($rowIndex++ === 1) continue; // header
    $params = [];
    $emptyRow = true;

    foreach ($colMap as $colLetter => $dbField) {
        if ($dbField === '_id') continue;
        $val = isset($r[$colLetter]) ? trim((string)$r[$colLetter]) : null;
        if ($val !== '' && $val !== null) $emptyRow = false;
        $params[$dbField] = $val;
    }

    if ($emptyRow) continue;

    if (empty($params['code']) || empty($params['last_name']) || empty($params['first_name']) || empty($params['course'])) {
        $skipped[] = "Row {$rowIndex}: missing required fields (code/last_name/first_name/course)";
        continue;
    }

    // birth date parsing
    if (isset($params['birth']) && $params['birth'] !== '') {
        if (is_numeric($params['birth'])) {
            try {
                $dt = PhpDate::excelToDateTimeObject($params['birth']);
                $params['birth'] = $dt->format('Y-m-d');
            } catch (Exception $e) {
                $params['birth'] = null;
            }
        } else {
            $ts = strtotime($params['birth']);
            $params['birth'] = $ts !== false ? date('Y-m-d', $ts) : null;
        }
    } else {
        $params['birth'] = null;
    }

    if (isset($params['year'])) $params['year'] = ($params['year'] === '' ? null : (int)$params['year']);
    if (isset($params['units'])) $params['units'] = ($params['units'] === '' ? null : (int)$params['units']);

    if (isset($params['email'])) {
        $params['email'] = $params['email'] === '' ? null : filter_var($params['email'], FILTER_SANITIZE_EMAIL);
    }

    try {
        $stmt->execute($params);
        $inserted++;
    } catch (Exception $e) {
        $skipped[] = "Row {$rowIndex}: DB error - " . $e->getMessage();
    }
}

echo json_encode([
    'success' => true,
    'inserted' => $inserted,
    'skipped' => $skipped
]);
exit;
