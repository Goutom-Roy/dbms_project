<?php
// Include the database connection file
include('../config/connect.php');

// Function to fetch product data (Product Overview)
function fetchProductData($conn) {
    $sql = "SELECT product_name, product_demand, expiration_date, region_of_product FROM Product_t";
    $result = $conn->query($sql);
    $products = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    return $products;
}

// Function to fetch data received by Agri Officers
function fetchAgriOfficerData($conn) {
    $sql = "SELECT a.product_scanned, a.check_storage_condition, a.data_received, a.selling_price_per_kg, e.employee_name 
            FROM Agri_Officer_t a
            JOIN Employee_t e ON a.employee_id = e.employee_id";
    $result = $conn->query($sql);
    $officers = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $officers[] = $row;
        }
    }
    return $officers;
}

// Fetch the required data
$products = fetchProductData($conn);
$officers = fetchAgriOfficerData($conn);

// Return the data as a JSON object
echo json_encode([
    "products" => $products,
    "officers" => $officers
]);

// Close the database connection
$conn->close();
?>
