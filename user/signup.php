

<?php

$error =$_SESSION['signup_error'] ?? [];
$old = $_SESSION['signup_input'] ?? [];

$sucess =   $_SESSION['sucess'] ?? null;
$fail =   $_SESSION['failed'] ?? null;
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <?php
include(__DIR__ . '/../main/common.php');
include(__DIR__ . '/../server/db.php');

  ?>

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
input{
  color:#fff;
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
      <form method="post"  action="../server/request.php">
        <h4 class="text-center mb-4 text-white">Create Account</h4>
        <?php if (isset($sucess)): ?>
    <div style="color: green; font-size: 0.9em;"><?= $sucess?></div>
      <meta http-equiv="refresh" content="3;url=../index.php"> <!--- for refresh and redirect after some time ---->

  <?php endif; ?>
       <?php if (isset($fail)): ?>
    <div style="color: red; font-size: 0.9em;"><?= $fail?></div>
  <?php endif; ?>
        <div class="mb-3">
          <label for="username" class="form-label text-white">Username</label>
          <input type="text" name="username" value="<?= htmlspecialchars($old['username'] ?? '') ?>" class="form-control input-dark" id="username" placeholder="Enter username" required>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label text-white">Email address</label>
          <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>"    class="form-control input-dark" id="email" placeholder="Enter email" required>
             <?php if (isset($error['email'])): ?>
    <div style="color: red; font-size: 0.9em;"><?= $error['email'] ?></div>
  <?php endif; ?>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label text-white">Password</label>
          <input type="password"  name="password" value="<?= htmlspecialchars($old['password'] ?? '') ?>" class="form-control input-dark" id="password" placeholder="Enter password" required>
            <?php if (isset($error['password'])): ?>
    <div style="color: red; font-size: 0.9em;"><?= $error['password'] ?></div>
  <?php endif; ?>

        </div>

        <div class="mb-4">
          <label for="confirmPassword" class="form-label text-white">Repeat Password</label>
          <input type="password" name="reppass" value="<?= htmlspecialchars($old['reppass'] ?? '') ?>"  class="form-control input-dark" id="confirmPassword" placeholder="Repeat password" required>
                    <?php if (isset($error['reppass'])): ?>
    <div style="color: red; font-size: 0.9em;"><?= $error['reppass'] ?></div>
  <?php endif; ?>
    
    
        </div>
       <p> Already have an account? <a href="?login=true"><span>Login</span></a></p>
        <div class="d-grid">

          <button type="submit" name="signup" value="submit" class="btn btn-cyan">Submit</button>
        </div>
      </form>
    </div>
  </div>
  </div>



</body>
</html>