<?php
session_start();
header("Content-Type: application/json");

/* =========================
   CSRF VALIDATION
========================= */
$isCsrfValid =
    isset($_POST['login_csrf'], $_SESSION['login_csrf']) &&
    hash_equals($_SESSION['login_csrf'], $_POST['login_csrf']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isCsrfValid) {

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
        "SELECT id, role, user_key, password 
         FROM users 
         WHERE email = ? 
         LIMIT 1"
    );

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        /* ===== PASSWORD CHECK (UNCHANGED) ===== */
        if ($password === $user['password']) {

            /* ✅ MINIMAL FIXES */
            session_regenerate_id(true);
            unset($_SESSION['login_csrf']);

            $_SESSION['user'] = [
                "id" => $user['id'],
                "role" => $user['role'],
                "attendance" => false,
                "user_key" => $user['user_key']
            ];

            echo json_encode([
                "status" => "success",
                "message" => "Login successful",
                "user" => [
                    "role" => $user['role']
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
        "message" => "Invalid request or invalid token"
    ]);
    exit;
}
