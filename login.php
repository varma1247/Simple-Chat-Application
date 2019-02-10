<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors','On');
$error='';

try {
  $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  // print_r($dbh);
  // $dbh->beginTransaction();
  // $dbh->exec('delete from users where username="smith"');
  // $dbh->exec('insert into users values("smith","' . md5("mypass") . '","John Smith","smith@cse.uta.edu")')
  //       or die(print_r($dbh->errorInfo(), true));
  // $dbh->commit();
  // $stmt = $dbh->prepare('select * from users');
  // $stmt->execute();
  // print "<pre>";
  // while ($row = $stmt->fetch()) {
  //   print_r($row);
  // }
  // print "</pre>";

  if (isset($_POST['username'])&&isset($_POST['password'])) {
    if ($_POST['username']==''||$_POST['password']=='') {
      $error="Enter all details";
    }
    else {
      $uname=$_POST['username'];
      $password=md5($_POST['password']);
      $stmt = $dbh->prepare("SELECT * FROM users WHERE username='$uname'AND password='$password'");
      $stmt->execute();
      $row = $stmt->fetch();
      if ($row) {
        $_SESSION['username']=$uname;
        $_SESSION['fullname']=$row['fullname'];
        header("Location: board.php");
      }
      else {
        $error='incorrect details';
      }
  }
}
} catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Message Board</title>
    <style media="screen">
    div{
       width:30%;
       margin:auto;
       text-align:center;
       background-color:white;
       margin-top: 200px;
       padding: 10px;
       border-radius: 10px;
       box-shadow: 10px 7px black;
    }
      input[type=text],input[type=password]{
        display:inline-block;
        width:70%;
        margin-top: 20px;
        margin-bottom: 10px;
        padding: 15px;
        font-size: 15px;
        border-radius: 10px;
        text-align: center;
        border-radius: 10px;
      }
      input[type=submit]{
        display:block;
        text-align:center;
        margin:auto;
        margin-bottom: 30px;
        padding: 10px;
        font-size: 15px;
        background-color: #168cff;
        border-radius: 10px;
        font-weight: bold;
        color:white
      }
    </style>
  </head>
  <body style="background-color:#9fb757">
    <div class="" style="">
    <form class="" action="login.php" method="post" style="text-align:center">
      <input type="text" name="username" value="" placeholder="username">
      <input type="password" name="password" value="" placeholder="password">
      <input type="submit" value="login">
    </form>
    </div>
      <p style="text-align:center">
        <?php if (!$error=='') {
          echo $error;
        }
       ?>
      </p>
  </body>
</html>
