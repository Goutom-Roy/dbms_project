<?php
// Include the database connection file
include('../config/connect.php');

// Fetch data if requested (GET request)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
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
} 

// Handle update requests (POST request)
else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the data from the request
    $data = json_decode(file_get_contents('php://input'), true);

    // Check the data source for which table it belongs
    if (isset($data['product_name'])) { // Product data update
        if (isset($data['product_name'], $data['product_demand'], $data['expiration_date'], $data['region_of_product'])) {
            $sql = "UPDATE Product_t SET 
                    product_name = ?, 
                    product_demand = ?, 
                    expiration_date = ?, 
                    region_of_product = ? 
                    WHERE product_name = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", 
                              $data['product_name'], 
                              $data['product_demand'], 
                              $data['expiration_date'], 
                              $data['region_of_product'], 
                              $data['product_name']);

            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Product updated successfully"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error updating product"]);
            }
            $stmt->close();
        }
    }

    else if (isset($data['employee_name'])) { // Agri Officer data update
        if (isset($data['product_scanned'], $data['check_storage_condition'], $data['data_received'], $data['selling_price_per_kg'], $data['employee_name'])) {
            $sql = "UPDATE Agri_Officer_t SET 
                    product_scanned = ?, 
                    check_storage_condition = ?, 
                    data_received = ?, 
                    selling_price_per_kg = ? 
                    WHERE employee_name = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", 
                              $data['product_scanned'], 
                              $data['check_storage_condition'], 
                              $data['data_received'], 
                              $data['selling_price_per_kg'], 
                              $data['employee_name']);

            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Agri Officer data updated successfully"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error updating Agri Officer data"]);
            }
            $stmt->close();
        }
    }

    // Close the database connection
    $conn->close();
} 
?>
