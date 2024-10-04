<?php
require_once(BASE_PATH.'/autoload.php');

$columns = array(
    'ID',
    'username',
    'fullname',
    'Image',
    'role',
    'status',
    'Action'
);

// Set the limit and offset for pagination
$limit = isset($_POST['length']) ? intval($_POST['length']) : 10;
$offset = isset($_POST['start']) ? intval($_POST['start']) : 0;

// Default sorting column and order
$sortColumnIndex = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : 0;
$sortColumnName = $columns[$sortColumnIndex];
$sortOrder = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc';

// Fetch total number of records without any filters
$totalRecords = $db->select("SELECT COUNT(*) as total FROM `users`");
$totalRecords = $totalRecords[0]['total'];

// User role
$searchByRole = isset($_POST['searchByrole']) ? $_POST['searchByrole'] : '';

// Custom user status filter
$searchByStatus = isset($_POST['searchBystatus']) ? $_POST['searchBystatus'] : '';

// Search term from DataTable
$searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';

// SQL query for sorting, pagination, and search
$sql = "SELECT id, username, f_name, l_name, image, role, status FROM `users` WHERE 1";

// Searchbar 
if (!empty($searchValue)) {
    $sql .= " AND (`id` LIKE '%$searchValue%' OR `username` LIKE '%$searchValue%' OR `f_name` LIKE '%$searchValue%' OR `l_name` LIKE '%$searchValue%')";
}

// Search user status 
if (!empty($searchByStatus)) {
    $sql .= " AND `status` = '$searchByStatus'";
}

// Search user role filter 
if (!empty($searchByRole)) {
    $sql .= " AND `role` = '$searchByRole'";
}

// If any filters are applied, calculate the total filtered records
$totalFilteredRecords = $totalRecords;
if (!empty($searchValue) || !empty($searchByStatus) || !empty($searchByRole)) {
    $sqlFiltered = "SELECT COUNT(*) as total FROM `users` WHERE 1";

    // Searchbar 
    if (!empty($searchValue)) {
        $sqlFiltered .= " AND (`id` LIKE '%$searchValue%' OR `username` LIKE '%$searchValue%' OR `f_name` LIKE '%$searchValue%' OR `l_name` LIKE '%$searchValue%')";
    }

    // Search user status 
    if (!empty($searchByStatus)) {
        $sqlFiltered .= " AND `status` = '$searchByStatus'";
    }

    // Search user role filter 
    if (!empty($searchByRole)) {
        $sqlFiltered .= " AND `role` = '$searchByRole'";
    }

    $filteredResult = $db->select($sqlFiltered);
    $totalFilteredRecords = $filteredResult[0]['total'];
}

// Add sorting for the full name column (first name + last name)
if ($sortColumnName == 'fullname') {
    $sql .= " ORDER BY CONCAT(`f_name`, ' ', `l_name`) $sortOrder";
} else {
    $sql .= " ORDER BY `$sortColumnName` $sortOrder";
}

// Apply pagination
$sql .= " LIMIT $limit OFFSET $offset";

// data fetch
$userObj = $db->select($sql);

$data = array();
foreach ($userObj as $key => $value) {
    $userID = $value['id'];
    $userName = $value['username'];
    $fullname = $value['f_name'] . " " . $value['l_name'];
    $userImage = $value['image'];
    $role = $value['role'];
    $status = $value['status'];

    $actionButtons = '<a href="' . ADMIN_URL . '/users/edit.php?id=' . $userID . '" class="btn block btn-primary btn-sm" id="">Edit</a>';
    
    // Check if the user is not logged in before adding the "Delete" button
    $userdata = getCurrentUser();
      $userid = $userdata['id'];
    if ($userid != $value['id']) {
        $actionButtons .= ' <a href="' . ADMIN_URL . '/users/delete.php?id=' . $userID . '" class="btn-sm btn btn-danger deleteRecord">Delete</a>';
    }
    
    $data[] = array(
        'ID' => $userID,
        'username' => $userName,
        'fullname' => $fullname,
        'Image' => $userImage,
        'role' => $role,
        'status' => $status,
        'Action' => $actionButtons,
    );
}


$response = array(
    "draw" => intval($_POST['draw']), // Pass the draw parameter back in the response
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFilteredRecords,
    "data" => $data
);

echo json_encode($response);

?>
