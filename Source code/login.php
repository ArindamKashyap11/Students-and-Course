<?php

$isfirsttime = 1;
$msg = " ";
if (empty($_POST)) {
    $msg = "First Time";
    $isfirsttime = 1;
} else {
    if (isset($_POST['new_user'])) {
        header("Location:createuser.php");
        die;
    }
    $isfirsttime = 0;
    $isfound = 0;

    $un = $_POST["t1"];
    $pw = $_POST["t2"];
    if($un=="ADMIN" && $pw="ADMIN")
      {
        header("Location:gradeEntryForm.php");
        die;
      }
    //create a connection to mysql server
    $servername = "localhost";
    $username = "root";
    $password = "";
    $conn = "";
    try {
       //$conn = new PDO("mysql:host=$servername;port=3307;dbname=mca2nd", $username, $password);
            $conn = new PDO("mysql:host=$servername;dbname=mca2nd", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "Connected successfully ";
    } catch (PDOException $e) {
        //echo "Connection failed: " . $e->getMessage();
    }
    //create a statement for the sql command to execute
    $q = "select * from user_details where user_name='" . $un . "' and pwd='" . $pw . "'";
    $statement = $conn->prepare($q);

    //execute the command
    $statement->execute();
    if ($statement->rowCount() == 0) {
        $isfound = 0;
    } else {
        $isfound = 1;
    }
    if ($isfound == 1) {
        $msg = "Welcome" . $un;
        session_start();
        $_SESSION['name'] = $un;
        $q = "select fname,mname,lname from user_details where user_name='" . $un . "'";
        $statement = $conn->prepare($q);
        $statement->execute();
        $details = $statement->fetchAll(PDO::FETCH_ASSOC);
        $_SESSION['fname'] = $details[0]['fname'];
        $_SESSION['mname'] = $details[0]['mname'];
        $_SESSION['lname'] = $details[0]['lname'];

        header("Location:home.php");
        die;
    } else {
        $msg = "Invalid User name and Password";
    }
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
    <title>LOGIN</title>
</head>

<body>

    <form method="POST" action="login.php">

        <div class="container" style="padding:20%">
            <div class="row rowspace">
                <div class="col">
                    <h4 class="textcolor">USERNAME</h4>
                </div>

            </div>

            <div class="row rowspace">
                <div class="col">
                    <input class="fulltextbox" type="text" name="t1">
                </div>

            </div>

            <div class="row rowspace">
                <div class="col">
                    <h4 class="textcolor">PASSWORD</h4>
                </div>

            </div>

            <div class="row rowspace">
                <div class="col">
                    <input class="fulltextbox" type="password" name="t2">
                </div>

            </div>

            <div class="row rowspace">
                <div class="col">
                    <input class="btn btn-info" type="submit" name="new_user" value='NEW USER'>
                </div>
                <div class="col btnright">
                    <input class="btn btn-info" type="submit" name="login" value='LOGIN'>
                </div>
            </div>
            <div class="row rowspace">
                <div class="col">
                    <h1><?php echo ($msg) ?> </h1>
                </div>

            </div>
        </div>
    </form>
</body>

</html>