<?php
$isLoggedIn = isset($_SESSION['user']);
$isAdminLoggedIn = $isLoggedIn && ($_SESSION['user']['role'] === "admin");

$currentPath = $_SERVER['REQUEST_URI']; // keep it exactly as-is

$links = ["home", "attendance", "admin", "login", "register"];
?>

<div class="container">
  <nav class="navbar navbar-expand-lg navbar-light bg-light my-4">
    <div class="container-fluid">
      <a class="navbar-brand" href="/"><?php echo $isActiveLink?></a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <?php
          foreach ($links as $link) {
            $isActiveLink = $link === trim($currentPath,'/');
            $activeClass= $isActiveLink ?'underline':'';
              if(!$isAdminLoggedIn && $link=== 'admin'){
                continue;
              }
              if($isLoggedIn && $link=== "login"){
                continue;
              }
              if($isLoggedIn && $link=== "register"){
                continue;
              }
              echo '<li class="nav-item">
                <a class="nav-link '.$activeClass.'" href="'.'/'.$link.'">'
                  . ucfirst($link) . '
                </a>
              </li>';
              
          }?>
        </ul>

        <?php if ($isLoggedIn): ?>
          <form class="d-flex" action="/logout.php" method="POST">
            <button class="btn btn-danger" type="submit">Log Out</button>
          </form>
        <?php endif; ?>

      </div>
    </div>
  </nav>

  <?php echo $html ?? ''; ?>
</div>
