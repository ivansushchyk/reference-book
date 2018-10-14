<?php
session_start();
if (!$_SESSION['user_id']) {
    header('Location:  login.php');
    exit();
}
require('database_connect.php');
$selectUserData = $dbh->prepare('SELECT name FROM users where id=:user_id');
$selectUserData->bindParam(':user_id', $_SESSION['user_id']);
$selectUserData->execute();
$userName = $selectUserData->fetchColumn();
if (isset($_POST['name'], $_POST['number'])) {
    if (strlen($_POST['name']) < 2 or strlen($_POST['name']) > 15) {
        $message = "Length of name must be bigger than 2 and less than 15";
    } elseif (strlen($_POST['number']) !== 10) {
        $message = "Length of number must be 10";
    } elseif (!ctype_digit($_POST['number'])) {
        $message = "Phone number must be recorded only numerically";
    } else {
        $inserContactQuery = $dbh->prepare('INSERT INTO contacts VALUES(NULL,:name,:number,:user_id)');
        $inserContactQuery->bindParam(':name', $_POST['name']);
        $inserContactQuery->bindParam(':number', $_POST['number']);
        $inserContactQuery->bindParam(':user_id', $_SESSION['user_id']);
        $inserContactQuery->execute();
    }
}
if ($_POST['id'] != 0) {
    $deleteContactQuery = $dbh->prepare('DELETE FROM contacts where id = (:deleteId)');
    $deleteContactQuery->bindParam(':deleteId', $_POST['id']);
    $deleteContactQuery->execute();
}

if (isset($_GET['search_name']) && $_GET['search_name'] !== "") {
    $selectUserDate = $dbh->prepare("SELECT * FROM contacts where name LIKE :search_name and user_id = :user_id");
    $searchName = "%{$_GET['search_name']}%";
    $selectUserDate->bindParam(':search_name', $searchName);
    $selectUserDate->bindParam(':user_id', $_SESSION['user_id']);
    $selectUserDate->execute();
    $userContacts = $selectUserDate->fetchAll();
    $resultMessage = "Result of request {$_GET['search_name']}";
    $link = true;
} else {
    $selectContactsQuery = $dbh->prepare('SELECT * FROM contacts where user_id=:user_id');
    $selectContactsQuery->bindParam(':user_id', $_SESSION['user_id']);
    $selectContactsQuery->execute();
    $userContacts = $selectContactsQuery->fetchAll();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reference book</title>
    <style type="text/css">
        table {
            width: 800px;
            margin: auto;
        }

        td {
            text-align: center;
        }

        P {
            text-align: center
        }

        h1 {
            text-align: center
        }

        .search {
            text-align: right;
        }
    </style>
</head>
<a class="ref" href="logout.php">Logout</a>
<body>
<div class="search">
    <form method="get" action="index.php?search_contact=<?= $_GET['search_name'] ?>">
        <input type="text" placeholder="Search contact" name="search_name">
        <input type="submit" value="Find a contact">
    </form>
</div>
<h1>Welcome <?= htmlspecialchars($userName) ?></h1>
<form action="index.php" method="post">
    <p>Name contact:<input type="text" name="name"/></p>
    <p>Number telephone:<input type="text" name="number"/></p>
    <p><input type="submit" value='Add to contacts'></p>
</form>
<p> <?= htmlspecialchars($message) ?>
<p>
<hr align="center" width="1300" color="Black"/>
<h1> <?= $resultMessage ?> </h1>

<?php if ($userContacts): ?>
    <table>
        <tr>
            <th>Name</th>
            <th>Number</th>
            <th></th>
            <th></th
        </tr>
        <?php foreach ($userContacts as $userContact): ?>
            <tr>
                <td>  <?= htmlspecialchars($userContact['name']) ?> </td>
                <td>   <?= htmlspecialchars($userContact['number']) ?> </td>
                <td>
                    <a href="edit.php?contact_id=<?= htmlspecialchars($userContact['id']) ?>"> Edit </a>
                </td>
                <td>
                    <form action="index.php" method="post">
                        <input type="hidden" id="id" name="id" value="<?= htmlspecialchars($userContact['id']) ?>">
                        <button type="submit">Delete contact</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php if ($link): ?>
        <p><a href="index.php"> Back to main
                <page></page>
            </a></p>
    <?php endif; ?>
<?php else: ?>
    <h2 style="text-align: center">No contacts </h2>
<?php endif; ?>
