<?php
$isLoggedIn = isset($_SESSION['user']);
$isAdminLoggedIn = isset($_SESSION['user']['role'])&& $_SESSION['user']['role']==="admin";
$html;
echo '

<div class="container">
<nav class="navbar navbar-expand-lg navbar-light bg-light my-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/attendance">Attendance</a>
        </li>'.($isAdminLoggedIn?'
        <li class="nav-item">
          <a class="nav-link" href="/admin" tabindex="-1">Admin</a>
        </li>':'').'
      </ul>'.($isLoggedIn?'
      <form class="d-flex" action="/logout.php">
         
        <button class="btn btn-danger" type="submit">Log Out</button>
      </form>':'').'
    </div>
  </div>
</nav>
'
.$html.
'</div>'
?>