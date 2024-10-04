<?php
require_once(BASE_PATH.'/autoload.php');

$columns = array(
    'ID',
    'Name',
    'Image',
    'Price',
    'Sales',
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
$totalRecords = $db->select("SELECT COUNT(*) as total FROM `products`");
$totalRecords = $totalRecords[0]['total'];

// Search term from DataTable
$searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';

// SQL query for sorting, pagination, and search
$sql = "SELECT *, CAST(`regular_price` AS DECIMAL) AS `numeric_regular_price`, CAST(`sales_price` AS DECIMAL) AS `numeric_sales_price` FROM `products` WHERE (`ID` LIKE '%$searchValue%' OR `Name` LIKE '%$searchValue%')";

// If any filters are applied, calculate the total filtered records
if (!empty($searchValue)) {
    $sqlFiltered = $sql . " ORDER BY `$sortColumnName` $sortOrder";
} else {
    // No search, so we can apply the sorting on the numeric price values
    $sqlFiltered = $sql . " ORDER BY `numeric_regular_price` $sortOrder";
}

$sqlFiltered .= " LIMIT $limit OFFSET $offset";

$productObj = $db->select($sqlFiltered);

$data = array();
foreach ($productObj as $key => $value) {
    $ProductID = $value['id'];
    $ProductName = $value['name'];
    $ProductImage = $value['image'];
    $RegularPrice = displayPrice($value['regular_price']);
    $SalePrice = displayPrice($value['sales_price']);

    $data[] = array(
        'ID' => $ProductID,
        'Name' => $ProductName,
        'Image' => $ProductImage,
        'Price' => $RegularPrice,
        'Sales' => $SalePrice,
        'Action' => '<a href="' . ADMIN_URL . '/product/edit.php?id=' . $ProductID . '" class="btn block btn-primary btn-sm" id="">Edit</a>
        <a href="' . ADMIN_URL . '/product/delete.php?id=' . $ProductID. '" class="btn-sm btn btn-danger deleteRecord">Delete</a>'
    );
}

$response = array(
    "recordsTotal" => $totalRecords, 
    "recordsFiltered" => $totalRecords,
    "data" => $data
);

echo json_encode($response);
?>
