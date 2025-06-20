
<?php


$error =$_SESSION['error'] ?? [];
$old = $_SESSION['ask_input'] ?? null;
$sucess = $_SESSION['success_ques'] ?? null;

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <?php
   include('common.php');
 

   
   ?>


   <style>
    body{
        background:#111;
        color:#fff;
        width: 100%;
    }
   .btn{
        background:#00ffff;
        color:#111;
        border:none;
        transition: transform 0.5s ease;

    }
    .btn:hover{
        transform:scale(1.1);
    }
   </style>
</head>
<body>
 <form method="POST" action="../server/request.php"  class="p-4 rounded" style="background-color: #111; color: white; max-width: 700px; margin: auto;">
  <h3 class="mb-4 text-center" style="color: #00ffff;">Ask a Question</h3>
   <?php if (isset($sucess)): ?>
    <div style="color: green; font-size: 0.9em;"><?= $sucess ?></div>
    <meta http-equiv="refresh" content="3;url=../main/index.php">
    <?php unset($_SESSION['success_ques']); ?>
<?php endif; ?>


     <?php if (!empty($error)): ?>
    <div style="color: red; font-size: 0.9em;">
        <?php foreach ($error as $e): ?>
            <div><?= htmlspecialchars($e) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

  
  <!-- Title -->
  <div class="mb-3">
    <label for="title" class="form-label">Title</label>
    <input type="text" name="title"  value="<?= htmlspecialchars($old['title'] ?? '') ?>" id="title" class="form-control input-dark" required>
  </div>

  <!-- Description -->
  <div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea name="description" id="description" rows="6" value="<?= htmlspecialchars($old['description'] ?? '') ?>" class="form-control input-dark" required></textarea>
  </div>

  <!-- Hashtags -->
  <div class="mb-3">
    <label for="hashtags" class="form-label">Hashtags (comma separated, don't include #)</label>
    <input type="text" name="hashtags" value="<?= htmlspecialchars($old['hastags'] ?? '') ?>"id="hashtags" class="form-control input-dark" placeholder="e.g. php,sql,login">
  </div>

  <!-- Optional Image -->


  <!-- Submit -->
  <div class="d-grid">
    <button type="submit" value="submit" name="ask"  class="btn">Submit Question</button>
  </div>
</form>

</body>
</html>