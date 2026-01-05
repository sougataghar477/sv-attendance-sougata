<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PHP App</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
 
</head>

<body>

  <?php
   
 date_default_timezone_set("Asia/Kolkata"); // Set to your local timezone
 $currentTime = strtotime(date("H:i"));
 $currentDate = date("Y-m-d");
 $userId = intval($_SESSION['user']['id']);
 $startTime = strtotime("00:00");
 $endTime = strtotime("23:30");
 $isWorkingHours = $currentTime>=$startTime && $currentTime<=$endTime?true:false;
 $deviceInfo = $_SERVER['HTTP_USER_AGENT'];
  $isLoggedIn =isset($_SESSION['user']);
  $role = $isLoggedIn ? $_SESSION['user']['role']:null;
  $userkey = $isLoggedIn ? $_SESSION['user']['user_key']:null;
    $conn = new mysqli(
        "db.fr-pari1.bengt.wasmernet.com",
        "a890400970b4800092c62a05eeea",
        "0694a890-4009-71fc-8000-31acc0d66b54",
        "userfeedbacks",
        10272
    );
 

$stmt = $conn->prepare(
    "SELECT * FROM attendance WHERE users_id=? AND DATE(attended_at) = ?"
);
$stmt->bind_param("is", $userId, $currentDate);
$stmt->execute();
$a = $stmt->get_result();
$attendanceMarkedOnce = $a->num_rows > 0;

  
    if($isLoggedIn && $userkey){
        if (!isset($_SESSION['attendance_csrf'])) {
            $_SESSION['attendance_csrf'] = bin2hex(random_bytes(32));
}
        if($isWorkingHours){
            $html = !$attendanceMarkedOnce
    ? '<form class="col-md-6 col-lg-4 mt-4 mx-auto p-4 border rounded shadow-sm" onsubmit="handleAttendance(event)">
          <input type="hidden" name="attendance_csrf" value="' . trim(htmlspecialchars($_SESSION['attendance_csrf'])) . '">
          <button class="btn btn-primary btn-lg w-100">
              Mark Attendance
          </button>
       </form>
 
<button type="button" class="btn btn-primary d-none" data-bs-toggle="modal" data-bs-target="#exampleModal"id="attendanceModalBtn">
  Launch demo modal
</button>

 
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       Attendance Marked Successfully
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
'
    : '<div class="alert alert-success" role="alert">
  Attendance Marked Successfully
</div>';

        }
        else{
            $html='<h1>You missed the time window to mark attendance.</h1>';
        }
    }
    else{
        if($isWorkingHours){
            $html='<a href="/login"><button class="btn btn-primary">Please Log In To Mark Attendance</button></a>';
        }
        else{
            $html = '<div>
                <h1>You missed the time window</h1>
            </div>';
        }
    }
         
    include "../container.php";
    
  ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="/js/main.js"></script>
</body>
</html>