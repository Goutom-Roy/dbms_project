<?php
session_start();
include '../config/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Prevent SQL Injection
    $userId = $conn->real_escape_string($userId);
    $password = $conn->real_escape_string($password);
    $role = $conn->real_escape_string($role);

    // Query to check user credentials
    $sql = "SELECT * FROM Users_t WHERE user_id = '$userId' AND role = '$role'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if ($password === $user['password']) { // Use hashing for better security (e.g., password_verify())
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            switch ($role) {
                case 'ADMIN':
                    header('Location: /dbms_project/dashboard/admin_db/admin_dashboard.html');
                    break;
                case 'AGRICULTURE_OFFICER':
                    header('Location: /dbms_project/dashboard/agri_officer_db/agri_officer.html');
                    break;
                case 'FARMER':
                    header('Location: /dbms_project/dashboard/farmer_db/farmer.html');
                    break;
                case 'MARKET_MANAGER':
                    header('Location: /dbms_project/dashboard/market_manager_db/market_manager.html');
                    break;
                case 'WARHOUSE_MANAGER':
                    header('Location: /dbms_project/dashboard/warhouse_manager_db/warehouse_manager.html');
                    break;
                case 'FOODQUALITY_OFFICER':
                    header('Location: /dbms_project/dashboard/food_quality_officer_db/food_quality_officer.html');
                    break;
                case 'GOVERNMENT_OFFICER':
                    header('Location: government_officer.html');
                    break;
                case 'CUSTOMER':
                    header('Location: customer_dashboard.html');
                    break;
                default:
                    echo "Invalid role!";
            }
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "User not found!";
    }
} else {
    echo "Invalid request!";
}
?>
