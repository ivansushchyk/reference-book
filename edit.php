<?php
session_start();
require('database_connect.php');
require('functions.php');
$selectEditedContactQuery = $dbh->prepare('SELECT * FROM contacts where id=:id');
$selectEditedContactQuery->bindParam(':id', $_GET['contact_id']);
$selectEditedContactQuery->execute();
$editedContact = $selectEditedContactQuery->fetch(PDO::FETCH_ASSOC);
if (!($editedContact)) {
    http_response_code(404);
    echo 'Contact not found';
    die;
} elseif ($_SESSION['user_id'] !== $editedContact['user_id']) {
    http_response_code(403);
    echo 'Acces denied';
    die;
}
$errorMessage = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errorMessage = validateContactData($_POST['name'], $_POST['number']);
    if (!$errorMessage) {
        $updateContactQuery = $dbh->prepare('UPDATE contacts SET name=:name, number=:number where id=:id');
        $updateContactQuery->bindParam(':name', $_POST['name']);
        $updateContactQuery->bindParam(':number', $_POST['number']);
        $updateContactQuery->bindParam(':id', $_GET['contact_id']);
        $updateContactQuery->execute();
        header('Location: index.php');
        exit();
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reference book</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<a class="ref" href="logout.php">Logout</a>
<h1>Editing contact</h1>
<hr align="center" width="1300" color="Black"/>
<form method="post">
    <p><input type="text" value="<?= htmlspecialchars($editedContact['name']) ?>" name="name">
        <input type="text" value="<?= htmlspecialchars($editedContact['number']) ?>" name="number">
        <input type="submit" value="Save">
    </p>
</form>
<p> <?= $errorMessage ?> </p>

<p><a href="index.php" class="button"> Back to the main page </a></p>
</body>
