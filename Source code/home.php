<?php
session_start();
$un=$_SESSION['name'];
$fname=$_SESSION['fname'];
$mname=$_SESSION['mname'];
$lname=$_SESSION['lname'];

if(isset($_POST['logout'])){
  header("Location:login.php");
  die;
}
elseif(isset($_POST['editp'])){
  $un=$_SESSION['name'];
  header("Location:editProfile.php");
  die;
}

//create a connection to mysql server
$servername = "localhost";
$username = "root";
$password = "";
$conn="";
try {
  //$conn = new PDO("mysql:host=$servername;port=3307;dbname=mca2nd", $username, $password);
  $conn = new PDO("mysql:host=$servername;dbname=mca2nd", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 // echo "Connected successfully ";
} catch(PDOException $e) {
  //echo "Connection failed: " . $e->getMessage();
}  
//create a statement for the sql command to execute
$q="select * from student_course where user_id='".$un."'";
$statement=$conn->prepare($q);
//execute the command
$statement->execute();

$r='<table class="table table-bordered">
<thead>
  <tr>
    <th scope="col">#</th>
    <th scope="col">COURSE CODE</th>
    <th scope="col">COURSE NAME</th>
    <th scope="col">L</th>
    <th scope="col">T</th>
    <th scope="col">P</th>
    <th scope="col">CR</th>
  </tr>
</thead>
<tbody>';

if($statement->rowCount()==0)
  {
      $r=$r.'<tr>
             <td></td>
             <td>NO COURSES TAKEN</td>
             </tr>';
  }
  else 
    {
        $rows=$statement->fetchAll(PDO::FETCH_ASSOC);
        for($i=0;$i<count($rows);$i++)
         {
          $ccode=$rows[$i]['course_code'];
$q="select * from course_details where course_code='".$ccode."'";
$s=$conn->prepare($q);
$s->execute();
$details=$s->fetchAll(PDO::FETCH_ASSOC);

$cname=$details[0]['course_name'];
$l=$details[0]['l'];
$p=$details[0]['p'];
$t=$details[0]['t'];
$cr=$details[0]['cr'];

          $r=$r.'<tr>
            <td>'.($i+1).'</td>
            <td>'.$ccode.'</td>
            <td>'.$cname.'</td>
            <td>'.$l.'</td>
            <td>'.$t.'</td>
            <td>'.$p.'</td>
            <td>'.$cr.'</td>
          </tr>';
         }
    }
         $r=$r.'<tr>
             <td colspan="6"></td>
             <td><input class="btn btn-info" type="submit" value="EDIT"></td>
             </tr>';

       $r=$r. '</tbody>
      </table>';
?>



<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
   <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous"> -->
   <link href="bootstrap.css" rel="stylesheet">
   <link href="ourcss.css" rel="stylesheet">
    <title>COURSES</title>
</head>

<body>
     <div class="container">
        <div class="raw">
        <form method="POST">
          <div class="col d-flex justify-content-between bg-info" style="padding:2%">
              <h1 class="text-white"><?php echo("Welcome " .$fname." ".$mname." ".$lname."") ?> </h1>
              <input class="btn btn-link text-white" type="submit" value="EDIT PROFILE" name="editp">
              <input class="btn btn-danger" type="submit" value="LOGOUT" name="logout">
          </div>
        </form>
        </div>
        <div class="row">
           <div class="col">
           <form action="selectcourses.php" method="$_POST">
        <?php echo $r ?>
           </form>
           </div>
        </div>

     </div>
       
</body>

</html>