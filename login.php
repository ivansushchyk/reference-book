<?php
session_start();
require('connect.php');
?>

<!DOCTYPE html>
<html>
 <head>
  <meta charset="utf-8" />
  <title>Reference book</title>
  <link rel="stylesheet" href="style.css">
 </head>
 <body>
<h1> Login </h1>
<div class="test">

  <form method="post" action="cheking_account.php">
      <input type="text" placeholder="Account name" name="login"> </br>
      <input type="password" placeholder="Password" name="password"> </br>
      <br>
      <button type="submit"> Login </button>
  </form>
<?php echo $_SESSION['message']; ?>
 </br>
Don't have account? <a class="ref" href="registration.php">Registration</a>



</html>
