<?php
// Database connection
include '../config/connect.php';

// Check if the 'type' parameter is passed in the request
if (isset($_GET['type'])) {
    $type = $_GET['type'];

    // Fetch Crop Types
    if ($type == 'cropTypes') {
        $query = "
            SELECT p.product_name, p.category
            FROM Product_t p
        ";
        $result = $conn->query($query);

        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>{$row['product_name']}</td><td>{$row['category']}</td></tr>";
        }
    }

    // Fetch Planting Data
    elseif ($type == 'plantingData') {
        $query = "
            SELECT p.product_name, f.planting_date, p.seasonality
            FROM Farmer_t f
            JOIN Product_t p ON f.product_id = p.product_id
        ";
        $result = $conn->query($query);

        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>{$row['product_name']}</td><td>{$row['planting_date']}</td><td>{$row['seasonality']}</td></tr>";
        }
    }

    // Fetch Harvest Data
    elseif ($type == 'harvestData') {
        $query = "
            SELECT p.product_name, f.harvest_date, f.price_per_kg
            FROM Farmer_t f
            JOIN Product_t p ON f.product_id = p.product_id
        ";
        $result = $conn->query($query);

        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>{$row['product_name']}</td><td>{$row['harvest_date']}</td><td>{$row['price_per_kg']} $</td></tr>";
        }
    }

    // Fetch Price per KG Data
    elseif ($type == 'priceData') {
        $query = "
            SELECT p.product_name, f.price_per_kg
            FROM Farmer_t f
            JOIN Product_t p ON f.product_id = p.product_id
        ";
        $result = $conn->query($query);

        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>{$row['product_name']}</td><td>\${$row['price_per_kg']}</td></tr>";
        }
    }

    // If no valid type is provided, return an error
    else {
        echo "Invalid type parameter!";
    }
} else {
    echo "Type parameter is missing!";
}

$conn->close();
?>
