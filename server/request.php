<?php 
session_start();
include ('db.php');
include('../main/common.php');
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup'])){
  
     $username= $_POST['username'];
      $password= $_POST['password'];
       $email= $_POST['email'];
        $reppass= $_POST['reppass'];



        $error=[];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $email_match = $stmt->fetch(PDO::FETCH_ASSOC);

    if($email_match){

        if($email_match['email']==$email){
            $error['email']="Email already exists";
        }

    }


        if(strlen($password)<6){

            $error['password']="Password should be of atleast 6 digit";
        }
        if($password!= $reppass){

            $error['reppass']="password doesn't match";
        }
        if (!empty($error)) {
           $_SESSION['signup_error']=$error;
           $_SESSION['signup_input']=$_POST;
           header("location: ../index.php?signup=true");
           exit;
        }

        $user = $conn->prepare("Insert into `users` (username,email,password) values(?,?,?)");


        $result = $user->execute([
            $username,
            $email,
            $password
        ]);

        
        $conn->lastInsertId();

        if ($result){
              
            $sucess = "Thanks for signing in ";
             $_SESSION['sucess'] = $sucess;

           $_SESSION['user'] = [
    "username" => $username,
    "email" => $email,
    "user_id" => $conn->lastInsertId()
];

             header("location: ../index.php?signup=true");
        }
        else{
        
             $_SESSION['failed'] = "Unable to  signing in ";
             exit;
        }
        








  
}elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];
    $error = [];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

   if ($user) {
        if ($password === $user['password']) {
        
            $_SESSION['sucess'] = "logged in";
            $_SESSION['user'] = ["username" => $user['username'], "email" => $email,"user_id"=>$user['id']];
            header("Location: ../index.php?login=true");
            exit;
        } else {
            $error['password'] = "Wrong password";
        }
    }
    
    else {
        $error['email'] = "Email doesn't exist";
    }

    if (!empty($error)) {
        $_SESSION['login_error'] = $error;
        $_SESSION['login_input'] = ['email' => $email];
        header("Location: ../index.php?login=true");
        exit;
    }

}


elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ask'])){

         $title=$_POST['title'];
         $descrip=$_POST['description'];
        
         $currentDate = date('Y-m-d');


    $user = $conn->prepare("Insert into `question` (title,	descriptation, date,user_id) values(?,?,?,?)");


        $result = $user->execute([
           $title,
           $descrip,
           $currentDate,
           $_SESSION['user']["user_id"]
        ]);

        if ($result) {
    // 1. Get inserted question ID
    $question_id = $conn->lastInsertId();

    // 2. Get hashtags from input (comma-separated)
    $rawTags = $_POST['hashtags'];  // e.g., "php, mysql ,webdev"
    
    // 3. Split into array and clean up each tag
    $tags = array_map('trim', explode(',', $rawTags)); // ['php', 'mysql', 'webdev']

    foreach ($tags as $tag) {
        if ($tag == '') continue; // Skip empty

        // 4. Check if hashtag already exists
        $stmt = $conn->prepare("SELECT id FROM hastag WHERE tag_name = ?");
        $stmt->execute([$tag]);
        $hashtag = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($hashtag) {
            $hashtag_id = $hashtag['id'];
        } else {
            // 5. Insert new hashtag
            $insert = $conn->prepare("INSERT INTO hastag (tag_name) VALUES (?)");
            $insert->execute([$tag]);
            $hashtag_id = $conn->lastInsertId();
        }

        // 6. Link question and hashtag
        $link = $conn->prepare("INSERT INTO question_hastag (ques_id, hastag_id) VALUES (?, ?)");
        $link->execute([$question_id, $hashtag_id]);
    }

    $_SESSION['success_ques'] = "Question posted successfully!";
    header("Location: ../index.php");
    exit;
}
else
{
    $_SESSION['error']="unable to post question";
           $_SESSION['ask_input']=$_POST;
           header("location: ../index.php?ask=true");
           exit;

}

   




}

