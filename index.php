<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <?php 
     include('main/common.php'); 
    
    ?>
    <title>Quick-Ask</title>
    <style>
      body{
        background:#111;
      } 
      html, body {
  height: 100%;
  margin: 0;
  padding: 0;
  width: 100%;
}

    </style>
    <link rel="stylesheet" href="main/style.css">
</head>
<body>
<?php
session_start();
 include('main/header.php');
 ?>
 <main class="container">
  <?php

if (isset($_GET['signup']) && $_GET['signup'] == true && !isset($_SESSION['user']['username'])) {
    include('user/signup.php');
  
   

}

else if(isset($_GET['login'])&& $_GET['login']==true && !isset($_SESSION['user']['username'])){
   include('user/login.php');


 }

if (isset($_GET['ask-question']) && $_GET['ask-question'] === 'true') {
    if (!isset($_SESSION['user']['username'])) {
        header("Location: index.php?login=true&redirect=ask-question");
        exit;
    } else {
        include('main/ask.php');
    }
}


else if (isset($_GET['q-id'])) {
  $qid=$_GET['q-id'];
  include('main/question-detail.php');
}
else if (isset($_GET['account'])&& $_GET['account']==true){
include('main/account.php');
  
}

else if (isset($_GET['logout'])&& $_GET['logout']==true){
session_destroy();


header("Location:index.php");
exit;
  
}
else if (isset($_GET['search'])){
  $search = $_GET['search'];
  include('main/question.php');

}
else
{
    include('main/trending.php');
  include('main/question.php');


}

?>
</main>
      <!-- Footer -->
<!-- Improved Footer -->

  <?php
    unset($_SESSION['signup_error'], $_SESSION['signup_input'], $_SESSION['success'], $_SESSION['fail']);
    include('main/footer.php');
  ?>
 
</body>
</html>