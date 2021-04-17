<?php

session_start();

require_once "pdo.php";

if ( ! isset($_SESSION['name']) ) {
    die('Not logged in');
}

if ( isset($_POST['delete']) && isset($_POST['auto_id']) ) {
$sql = "DELETE FROM autos WHERE auto_id = :zip";

$stmt = $pdo->prepare($sql);
$stmt->execute(array(':zip' => $_POST['auto_id']));
}

$stmt = $pdo->query("SELECT * FROM autos");
$_SESSION['rows'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ( isset($_POST['logout']) ) {
    header('Location: index.php');
    return;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Mohamed Ibrahem PDO 92a69ed3</title>
<style media="screen">
  th{
    text-align:center;
    color: red;
  }
  table td{
    text-align:center;
    height: 50px;
    width: 200px;
    vertical-align: center;
  }
</style>
</head>
<body>

<div class="container">
<h1>
   <?php
      if ( isset($_SESSION['name']) ) {
          echo "<p>Tracking Autos for ";
          echo htmlentities($_SESSION['name']);
          echo "</p>\n";
      }

      if ( isset($_SESSION['success']) ) {
        echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
        unset($_SESSION['success']);
      }
      ?>
</h1>
<h2>Automobiles</h2>
<head></head><body><table border="3">
<tr><th >make</th>
<th >year</th>
<th > mileage </th>
<th > Delete </th></tr>
<?php
$rows=$_SESSION['rows'];
foreach ( $rows as $row ) {
    echo "<tr><td>";
    echo($row['make']);
    echo("</td><td>");
    echo($row['year']);
    echo("</td><td>");
    echo($row['mileage']);
    echo("</td><td>");
    echo('<form method="post"><input type="hidden" ');
    echo('name="auto_id" value="'.$row['auto_id'].'">'."\n");
    echo('<input type="submit" value="Del" name="delete">');
    echo("\n</form>\n");
    echo("</td></tr>\n");
}
?>
</table>

<p>
<a href="add.php">Add New</a>
<a href="logout.php">Logout</a>
</p>

</div>
</body>
</html>
