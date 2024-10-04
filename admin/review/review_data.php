<?php
require_once(BASE_PATH.'/autoload.php');

$columns = array(
    'id',
    'product_id',
    'user_id',
    'rating',
    'status',
    'created_date'
);

$offset = isset($_POST['start']) ? intval($_POST['start']) : 0;
$limit = isset($_POST['length']) ? intval($_POST['length']) : 10;

$sortColumnIndex = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : 0;
$sortDir = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'desc';
$sortColumn = ($sortColumnIndex >= 0 && $sortColumnIndex < count($columns)) ? $columns[$sortColumnIndex] : $columns[0];

if ($sortDir === 'desc') {
    $sortDir = 'asc';
} else {
    $sortDir = 'desc';
}


// $sortColumnIndex = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : 0;
// $sortDir = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'desc';

// $sortColumn = ($sortColumnIndex >= 0 && $sortColumnIndex < count($columns)) ? $columns[$sortColumnIndex] : $columns[0];





$statusFilter = isset($_POST['statusFilter']) ? $_POST['statusFilter'] : '';
$userFilter = isset($_POST['userFilter']) ? $_POST['userFilter'] : '';
$productFilter = isset($_POST['productFilter']) ? $_POST['productFilter'] : '';
$searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';

// Assuming $db is your database connection instance
$sql = "SELECT " . implode(',', $columns) . " FROM `reviews` WHERE 1 = 1";

if (!empty($statusFilter)) {
    $sql .= " AND status = '$statusFilter'";
}

if (!empty($userFilter)) {
    $sql .= " AND ((user_id IN (SELECT id FROM `users` WHERE CONCAT(f_name, ' ', l_name) LIKE '%$userFilter%')) OR (user_id IN (SELECT id FROM `users` WHERE username LIKE '%$userFilter%')))";
}

if (!empty($productFilter)) {
    $sql .= " AND product_id = '$productFilter'";
}
if (!empty($searchValue)) {
    $sql .= " AND (id LIKE '%$searchValue%' OR product_id LIKE '%$searchValue%' OR user_id LIKE '%$searchValue%'  OR status  LIKE '%$searchValue%' OR created_date  LIKE '%$searchValue%' OR rating   LIKE '%$searchValue%')";
}

$sqlTotal = "SELECT COUNT(*) as total FROM `reviews`";
$totalRecordsResult = $db->select($sqlTotal);
$totalRecords = $totalRecordsResult[0]['total'];

$sqlFiltered = $sql; // Store the filtered query without ORDER BY and LIMIT for calculating filtered records

$sql .= " ORDER BY $sortColumn $sortDir LIMIT $offset, $limit";

$sqlFilteredCount = "SELECT COUNT(*) as filtered FROM ($sqlFiltered) AS filtered_data";
$filteredRecordsResult = $db->select($sqlFilteredCount);
$filteredRecords = $filteredRecordsResult[0]['filtered'];

$result = $db->select($sql);

$data = array();

foreach ($result as $row) {
    $product_id = $row['product_id'];

    $sql = "SELECT * FROM `products` WHERE id = $product_id";
    $productInfo = $db->select($sql);

    if ($productInfo) {
        $product_name = '<a href="' . BASE_URL . '/product/' . $productInfo[0]['slug'] . '">' . $productInfo[0]['name'] . '</a>';
    } else {
        $product_name = "Product not found";
    }

    $user_id = $row['user_id'];
    $sql ="SELECT * FROM `users` WHERE id =$user_id";
    $alluser = $db->select($sql);

    $allUsernames = ''; // Initialize an empty string to store usernames
    
    foreach ($alluser as $userData) {
        $fullName = ucwords($userData['f_name']) . ' ' . ucwords($userData['l_name']);
        $username = $userData['username'];

        $allUsernames .= $fullName . ', '; // Concatenate usernames with a comma and space
    }

    $allUsernames = rtrim($allUsernames, ', '); // Remove the trailing comma and space
    
    $rating = $row['rating'];
    $status = $row['status'];

    // Date format
    $created_date = $row['created_date'];
    $formatted_date = date('j M, Y', strtotime($created_date));

    // Add different colors based on status
    if ($status == 'approved') {
        $status_html = '<span class="badge badge-success">Approved</span>';
    } elseif ($status == 'banned') {
        $status_html = '<span class="badge badge-danger">Banned</span>';
    } elseif ($status == 'pending') {
        $status_html = '<span class="badge badge-warning">Pending</span>';
    } else {
        $status_html = '<span class="badge badge-secondary">' . $status . '</span>';
    }

    $actionButtons = '<a href="' . ADMIN_URL . '/review/edit_review.php?id=' . $row['id'] . '"  class="btn btn-primary btn-sm">Edit</a>';
    $actionButtons .= '<a href="' . ADMIN_URL . '/review/delete.php?id=' . $row['id'] . '" class="btn-sm btn btn-danger deleteRecord">Delete</a>';

    $data[] = array(
        'id' => $row['id'],
        'product_id' => $product_name,
        'user_id' => $allUsernames ,
        'rating' => $rating,
        'status' => $status_html, // Use the HTML for status with different colors
        'created_date' => $formatted_date,
        'action' => $actionButtons,
    );
}

$response = array(
    "draw" => isset($_POST['draw']) ? intval($_POST['draw']) : 0,
    "recordsTotal" => $totalRecords, // Total number of records (before filtering)
    "recordsFiltered" => $filteredRecords, // Total number of records (after filtering)
    "data" => $data
);

echo json_encode($response);
?>
