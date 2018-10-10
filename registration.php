<?php
require('connect.php');
session_start();
$message = null;
if (isset($_POST['login'],$_POST['password'])) {
  $login = strip_tags($_POST['login']);
  $password = strip_tags($_POST['password']);

  if ($login !== "" && $password !== "") {
    $isNameExistsQuery = $dbh->prepare('SELECT COUNT(*) FROM users WHERE name=:name');
    $isNameExistsQuery->bindParam(':name', $login);
    $isNameExistsQuery->execute();
    $countOfUsersWithSameName = $isNameExistsQuery->fetchColumn();

    if($countOfUsersWithSameName){
      $message = "Username has been already taken";
    } else {
      $insertQuery = $dbh->prepare('INSERT INTO users(name, pass) VALUES(:login,:pass)');
      $insertQuery->bindParam(':login', $login);
      $insertQuery->bindParam(':pass', $password);
      $insertQuery->execute();
      $selectUserIdQuery = $dbh->prepare('SELECT id from users where name=:login');
      $selectUserIdQuery->bindParam(':login', $login);
      $selectUserIdQuery->execute();
      $userId = $selectUserIdQuery->fetchColumn();
      $_SESSION['user_id'] = $userId;
      header('location:  index.php');
      exit();
    }

  } else {
    $message = "Fill login and password";
  }
}
?>


<!DOCTYPE html>
<html>
 <head>
  <meta charset="utf-8" />
  <title>Reference book</title>
  <link rel="stylesheet" href="style.css">
 </head>
 <body>

<h1> Registration </h1>
<div class="test">

  <form method="post" action="registration.php">
      <input type="text" placeholder="Account name" name="login"> </br>
      <input type="password" placeholder="Password" name="password"> </br>
      <br>
      <button type="submit"> Register <?php echo $_SESSION[count]; ?> </button>
  </form>
     <?php echo $message;?>

   </br>
Have a account? <a class="ref" href="registration_login_connect.php">Log in</a>
</html>
