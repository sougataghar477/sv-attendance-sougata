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
  $csrf = bin2hex(random_bytes(32));
  $_SESSION['login_csrf'] = $csrf;
// Load our environment variables from the .env file:
    
     $html=!isset($_SESSION['user'])?

     '
     <div class="col-md-6 col-lg-4 mt-4 mx-auto shadow-lg rounded-4 p-4">
     <h3 class="text-center">Login</h3>
     <form class="" onsubmit="handleLogin(event)" method="POST">
  
  <div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input
      type="email"
      class="form-control"
      id="email"
      name="email"
      required
    />
  </div>

  <div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input
      type="password"
      class="form-control"
      id="password"
      name="password"
      required
    />
    <input type="hidden" name="login_csrf" value="'.$csrf.'" />
  </div>

  <button type="submit" class="btn btn-dark w-100">
    Login
  </button>

</form>
</div>
<button type="button" class="btn btn-primary d-none" data-bs-toggle="modal" data-bs-target="#exampleModal2"id="loginModalBtn">
  Launch demo modal
</button>

 
<div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       
        <div class="alert alert-warning" role="alert">
    Invalid Email or Password . Please retry
  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
':header("Location: /attendance");
     include "../container.php";
  ?>
<script src="/js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>