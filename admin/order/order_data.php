<?php
require_once(BASE_PATH.'/autoload.php');

$columns = array(
    'id',
    'address',
    'created_date',
    'status',
    'payment_status',
    'total'
);

// Set the limit and offset for pagination
$limit = isset($_POST['length']) ? intval($_POST['length']) : 10;
$offset = isset($_POST['start']) ? intval($_POST['start']) : 0;

$paymentstatus = isset($_POST['paymentstatus']) ? $_POST['paymentstatus'] : '';
$searchByStatus = isset($_POST['searchByStatus']) ? $_POST['searchByStatus'] : '';
$searchStartDate = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$searchEndDate = isset($_POST['end_date']) ? $_POST['end_date'] : '';

$searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';

$sql = "SELECT * FROM `orders` WHERE 1";

if (!empty($searchValue)) {
    $sql .= " AND (`id` = '$searchValue' OR `status` LIKE '%$searchValue%' OR `payment_status` LIKE '%$searchValue%' OR `total` LIKE '%$searchValue%' OR JSON_UNQUOTE(JSON_EXTRACT(address, '$.f_name')) LIKE '%$searchValue%')";
}

if (!empty($searchByStatus)) {
    $sql .= " AND `status` = '$searchByStatus'";
}

if (!empty($paymentstatus)) {
    $sql .= " AND `payment_status` = '$paymentstatus'";
}

if (!empty($searchStartDate) && !empty($searchEndDate)) {
    $searchStartDateFormatted = date('Y-m-d H:i:s', strtotime($searchStartDate));
    $searchEndDateFormatted = date('Y-m-d 23:59:59', strtotime($searchEndDate)); 
    $sql .= " AND `created_date` >= '$searchStartDateFormatted' AND `created_date` <= '$searchEndDateFormatted'";
}

// Fetch total number of records without any filters
$totalRecords = $db->select("SELECT COUNT(*) as total FROM `orders`");
$totalRecords = $totalRecords[0]['total'];

// If any filters are applied, calculate the total filtered records
$totalFilteredRecords = $totalRecords;

if (!empty($searchValue) || !empty($searchByStatus) || !empty($paymentstatus) || (!empty($searchStartDate) && !empty($searchEndDate))) {
    $sqlFiltered = "SELECT COUNT(*) as total FROM `orders` WHERE 1";

    if (!empty($searchValue)) {
        $sqlFiltered .= " AND (`id` = '$searchValue' OR `status` LIKE '%$searchValue%' OR `payment_status` LIKE '%$searchValue%' OR `total` LIKE '%$searchValue%' OR JSON_UNQUOTE(JSON_EXTRACT(address, '$.f_name')) LIKE '%$searchValue%')";
    }

    if (!empty($searchByStatus)) {
        $sqlFiltered .= " AND `status` = '$searchByStatus'";
    }

    if (!empty($paymentstatus)) {
        $sqlFiltered .= " AND `payment_status` = '$paymentstatus'";
    }

    if (!empty($searchStartDate) && !empty($searchEndDate)) {
        $searchStartDateFormatted = date('Y-m-d H:i:s', strtotime($searchStartDate));
        $searchEndDateFormatted = date('Y-m-d 23:59:59', strtotime($searchEndDate)); 
        $sqlFiltered .= " AND `created_date` >= '$searchStartDateFormatted' AND `created_date` <= '$searchEndDateFormatted'";
    }

    $filteredResult = $db->select($sqlFiltered);
    $totalFilteredRecords = $filteredResult[0]['total'];
}

// Add ordering to the SQL query and limit-offset logic
$orderBy = "";
if (isset($_POST['order']) && is_array($_POST['order'])) {
    $columnIdx = $_POST['order'][0]['column'];
    $columnName = $columns[$columnIdx];
    $columnDir = $_POST['order'][0]['dir'];
    $orderBy = " ORDER BY `$columnName` $columnDir";
}

$sql .= $orderBy . " LIMIT $limit OFFSET $offset";

$result = $db->select($sql);

$data = array();

foreach ($result as $row) {
    $customerName = json_decode($row['address'], true)['f_name'];
    $date = new DateTime($row['created_date']);
    $newDateFormat = $date->format('Y-m-d');

    $actionButtons = '<a href="' . ADMIN_URL . '/order/edit.php?id=' . $row['id'] . '" class="btn btn-primary btn-sm m-6"">Edit</a>';
    $actionButtons .= '<a href="' . ADMIN_URL . '/order/delete.php?id=' . $row['id'] . '" class="btn-sm btn btn-danger deleteRecord">Delete</a>';
    
    $data[] = array(
        'id' => $row['id'],
        'customer_name' => $customerName,
        'date' => $newDateFormat,
        'status' => $row['status'],
        'payment_status' => $row['payment_status'],
        'total' => displayPrice($row['total']), 
        'action' => $actionButtons,
    );
}

$response = array(
    "draw" => isset($_POST['draw']) ? intval($_POST['draw']) : 0,
    "recordsTotal" => $totalRecords, 
    "recordsFiltered" => $totalFilteredRecords, // Use totalFilteredRecords for pagination info
    "data" => $data,
);

echo json_encode($response);
?>
