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

<h1>PHP Server Running</h1>

<?php
$isAdminLoggedIn =
    isset($_SESSION['user'], $_SESSION['user']['role'], $_SESSION['user']['user_key'])
    && $_SESSION['user']['role'] === "admin";

if ($isAdminLoggedIn) {
 
    if (!isset($_SESSION['admin_csrf'])) {
        $_SESSION['admin_csrf'] = bin2hex(random_bytes(32));
    }

    $conn = new mysqli(
        "db.fr-pari1.bengt.wasmernet.com",
        "a890400970b4800092c62a05eeea",
        "0694a890-4009-71fc-8000-31acc0d66b54",
        "userfeedbacks",
        10272
    );

    if ($conn->connect_error) {
        $html = "<h1>Database connection failed</h1>";
        exit;
    } else {

        $result = $conn->query(
            "SELECT username,users_id, attended_at, check_in_time, device_info,ip FROM attendance"
        );

        if ($result && $result->num_rows > 0) {

            $attendances = '';

            while ($row = $result->fetch_assoc()) {
                $attendances .= '
                <tr>
                    <td>' . $row['username'] . '</td>
                    <td>' . $row['users_id'] . '</td>
                    <td>' . $row['attended_at'] . '</td>
                    <td>' . $row['check_in_time'] . '</td>
                    <td>' . $row['device_info'] . '</td>
                    <td>' . $row['ip'] . '</td>
                </tr>';
            }

$html = '
<table id="usersTable" class="table table-striped table-bordered">
  <thead>
    <tr>
     <th>Username</th>
      <th>User ID</th>
      <th>Attended At</th>
      <th>Check In Time</th>
      <th>Device Info</th>
      <th>IP Address</th>
    </tr>
  </thead>
  <tbody>
    '.$attendances.'
  </tbody>
</table>';






        } else {
            $html = '<h1>0 Results</h1>';
        }
    }
}
else{
  header("Location: /login");
exit;

}
include "../container.php";
?>

<script src="/js/main.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
  $(document).ready(function () {
    $('#usersTable').DataTable({
      pageLength: 5,
      lengthMenu: [5, 10, 25, 50],
      ordering: true,
      searching: true,
      responsive: true
    });
  });
</script>

</body>
</html>
