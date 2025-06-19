<?php
include(__DIR__ . '/../main/common.php');
include(__DIR__ . '/../server/db.php');
$sucess =   $_SESSION['sucess'] ?? null;
$error =$_SESSION['login_error'] ?? [];
$old = $_SESSION['logininput'] ?? [];
?>




<!DOCTYPE html>
<html lang="en">
<head>
   <style>
    
.form-container {
  background-color: #111; /* Dark gray card */
  width: 100%;
  max-width: 400px;
  border: 1px solid cyan;
}

.input-dark {
  background-color: #222;
  color: white;
  border: 1px solid #00ffff;
}

.input-dark:focus {
  background-color: #111;
  border-color: #00ffff;
  box-shadow: 0 0 5px 1px cyan;
}

.btn-cyan {
  background-color: #00ffff !important;
  color: #000 !important;
  font-weight: bold;
  border: none;
  transition: background-color 0.3s ease;
}

.btn-cyan:hover {
  background-color: #00cccc !important;
}
span{
    color:#00cccc;
   
}
a{
     text-decoration:none;
}
p{
    color:white;
}

   </style>
</head>
<body>
<div class="d-flex justify-content-center align-items-center vh-100 bg-black">
    <div class="form-container p-4 rounded shadow">
      <form method="POST" action="/server/request.php">
        <h4 class="text-center mb-4 text-white">Login</h4>

        <?php if (isset($sucess)): ?>
    <div style="color: green; font-size: 0.9em;"><?= $sucess?></div>
    <meta http-equiv="refresh" content="3;url=index.php">
<?php endif; ?>


        <div class="mb-3">
          <label for="email" class="form-label text-white">Email address</label>
          
          <input type="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" class="form-control input-dark" name="email" id="email" placeholder="Enter email" required>
          <?php if (isset($error['email'])): ?>
    <div style="color: red; font-size: 0.9em;"><?= $error['email'] ?></div>
  <?php endif; ?>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label text-white">Password</label>
          <input type="password"  value="<?= htmlspecialchars($old['password'] ?? '') ?>" class="form-control input-dark" name="password"  id="password" placeholder="Enter password" required>
          <?php if (isset($error['password'])): ?>
    <div style="color: red; font-size: 0.9em;"><?= $error['password'] ?></div>
  <?php endif; ?>

        </div>

          <p> New here? <a href="?signup=true"><span>Register</span></a></p>

        <div class="d-grid">
          <button type="submit" name="login" value="submit" class="btn btn-cyan">Submit</button>
        </div>
      </form>
    </div>
  </div>
  </div>


</body>
</html>