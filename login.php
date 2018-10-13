<?php
session_start();
require('database_connect.php');
if ($_SESSION['user_id']) {
    header('Location: index.php');
    exit();
}

$message = null;
if ($_POST['login'] != "" && $_POST['password'] != "") {
    $username = $_POST['login'];
    $password = $_POST['password'];
    $checkPassAndLoginQuery = $dbh->prepare('SELECT id from users where name=:username and pass=:pass');
    $checkPassAndLoginQuery->bindParam(':username', $username);
    $checkPassAndLoginQuery->bindParam(':pass', md5($password));
    $checkPassAndLoginQuery->execute();
    $checkUserId = $checkPassAndLoginQuery->fetchColumn();
    if ($checkUserId) {
        $_SESSION['user_id'] = $checkUserId;
        header('Location: index.php');
        exit();
    } else {
        $message = 'Incorect login or password';
    }

}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Reference book</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1> Login </h1>
<div class="test">

    <form method="post" action="login.php">
        <input type="text" placeholder="Account name" name="login"> <br/>
        <input type="password" placeholder="Password" name="password"> <br/>
        <br>
        <button type="submit"> Login</button>
    </form>
    <?= htmlspecialchars($message) ?>
    <br/>
    Don't have account? <a class="ref" href="registration.php">Registration</a>


</html>
