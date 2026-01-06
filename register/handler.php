<?php
// Start session for CSRF handling
session_start();

// Validate CSRF token
$isRegisterCsrfValid =
    isset($_POST['register_csrf'], $_SESSION['register_csrf']) &&
    hash_equals($_SESSION['register_csrf'], $_POST['register_csrf']);

// Allow only POST requests with valid CSRF
if($_SERVER['REQUEST_METHOD'] === "POST" && $isRegisterCsrfValid){

    // Get form values
    $name = trim($_POST['name']) ?? '';
    $email = trim($_POST['email']) ?? '';
    $password = $_POST['register_password'] ?? '';
    $confirmPassword = $_POST['register_confirm_password'] ?? '';

    // Password validation message container
    $passwordValidationMessage = 'Password ';

    // Check for missing fields
    if(empty($name) || empty($email) || empty($password) || empty($confirmPassword)){
        echo json_encode([
            "status" => "error",
            "message" => "Missing Fields"
        ]);
        exit;
    }

    // Validate name length
    if(strlen($name) < 2 || strlen($name) > 30){
        echo json_encode([
            "status" => "error",
            "message" => "Entered Name must be between 2-30 characters"
        ]);
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid email"
        ]);
        exit;
    }

    // Check minimum password length
    if(strlen($password) < 8){
        echo json_encode([
            "status" => "error",
            "message" => "Password too short"
        ]);
        exit;
    }

    // Password must contain lowercase letter
    if(!preg_match("/[a-z]/", $password)) {
        $passwordValidationMessage .= 'must contain a lower case,';
    }

    // Password must contain uppercase letter
    if(!preg_match("/[A-Z]/", $password)) {
        $passwordValidationMessage .= 'must contain an upper case,';
    }

    // Password must contain a digit
    if(!preg_match("/[0-9]/", $password)) {
        $passwordValidationMessage .= 'must contain a digit,';
    }

    // Password must contain a special character
    if(!preg_match('/[ -\/:-@\[-\`{-~]/', $password)) {
        $passwordValidationMessage .= 'must contain a special character,';
    }

    // Password and confirm password must match
    if($password !== $confirmPassword){
        $passwordValidationMessage .= 'must match.';
    }

    // If password validation failed, return message
    if($passwordValidationMessage !== "Password "){
        $passwordValidationMessage = substr_replace($passwordValidationMessage, "", -1);
        echo json_encode([
            "status" => "error",
            "message" => $passwordValidationMessage
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

    // Hash password securely
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Generate unique user key
    $userKey = bin2hex(random_bytes(16));

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // If email already exists, stop registration
    if ($result->num_rows > 0) {
        echo json_encode([
            "status" => "error",
            "message" => "Email already registered"
        ]);
        exit;
    }

    // Insert new user into database
    else {

        $stmt = $conn->prepare(
            "INSERT INTO users (name, email, password, role, user_key)
             VALUES (?, ?, ?, 'user', ?)"
        );

        $stmt->bind_param(
            "ssss",
            $name,
            $email,
            $hashedPassword,
            $userKey
        );

        // If insert succeeds
        if ($stmt->execute()) {

            // Remove CSRF token after successful registration
            unset($_SESSION['register_csrf']);

            echo json_encode([
                "status" => "success",
                "message" => "Registration successful"
            ]);
            exit;
        }
        // If insert fails
        else {
            echo json_encode([
                "status" => "error",
                "message" => "Registration failed"
            ]);
            exit;
        }
    }

}
// Invalid request or CSRF
else{
    echo json_encode([
        "status" => "error",
        "message" => "Wrong Method"
    ]);
}
?>
