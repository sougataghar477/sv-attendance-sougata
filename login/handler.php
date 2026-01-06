<?php
// Start session for CSRF and login state
session_start();

// Tell client this endpoint returns JSON
header("Content-Type: application/json");

// Check CSRF token
$isCsrfValid =
    isset($_POST['login_csrf'], $_SESSION['login_csrf']) &&
    hash_equals($_SESSION['login_csrf'], $_POST['login_csrf']);

// Allow only POST requests with valid CSRF
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isCsrfValid) {

    // Get form inputs
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validate required fields
    if ($email === '' || $password === '') {
        echo json_encode([
            "status" => "error",
            "message" => "Email and password are required"
        ]);
        exit;
    }

    // Create database connection
    $conn = new mysqli(
        "db.fr-pari1.bengt.wasmernet.com",
        "a890400970b4800092c62a05eeea",
        "0694a890-4009-71fc-8000-31acc0d66b54",
        "userfeedbacks",
        10272
    );

    // Check database connection
    if ($conn->connect_error) {
        echo json_encode([
            "status" => "error",
            "message" => "Database connection failed"
        ]);
        exit;
    }

    // Prepare query to fetch user by email
    $stmt = $conn->prepare(
        "SELECT id, role, user_key, password, name
         FROM users
         WHERE email = ?"
    );

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // If user exists
    if ($result->num_rows === 1) {

        // Fetch user data
        $user = $result->fetch_assoc();

        // Verify password against hashed password
        if (password_verify($password, $user['password'])) {

            // Regenerate session ID for security
            session_regenerate_id(true);

            // Remove CSRF token after successful login
            unset($_SESSION['login_csrf']);

            // Store user data in session
            $_SESSION['user'] = [
                "id" => $user['id'],
                "role" => $user['role'],
                "attendance" => $_SESSION['user']['attendance'] ?? false,
                "user_key" => $user['user_key'],
                "name" => $user['name']
            ];

            // Send success response
            echo json_encode([
                "status" => "success",
                "message" => "Login successful",
                "user" => [
                    "role" => $user['role'],
                    "name" => $user['name']
                ]
            ]);
            exit;

        } else {
            // Password is incorrect
            echo json_encode([
                "status" => "error",
                "message" => "Invalid email or password"
            ]);
            exit;
        }

    } else {
        // Email not found
        echo json_encode([
            "status" => "error",
            "message" => "Invalid email or password"
        ]);
        exit;
    }

} else {
    // Invalid request method or CSRF token
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request or invalid token"
    ]);
    exit;
}
