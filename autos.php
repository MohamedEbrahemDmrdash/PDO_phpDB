<?php
require_once "bootstrap.php";
require_once "pdo.php";
// Demand a GET parameter
if ( ! isset($_GET['name']) || strlen($_GET['name']) < 1  ) {
    die('Name parameter missing');
}

// If the user requested logout go back to index.php
if ( isset($_POST['logout']) ) {
    header('Location: index.php');
    return;
}

$failure = false;
$success = false;


if ( isset($_POST['make']) && isset($_POST['year'])
     && isset($_POST['mileage'])) {
       if ( strlen($_POST['make']) < 1 ){
         $failure = "Make is Required";
       }else {
         if (is_numeric($_POST['mileage'])&&is_numeric($_POST['year'])) {
           error_log("year is a number ".$_POST['year']);
           error_log("Mileage is a number ".$_POST['mileage']);
           $sql = "INSERT INTO autos (make, year, mileage)
                     VALUES (:make, :year, :mileage)";
           $stmt = $pdo->prepare($sql);
           $stmt->execute(array(
               ':make' => htmlentities($_POST['make']),
               ':year' => htmlentities($_POST['year']),
               ':mileage' => htmlentities($_POST['mileage'])));
               $success = "Record Inserted";
       }else {
         $failure = "Mileage and Year must be numeric";
         error_log("year or mileage is not a number year=".$_POST['year']);
         error_log("Mileage or year is not a number mileage=".$_POST['mileage']);
       }
            }
}
if ( isset($_POST['delete']) && isset($_POST['auto_id']) ) {
$sql = "DELETE FROM autos WHERE auto_id = :zip";

$stmt = $pdo->prepare($sql);
$stmt->execute(array(':zip' => $_POST['auto_id']));
}

$stmt = $pdo->query("SELECT * FROM autos");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
<title>Mohamed Ibrahem PDO</title>
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
      if ( isset($_REQUEST['name']) ) {
          echo "<p>Tracking Autos for ";
          echo htmlentities($_REQUEST['name']);
          echo "</p>\n";
      }
      ?>
</h1>
<p>
   <?php
      // Note triple not equals and think how badly double
      // not equals would work here...
      if ( $failure !== false ) {
          // Look closely at the use of single and double quotes
          echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
      }
      if ( $success !== false ) {
          // Look closely at the use of single and double quotes
          echo('<p style="color: green;">'.htmlentities($success)."</p>\n");
      }
      ?>
</p>
<form method="post">
<p>Make:
<input type="text" name="make" size="60"></p>
<p>Year:
<input type="text" name="year"></p>
<p>Mileage:
<input type="text" name="mileage"></p>
<input type="submit" value="Add">
<input type="submit" name="logout" value="Logout">
</form>

<h2>Automobiles</h2>
<head></head><body><table border="3">
<tr><th >make</th>
<th >year</th>
<th > mileage </th>
<th > Delete </th></tr>
<?php
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
</div>
</body>
</html>
