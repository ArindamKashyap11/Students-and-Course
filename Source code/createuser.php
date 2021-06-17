<?php
$msg = "";
if (empty($_POST)) {
    $msg = "FIRST TIME LOAD";
} else {
    if(isset($_POST['loginUser'])){
        header("Location:login.php");
    }

    $un = $_POST['username'];
    $pw = $_POST['pwd'];

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

    if($un=="" and $pw=="")
      {
          $msg="Please enter proper username and pasword";
      }
    else{
    //create a statement for the sql command to execute
    $q = "insert into user_details(user_name,pwd)values('" . $un . "','" . $pw . "')";
    //echo($q);
    $statement = $conn->prepare($q);
    try {
        $statement->execute();
        $msg = "Account created,go to login page";
    } catch (PDOException $err) {
        $msg = "Account already exist";
    }
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
    <title>CREATE USER</title>
</head>

<body>

    <form method="POST" action="createuser.php">

        <div class="container" style="padding:20%">
            <div class="row rowspace">
                <div class="col">
                    <h4 class="textcolor">USERNAME</h4>
                </div>

            </div>

            <div class="row rowspace">
                <div class="col">
                    <input class="fulltextbox" type="text" name="username">
                </div>

            </div>

            <div class="row rowspace">
                <div class="col">
                    <h4 class="textcolor">PASSWORD</h4>
                </div>

            </div>

            <div class="row rowspace">
                <div class="col">
                    <input class="fulltextbox" type="password" name="pwd">
                </div>

            </div>

            <div class="row rowspace">
                <div class="col">
                    <input class="btn btn-info" type="submit" name="create_User" value='CREATE USER'>
                </div>
                <div class="col btnright">
                    <input class="btn btn-info" type="submit" name="loginUser" value='LOGIN'>
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