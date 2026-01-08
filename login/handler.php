<?php
session_start();

header("Content-Type: application/json");

$isCsrfValid =
    isset($_POST['login_csrf'], $_SESSION['login_csrf']) &&
    hash_equals($_SESSION['login_csrf'], $_POST['login_csrf']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!$isCsrfValid) {
        echo json_encode([
            "status" => "expired",
            "message" => "Session expired. Please refresh and try again."
        ]);
        exit;
    }

    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        echo json_encode([
            "status" => "error",
            "message" => "Email and password are required"
        ]);
        exit;
    }

    $conn = new mysqli(
        "db.fr-pari1.bengt.wasmernet.com",
        "a890400970b4800092c62a05eeea",
        "0694a890-4009-71fc-8000-31acc0d66b54",
        "userfeedbacks",
        10272
    );

    if ($conn->connect_error) {
        echo json_encode([
            "status" => "error",
            "message" => "Database connection failed"
        ]);
        exit;
    }

    $stmt = $conn->prepare(
        "SELECT id, role, user_key, password, name
         FROM users
         WHERE email = ?"
    );

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            session_regenerate_id(true);
            unset($_SESSION['login_csrf']);

            $_SESSION['user'] = [
                "id" => $user['id'],
                "role" => $user['role'],
                "attendance" => $_SESSION['user']['attendance'] ?? false,
                "user_key" => $user['user_key'],
                "name" => $user['name']
            ];

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
            echo json_encode([
                "status" => "error",
                "message" => "Invalid email or password"
            ]);
            exit;
        }

    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid email or password"
        ]);
        exit;
    }

} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method"
    ]);
    exit;
}
