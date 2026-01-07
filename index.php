<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PHP App</title>
    <link rel="stylesheet" href="/styles/style.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>

<body>

  <?php
    $html ="
    <h1>Welcome to Your Attendance Page</h1>
    <div role='alert' class='alert alert-warning'>
        <h2>Attention</h2>
        <p>
        All existing users and administrators are required to head to the <a href='/login'>login page</a> which is at the navbar on top.
        If you haven't registered yet , please register immediately by clicking on the <a href='/register'>register page</a> in the navbar as it is mandatory.
        </p>
    </div>
    ";
    include "./container.php";
  ?>
<script src="/js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>