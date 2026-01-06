<?php
// Start session for user and CSRF handling
session_start();

// Set local timezone
date_default_timezone_set("Asia/Kolkata");

// Get current time and define allowed working hours
$currentTime = strtotime(date("H:i"));
$startTime = strtotime("00:00");
$endTime = strtotime("23:30");

// Check if current time is within working hours
$isWorkingHours = $currentTime >= $startTime && $currentTime <= $endTime ? true : false;

// Validate CSRF token
$isCsrfValid =
    isset($_POST['attendance_csrf'], $_SESSION['attendance_csrf']) &&
    hash_equals($_SESSION['attendance_csrf'], $_POST['attendance_csrf']);

// Allow only POST requests during working hours with valid CSRF
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isWorkingHours && $isCsrfValid) {

    // Check if user is logged in
    if (!isset($_SESSION['user']['id']) || !isset($_SESSION['user']['user_key'])) {
        echo json_encode(["status" => "error", "message" => "Unauthorized"]);
        exit;
    }

    // Get user and request data
    $userId = intval($_SESSION['user']['id']);
    $userName = $_SESSION['user']['name'];
    $ip = $_SERVER['REMOTE_ADDR'];
    $deviceInfo = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    $attendanceDay = date("Y-m-d");
    $checkinTime = date("H:i:s");
    $createdAt = date("Y-m-d H:i:s", $_SERVER['REQUEST_TIME']);

    // Create database connection
    $conn = new mysqli(
        "db.fr-pari1.bengt.wasmernet.com",
        "a890400970b4800092c62a05eeea",
        "0694a890-4009-71fc-8000-31acc0d66b54",
        "userfeedbacks",
        10272
    );

    // Stop if database connection fails
    if ($conn->connect_error) {
        echo json_encode(["status" => "error", "message" => $conn->connect_error]);
        exit;
    }

    // Check if attendance is already marked for today
    $stmt = $conn->prepare(
        "SELECT users_id FROM attendance WHERE users_id = ? AND attended_at = ?"
    );
    $stmt->bind_param("is", $userId, $attendanceDay);
    $stmt->execute();

    $result = $stmt->get_result();

    // If attendance already exists
    if ($result->num_rows > 0) {

        // Mark attendance in session
        $_SESSION['user']['attendance'] = true;

        echo json_encode([
            "status" => "failure",
            "message" => "Attendance Marked Already"
        ]);
        exit;

    } else {

        // Insert new attendance record
        $stmt = $conn->prepare(
            "INSERT INTO attendance 
            (users_id, username, attended_at, check_in_time, created_at, device_info, ip)
            VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "issssss",
            $userId,
            $userName,
            $attendanceDay,
            $checkinTime,
            $createdAt,
            $deviceInfo,
            $ip
        );

        // If attendance insert succeeds
        if ($stmt->execute()) {

            // Update session and remove CSRF token
            $_SESSION['user']['attendance'] = true;
            unset($_SESSION['attendance_csrf']);

            echo json_encode([
                "status" => "success",
                "message" => "Attendance Marked Successfully"
            ]);
            exit;

        } else {

            // Handle duplicate or insert error
            if ($stmt->errno) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Duplicate Found"
                ]);
                exit;
            }
        }
    }

} else {
    // Invalid request, CSRF failure, or outside working hours
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request"
    ]);
    exit;
}
