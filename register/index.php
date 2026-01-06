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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="/styles/style.css"/>
</head>

<body>

  <?php
  $csrf = bin2hex(random_bytes(32));
  $_SESSION['register_csrf'] = $csrf;
// Load our environment variables from the .env file:
    
     $html=!isset($_SESSION['user'])?
     '<form  class="col-md-6 col-lg-4 mt-4 mx-auto shadow-lg rounded-4 p-4" onsubmit="handleRegister(event)" method="POST" id="registerForm">
    <div class="mb-3">
    <label for="name" class="form-label">Name</label>
    <div>
    <input
      type="text"
      class="form-control"
      id="name"
      name="name"
      required
    />
    </div>
          <div class="invalid-feedback d-block">
         
      </div>
  </div>

  <div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <div class="position-relative">
    <input
      type="email"
      class="form-control"
      id="email"
      name="email"
      required
      pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
    />
    </div>
      <div class="invalid-feedback d-block">
         
      </div>
  </div>

  <div class="mb-3">
    <label for="register_password" class="form-label">Password</label>
    <div class="position-relative">
    <input
      type="password"
      class="form-control"
      id="register_password"
      name="register_password"
      required
    />
    <i class="bi bi-eye position-absolute" id="register_password_toggler"></i>
    </div>
        <div class="invalid-feedback d-block">
         
      </div>
      </div>
        <div class="mb-3">
    <label for="confirm_password" class="form-label">Confirm Password</label>
    <div class="position-relative">
    <input
      type="password"
      class="form-control d-flex"
      id="register_confirm_password"
      name="register_confirm_password"
      required
    /> 
    <i class="bi bi-eye position-absolute" id="confirm_password_toggler"></i>
    </div>
        <div class="invalid-feedback d-block">
         
      </div>
      </div>
      <input type="hidden" name="register_csrf" value="'.$csrf.'" />

  <button type="submit" class="btn btn-dark w-100">
    Register
  </button>

</form>
<button type="button" class="btn btn-primary d-none" data-bs-toggle="modal" data-bs-target="#exampleModalRegister"id="registerModalBtn">
  Launch demo modal
</button>

 
<div class="modal fade" id="exampleModalRegister" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       
        <div class="alert" role="alert" id="registerModalBody">
     
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