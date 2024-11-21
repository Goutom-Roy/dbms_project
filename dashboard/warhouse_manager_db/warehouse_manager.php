<?php
// Include the database connection
include('../config/connect.php');

// Function to fetch data from the database
function fetchData() {
    global $conn;

    // Prepare and execute query for products data
    $productsQuery = "SELECT p.product_name, p.storage_condition, p.barcode_number FROM product_t p";
    $productsResult = mysqli_query($conn, $productsQuery);
    $products = [];
    
    // Fetch all product records
    while ($product = mysqli_fetch_assoc($productsResult)) {
        $products[] = $product;
    }

    // Prepare and execute query for inspections data
    $inspectionsQuery = "SELECT f.inspection_id, f.inspection_date, f.quality_status, f.remarks, f.inspection_type, 
                                f.quality_score, f.quality_level, p.product_name 
                         FROM food_quality_officer_t f 
                         JOIN product_t p ON f.product_id = p.product_id";
    $inspectionsResult = mysqli_query($conn, $inspectionsQuery);
    $inspections = [];
    
    // Fetch all inspection records
    while ($inspection = mysqli_fetch_assoc($inspectionsResult)) {
        $inspections[] = $inspection;
    }

    // Return data as a JSON response
    echo json_encode([
        'products' => $products,
        'inspections' => $inspections
    ]);
}

// Call the function to fetch data and return the response
fetchData();
?>
