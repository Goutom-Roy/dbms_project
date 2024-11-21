<?php
header("Content-Type: application/json");
include '../config/connect.php';

// Check if the request is AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type']; // Type of data requested

    // Fetch Inspection Reports
    if ($type === 'inspectionReports') {
        $query = "
            SELECT 
                p.product_name, 
                fqo.inspection_date, 
                fqo.status, 
                fqo.remarks
            FROM 
                Food_Quality_Officer_t fqo
            JOIN 
                Product_t p 
            ON 
                fqo.product_id = p.product_id
        ";
        $result = $conn->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    }

    // Fetch Inspected Product Information
    elseif ($type === 'inspectedProducts') {
        $query = "
            SELECT 
                p.product_name, 
                fqo.inspection_type, 
                fqo.quality_status, 
                fqo.next_steps
            FROM 
                Food_Quality_Officer_t fqo
            JOIN 
                Product_t p 
            ON 
                fqo.product_id = p.product_id
        ";
        $result = $conn->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    }

    // Fetch Crop Quality Data
    elseif ($type === 'cropQuality') {
        $query = "
            SELECT 
                p.product_name AS crop, 
                fqo.quality_score, 
                fqo.quality_level
            FROM 
                Food_Quality_Officer_t fqo
            JOIN 
                Product_t p 
            ON 
                fqo.product_id = p.product_id
        ";
        $result = $conn->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    } else {
        echo json_encode(["error" => "Invalid request type"]);
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
$conn->close();
?>
