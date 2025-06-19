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
        background:black;
      } 
    </style>
</head>
<body>
<?php
session_start();
 include('main/header.php');
if (isset($_GET['signup']) && $_GET['signup'] == true && !isset($_SESSION['user']['username'])) {
    include('user/signup.php');
  
   

}

else if(isset($_GET['login'])&& $_GET['login']==true && !isset($_SESSION['user']['username'])){
   include('user/login.php');

   echo $_SESSION['id'];
 }

 if (isset($_GET['ask-question']) && $_GET['ask-question'] === 'true') {
    if (!isset($_SESSION['user']['username'])) {
        // User is not logged in – redirect or show login
        include('user/login.php');
    } else {
        // User is logged in – show ask form
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
  
  <?php
    unset($_SESSION['signup_error'], $_SESSION['signup_input'], $_SESSION['success'], $_SESSION['fail']);
  ?>
</body>
</html>