<?php

include ('common.php');


?>

 



<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="/index.php">
      <span style="color:cyan">Quick</span>-Ask
    </a>

    <!-- Hamburger button for small screens -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" 
      aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Collapsible content -->
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <a class="nav-link nav-style" href="/index.php">Home</a>
        <a class="nav-link nav-style" href="?ask-question=true">Ask-Question</a>

        <?php if (!isset($_SESSION['user']['username'])): ?>
          <a class="nav-link nav-style" href="?signup=true">Signup</a>
          <a class="nav-link nav-style" href="?login=true">Login</a>
        <?php else: ?>
          <a class="nav-link nav-style" href="?logout=true">Logout</a>
          <a class="nav-link nav-style" href="?account=true">My account</a>
        <?php endif; ?>

        <!-- Left-side Search Bar -->
<form class="d-flex align-items-center me-10" role="search" style="max-width: 200px;"  action="" method="GET">
  <input name="search" class="form-control form-control-sm bg-dark text-white border-secondary" 
         type="search" 
         placeholder="Search queries" 
         required
       >
  <button class="btn btn-outline-cyan btn-sm ms-2" type="submit"  style="border-color: cyan; color: cyan;">
    🔍
  </button>
</form>

      </div>
    </div>
  </div>
</nav>
