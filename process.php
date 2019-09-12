<?php
$servername = "remotemysql.com:3306";
$username = "lEUlbC4qlA";
$password = "1e7Br5gkzl";
$db = 'lEUlbC4qlA';
////////////////////////////
function themsach($con, $ten, $nxb, $theloai, $tacgia) {
  $sql = "insert into Sach values ('" .$ten. "','". $nxb."','".
         $theloai . "','" . $tacgia. "');";

  $flagtacgia = testtontai($con,'Tacgia', $tacgia);
  $flagnxb = testtontai($con, 'Nxb', $nxb);
  $flagtheloai = testtontai($con, 'Theloai', $theloai);
  /*echo 'nxb';
  var_dump($flagnxb);
  echo 'theloai';
  var_dump($flagtheloai);
  echo 'tacgia';
  var_dump($flagtacgia); */


  if ($con->query($sql) === TRUE) {
    echo "New record created successfully <br/>";
  } else {
    echo "Error: " . $sql . "<br>" . $con->error;
  }
  echo "<br/><a href='index.php'>HOME</a>";
}
///////////////////

///////////////
function testtontai($con, $table, $bienso) {
  $sql = "select * from " . $table . " where ten = '" . $bienso . "';";
  $result = $con->query($sql);
  if ($result->num_rows > 0) {
    return True;

  } else {
      $sql2 = "insert into " . $table . " values ('" . $bienso . "');";
      if ($con->query($sql2) === TRUE) {
        echo "New record created successfully <br/>";
      } else {
        echo "Error: " . $sql2 . "<br>" . $con->error;
      }
      return False;
  }
}
////////////////////////

////////////////////////
function xoasach($con, $ten) {
  $sql = "delete from Sach where ten = '" . $ten . "';";
  if ($con->query($sql) === TRUE) {
    echo "deleted successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $con->error;
  }
  echo "<br/><a href='index.php'>HOME</a>";

}
////////////////////////

//////////////////////
function suathongtin($con, $ten, $nxb, $theloai, $tacgia) {
  $sql = "select * from Sach where ten = '" . $ten . "';";
  $result = $con->query($sql);
  if ($result->num_rows == 0) {
    echo 'sach khong ton tai de ma sua';
    return;
  }

  $sql = "update Sach set ";
  if ($nxb) $sql .= ("nxb = '" . $nxb. "',");
  if ($theloai) $sql .= ("theloai = '" . $theloai . "',");
  if ($tacgia) $sql .= ("tacgia = '" . $tacgia . "',");
  $sql = substr($sql, 0, -1);
  $sql .= " where ten = '" . $ten. "';";
  //echo $sql;

  $flagtacgia = testtontai($con,'Tacgia', $tacgia);
  $flagnxb = testtontai($con, 'Nxb', $nxb);
  $flagtheloai = testtontai($con, 'Theloai', $theloai);

  if ($con->query($sql) === TRUE) {
    echo "changed successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $con->error;
  }
  echo "<br/><a href='index.php'>HOME</a>";

}
///////////////////////////////////////////////////////////////
function lietke($con, $start, $phrase, $how, $action) {
  //$sql_count = "SELECT count(ten) FROM Sach ";


  $per = 2;
  $search = isset($phrase) && isset($how);
  //var_dump($search);
  $ending = "limit " . $per . " offset " . $start;
  if ($search) $sql= "SELECT Sach.* FROM Sach inner join " . ucfirst($how) .
    " on Sach." . strtolower($how) . " = ".ucfirst($how). ".ten ". " where ". ucfirst($how) . ".ten = '". $phrase . "' " . $ending;
  else $sql= "SELECT * FROM Sach " . $ending;
  //echo $sql;
  //echo "<br/>";
  //echo $start;


  $result = $con->query($sql);
  if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      //var_dump($row);
        echo "Ten: " . $row['ten'] . " ----- " . "Nxb: " . $row['nxb'] . " ----- ".
          "The loai: " . $row['theloai'] . " ----- " .
            "Tac gia: " . $row['tacgia'] . "<br/>";
    }
    echo "<br/><form action='process.php' method='post'>".
      "<input type='hidden' name='action' value='".$action."' />".
      "<input type='hidden' name='start' value=" . ($start+$per) ." />".
      "<input type='hidden' name='phrase' value='" . $phrase ."' />".
      "<input type='hidden' name='how' value='" . $how . "' />'".
      "<input type='submit' value='MORE' />".
    "</form>";
  } else {
      echo "HET";
  }
  echo "<br/><a href='index.php'>HOME</a>";
}

//////////////////////////////////////////////////////////////
$con = new mysqli($servername, $username, $password, $db);

if ($con->connect_error) {
  die("failed: ". $con->connect_error);
}
//echo 'connected <br/>';

switch ($_POST['action']) {
  case "add":
    echo 'adding<br/>';
    themsach($con, $_POST['ten'], $_POST['nxb'],
          $_POST['theloai'], $_POST['tacgia']);
    break;
  case "delete":
    echo 'deleting<br/>';
    xoasach($con, $_POST['ten']);
    break;
  case "change":
    echo 'changing<br/>';
    suathongtin($con, $_POST['ten'], $_POST['nxb'],
        $_POST['theloai'], $_POST['tacgia']);
    break;
  case "search":
    echo 'searching <br/>';
    //var_dump($_POST);
    lietke($con, $_POST['start'], $_POST['phrase'], $_POST['how'], 'search');
    break;
  case "list":
    echo "listing <br/>";
    //var_dump($_POST);

    lietke($con, $_POST['start'], NULL, NULL, 'list');
    break;


}




?>
