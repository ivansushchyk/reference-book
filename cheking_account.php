<?php
require('connect.php');
session_start();


if ($_POST['login'] != "" && $_POST['password'] != "") {
$userName = strip_tags($_POST['login']);
$password = strip_tags($_POST['password']);
$chekingPassAndLoginQuery = $dbh->prepare('SELECT id from users where name=:username and pass=:pass');
$chekingPassAndLoginQuery->bindParam(':username',$userName);
$chekingPassAndLoginQuery->bindParam(':pass',$password);
$chekingPassAndLoginQuery->execute();
$checkUserId = $chekingPassAndLoginQuery->fetchColumn();
      if ($checkUserId){
        $_SESSION['user_id'] = $checkUserId;
        header('location: index.php');
        exit();
      } else {
        $_SESSION['message'] = "Incorect login or password";
          header('location:  login.php');
          exit();
      }

}
else {
  header('location:  login.php');
  $_SESSION['message'] = "Fill the empty field";
  exit;
}


 ?>
