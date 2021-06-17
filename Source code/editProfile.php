<?php

session_start();
$un=$_SESSION['name'];
$fname=$_SESSION['fname'];
$mname=$_SESSION['mname'];
$lname=$_SESSION['lname'];
//create a connection to mysql server
$servername = "localhost";
$username = "root";
$password = "";
$conn = "";
try {
   //$conn = new PDO("mysql:host=$servername;port=3307;dbname=mca2nd", $username, $password);
  $conn = new PDO("mysql:host=$servername;dbname=mca2nd", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully ";
} catch (PDOException $e) {
    //echo "Connection failed: " . $e->getMessage();
}

if(empty($_POST)){
    $q="select * from user_details where user_name='".$un."'";
    $s=$conn->prepare($q);
    $s->execute();
    $details=$s->fetchAll(PDO::FETCH_ASSOC);
    $rollno=$details[0]['rollno'];
    $fname=$details[0]['fname'];
    $mname=$details[0]['mname'];
    $lname=$details[0]['lname'];
}
else
  {
    if(isset($_POST['cancel'])){
        header("Location:home.php");die;
    }
    $rollno=$_POST["rollno"];
    $fname=$_POST["fname"];
    $mname=$_POST["mname"];
    $lname=$_POST["lname"];
   $q="update user_details set rollno='".$rollno."',fname='".$fname."',mname='".$mname."',lname='".$lname."' where user_name='".$un."'"; 
   $sta=$conn->prepare($q);
    $sta->execute();
    $_SESSION['fname']=$fname;
   $_SESSION['mname']=$mname;
   $_SESSION['lname']=$lname;
    header("Location:home.php");
    die;
   // echo("Finished successfully");
 }
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
    <title>PROFILE</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col d-flex justify-content-between bg-info" style="padding: 2%;">
                <h1 class="text-white"><?php echo ("Welcome " .$fname." ".$mname." ".$lname."") ?> </h1>
            </div>
        </div><br><br><br>
        <div class="row rowspace">
            <div class="col-sm-3">
                <!-- 2 empty sections in the left -->
            </div>
            <div class="col-md-6 col-md-offset-6">
                <form method="POST" action="editProfile.php">
                    <div class="form-group row rowspace ">
                        <label for="rollno">ROLL NO:</label>
                        <input type="text" class="form-control fulltextbox" name="rollno" value=<?php echo($rollno) ?>>
                    </div>
                    <div class="form-group row rowspace">
                        <label for="fname">FIRST NAME</label>
                        <input type="text" class="form-control fulltextbox" name="fname" value=<?php echo($fname) ?>>
                    </div>
                    <div class="form-group row rowspace">
                        <label for="mname">MIDDLE NAME</label>
                        <input type="text" class="form-control fulltextbox" name="mname" value=<?php echo($mname) ?>>
                    </div>
                    <div class="form-group row rowspace">
                        <label for="lname">LAST NAME</label>
                        <input type="text" class="form-control fulltextbox" name="lname" value=<?php echo($lname) ?>>
                    </div>
                    <div class="row rowspace">
                        <div class="col">
                            <input class="btn btn-light btn-outline-dark" type="submit" name="cancel" value='CANCEL'>
                        </div>
                        <div class="col btnright">
                            <input class="btn btn-light btn-outline-dark" type="submit" name="done" value='DONE'>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <!-- 2 empty sections in the left -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>