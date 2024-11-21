<?php
// Include the database connection file
include('../config/connect.php');

// Fetch data from the Product_t table
$query = "SELECT product_name, region_of_product, product_demand, production_cost FROM Product_t";
$result = $conn->query($query);

// Initialize an array to hold the fetched data
$productData = [];

if ($result->num_rows > 0) {
    // Loop through the result set and populate the productData array
    while ($row = $result->fetch_assoc()) {
        $productData[] = $row;
    }
}

// Close the database connection
$conn->close();

// Return the data as a JSON response
header('Content-Type: application/json');
echo json_encode($productData);
?>
