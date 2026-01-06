<?php
$isLoggedIn = isset($_SESSION['user']);
$isAdminLoggedIn =
    isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === "admin";
?>

<div class="container">
  <nav class="navbar navbar-expand-lg navbar-light bg-light my-4">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Navbar</a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" href="/">Home</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="/attendance">Attendance</a>
          </li>

          <?php
          if ($isAdminLoggedIn) {
              echo '
              <li class="nav-item">
                <a class="nav-link" href="/admin">Admin</a>
              </li>';
          }

                    if (!$isLoggedIn) {
              echo '
              <li class="nav-item">
                <a class="nav-link" href="/login">Login</a>
              </li>';
          }
              if (!$isLoggedIn) {
              echo '
              <li class="nav-item">
                <a class="nav-link" href="/register">Register</a>
              </li>';
          }
          ?>
        </ul>

        <?php
        if ($isLoggedIn) {
            echo '
            <form class="d-flex" action="/logout.php" method="POST">
              <button class="btn btn-danger" type="submit">Log Out</button>
            </form>';
        }
        ?>
      </div>
    </div>
  </nav>

  <?php echo $html ?? ''; ?>
</div>
