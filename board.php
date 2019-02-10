<?php
session_start();
$error='';
$dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
}
if (isset($_GET['logout'])||!isset($_SESSION['username'])) {
  session_destroy();
  header("Location: login.php");
}
if (isset($_GET['msg'])) {
  if ($_GET['msg']=='') {
    $error="Enter message";
  }
  else {
    // code...
  $id=uniqid();
  $postedby=$_SESSION['username'];
  // $datetime=NOW();
  $msg=$_GET['msg'];
  if (!isset($_GET['replyto'])) {
    $stmt = $dbh->prepare("INSERT INTO posts (id,replyto,postedby,datetime,message) VALUES ('$id',NULL,'$postedby',NOW(),'$msg')");
  }
  else {
    $replyto=$_GET['replyto'];
    $stmt = $dbh->prepare("INSERT INTO posts (id,replyto,postedby,datetime,message) VALUES ('$id','$replyto','$postedby',NOW(),'$msg')");

  }

  $stmt->execute();
  header("Location: board.php");
}
}
 ?>
<html>
<head><title>Message Board</title></head>
<body>
  <?php  ?>
  <div class="" style="width:90%; margin:auto; background-color:#009dff; height:50px; border-radius:10px;">
    <p style="display:inline; float:left; margin-left:25px; font-weight:bold; color:white"><?php echo $_SESSION['fullname'];  ?></p>
    <p style="display:inline; float:right; margin-right:25px; font-weight:bold; color:white"><a href='board.php?logout=1' style="color:white">logout</a></p>
  </div>
  <div class="" style="width:70%; height:70vh; overflow-y:scroll; background-color:#d3f2ec;margin:auto; margin-top:30px">
    <?php
      $stmt1 = $dbh->prepare('SELECT * FROM posts ORDER BY datetime DESC');
      $stmt1->execute();
      while ($row = $stmt1->fetch()) {
          $name=$row["postedby"];
          $stmt2=$dbh->prepare("SELECT username,fullname FROM users WHERE username='$name'");
          $stmt2->execute();
          $row1=$stmt2->fetch();
          echo "<div style='width:100%; height:300px; margin-bottom:20px;'>";
          if ($name==$_SESSION['username']) {
            echo "<div style='width:90%; background-color:#15c6a0; text-align:center; height:100%; border-radius:10px; margin:auto'>";
          }
          else {
              echo "<div style='width:90%; background-color:#b7b75f; text-align:center; height:100%; border-radius:10px; margin:auto'>";
          }
          // echo "<div style='width:50%; float:left'>";
          echo "<p style='background-color:white; width:80%; margin:auto;margin-bottom:40px; margin-top:10px;padding:5px;border-radius:10px; font-weight:bold'>".$row['message']."</p>";
          echo "<p>ID: ".$row['id']."</p>";
          echo "<p>PostedBy: ".$row['postedby']."</p>";
          echo "<p>FullName: ".$row1['fullname']."</p>";
          echo "<p>".$row['datetime']."</p>";
          if (!is_null($row['replyto'])) {
            echo "<p>ReplyTo: ".$row['replyto']."</p>";
          }
          echo '<button type="submit" name="replyto" form="chat" value="'.$row['id'].'">reply</button>';
          echo "</div>";
          echo"</div>";
        }
    ?>
  </div>
  <div class="" style="width:60%;margin:auto; margin-top:20px; text-align:center; height:15vh; background-color:#d3f2ec">
    <form id="chat" action="board.php" method="GET">
      <textarea name="msg" rows="8" cols="50" placeholder="enter your message"></textarea>
      <input type="submit" name="" value="new post">
    </form>
  </div>
  <div class="" style="text-align:center">
    <p><?php if (!$error==''){
      echo $error;
    }?>
</p>
  </div>
<!-- <?php
// error_reporting(E_ALL);
// ini_set('display_errors','On');
//
// try {
//   $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
//   print_r($dbh);
//   $dbh->beginTransaction();
//   $dbh->exec('delete from users where username="smith"');
//   $dbh->exec('insert into users values("smith","' . md5("mypass") . '","John Smith","smith@cse.uta.edu")')
//         or die(print_r($dbh->errorInfo(), true));
//   $dbh->commit();
//
//   $stmt = $dbh->prepare('select * from users');
//   $stmt->execute();
//   print "<pre>";
//   while ($row = $stmt->fetch()) {
//     print_r($row);
//   }
//   print "</pre>";
// } catch (PDOException $e) {
//   print "Error!: " . $e->getMessage() . "<br/>";
//   die();
// }
?> -->
</body>
</html>
