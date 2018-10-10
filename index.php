<?php
session_start();
if(!$_SESSION['user_id']){
  header('Location:  login.php');
  exit();
}
require('connect.php');

$selectAcountName = $dbh->prepare('SELECT name FROM users where id=:user_id');
$selectAcountName->bindParam(':user_id',$_SESSION['user_id']);
$selectAcountName->execute();
$userName = $selectAcountName->fetchColumn();
if (isset($_POST['name'],$_POST['number'])) {

      if (strlen($_POST['name']) < 2 or strlen($_POST['name']) > 15){
        $message = "Length of name must be bigger than 2 and less than 15";
      }

      elseif (strlen($_POST['number']) !== 10){
        $message = "Length of number must be 10";
      }
      elseif (!ctype_digit($_POST['number'])){
        $message = "Phone number must be recorded only numerically";

      }


      else {
       $inserContactQuery = $dbh->prepare('INSERT INTO contacts VALUES(NULL,:name,:number,:user_id)');
       $inserContactQuery->bindParam(':name', strip_tags($_POST['name']));
       $inserContactQuery->bindParam(':number',strip_tags($_POST['number']));
       $inserContactQuery->bindParam(':user_id',$_SESSION['user_id']);
       $inserContactQuery->execute();
      }
}







if ($_POST['id'] != 0) {

      $deleteContactQuery = $dbh->prepare('DELETE FROM contacts where id = (:deleteId)');
      $deleteContactQuery->bindParam(':deleteId',$_POST['id']);
      $deleteContactQuery->execute();
}
       $selectContactsQuery = $dbh->prepare('SELECT * FROM contacts where user_id=:user_id');
       $selectContactsQuery->bindParam(':user_id',$_SESSION['user_id']);
       $selectContactsQuery->execute();
       $userContacts = $selectContactsQuery->fetchAll();

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
            P { text-align: center }
           h1 { text-align: center }
          </style>
  </head>
  <a class="ref" href="logout.php">Logout</a>
              <body>
    <h1>Welcome <?php echo $userName; ?></h1>
    <form action="index.php" method="post">
          <p>Name contact:<input type="text" name="name" /></p>
          <p>Number telephone:<input type="text" name="number" /></p>
          <p><input type="submit" value='Add to contacts'></p>
</form>
          <p> <?php echo $message; ?> <p>
         <hr align="center" width="1300" color="Black" />

<?php
if($userContacts){

 ?>

<table>
    <tr>
        <th>Name contact</th>
        <th>Number contact</th>
        <th> </th>
        <th> </th>

    </tr> <?php
    foreach ($userContacts as $userContact)
    {
    ?>
    <tr>
    <form action="index.php" method="post">
        <td>  <?php echo $userContact['name']; ?> </td>
        <td>   <?php echo $userContact['number'];?> </td>
        <td>  <input type="submit" value="Delete contact"</td>
        <td>   <input type="hidden" id="id" name="id" value="<?php echo $userContact['id']; ?>" </td>
    </tr>
  </form> <?php } ?>


</table>
<?php } ?>
