<?php
// Include the database connection file
include('../config/connect.php');

// Initialize response array
$response = array(
    'qualityCheck' => array(),
);

// Fetch data for quality check
$qualityCheckQuery = "
    SELECT 
        fqo.product_id, 
        fqo.quality_status, 
        fqo.inspection_date
    FROM food_quality_officer_t fqo
    JOIN Product_t p ON fqo.product_id = p.product_id
";
$qualityCheckResult = mysqli_query($conn, $qualityCheckQuery);

if ($qualityCheckResult) {
    while ($row = mysqli_fetch_assoc($qualityCheckResult)) {
        $response['qualityCheck'][] = array(
            'product_id' => $row['product_id'],
            'quality_status' => $row['quality_status'],
            'inspection_date' => $row['inspection_date'],
        );
    }
} else {
    echo json_encode(array('error' => 'Error fetching quality check data.'));
    exit;
}

// Close database connection
mysqli_close($conn);

// Return data as JSON
echo json_encode($response);
?>
