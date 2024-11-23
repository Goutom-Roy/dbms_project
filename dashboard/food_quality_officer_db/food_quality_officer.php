<?php
header("Content-Type: application/json");
include '../config/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle data fetching requests
    if (isset($_POST['type'])) {
        $type = $_POST['type'];
        $data = [];

        try {
            if ($type === 'inspectionReports') {
                $query = "
                    SELECT 
                        fqo.inspection_id, 
                        p.product_name, 
                        fqo.inspection_date, 
                        fqo.status, 
                        fqo.remarks
                    FROM 
                        Food_Quality_Officer_t fqo
                    JOIN 
                        Product_t p ON fqo.product_id = p.product_id
                ";
            } 
            elseif ($type === 'inspectedProducts') {
                $query = "
                    SELECT 
                        fqo.inspection_id, 
                        p.product_name, 
                        fqo.inspection_type, 
                        fqo.quality_status, 
                        fqo.next_steps
                    FROM 
                        Food_Quality_Officer_t fqo
                    JOIN 
                        Product_t p ON fqo.product_id = p.product_id
                ";
            }
            elseif ($type === 'cropQuality') {
                $query = "
                    SELECT 
                        fqo.inspection_id, 
                        p.product_name AS crop, 
                        fqo.quality_score, 
                        fqo.quality_level
                    FROM 
                        Food_Quality_Officer_t fqo
                    JOIN 
                        Product_t p ON fqo.product_id = p.product_id
                ";
            }

            $result = $conn->query($query);
            if (!$result) {
                throw new Exception($conn->error);
            }

            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            echo json_encode(['success' => true, 'data' => $data]);
            exit;
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }

    // Handle edit requests
    if (isset($_POST['editType']) && isset($_POST['recordId'])) {
        $type = $_POST['editType'];
        $inspection_id = $_POST['recordId'];
        
        try {
            if ($type === 'inspectionReports') {
                $status = $_POST['status'];
                $remarks = $_POST['remarks'];
                
                $query = "UPDATE Food_Quality_Officer_t 
                         SET status = ?, remarks = ? 
                         WHERE inspection_id = ?";
                
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssi", $status, $remarks, $inspection_id);
                
            } elseif ($type === 'inspectedProducts') {
                $inspection_type = $_POST['inspection_type'];
                $quality_status = $_POST['quality_status'];
                $next_steps = $_POST['next_steps'];
                
                $query = "UPDATE Food_Quality_Officer_t 
                         SET inspection_type = ?, quality_status = ?, next_steps = ? 
                         WHERE inspection_id = ?";
                
                $stmt = $conn->prepare($query);
                $stmt->bind_param("sssi", $inspection_type, $quality_status, $next_steps, $inspection_id);
                
            } elseif ($type === 'cropQuality') {
                $quality_score = $_POST['quality_score'];
                $quality_level = $_POST['quality_level'];
                
                $query = "UPDATE Food_Quality_Officer_t 
                         SET quality_score = ?, quality_level = ? 
                         WHERE inspection_id = ?";
                
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssi", $quality_score, $quality_level, $inspection_id);
            }
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => $stmt->error]);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
$conn->close();
?>