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
$conn="";
try {
  //$conn = new PDO("mysql:host=$servername;port=3307;dbname=mca2nd", $username, $password);
  $conn = new PDO("mysql:host=$servername;dbname=mca2nd", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 // echo "Connected successfully ";
} catch(PDOException $e) {
  // echo "Connection failed: " . $e->getMessage();
}  

//WE WANT A LIST OF ALREADY SELECTED COURSES
$q="select * from student_course where user_id='".$un."'";
$s=$conn->prepare($q);
$s->execute();
$alreadySelectedCourses=$s->fetchAll(PDO::FETCH_ASSOC);

if(!empty($_POST))
 {
    $q="DELETE from student_course where user_id='".$un."'";
    $sta=$conn->prepare($q);
    try{
     $sta->execute();
    }
    catch(PDOException $EEE){
 
    }
   if(!empty($_POST['course']))
      {
   $selectedCourses=$_POST['course'];
   $q="DELETE from student_course where user_id='".$un."'";
   $sta=$conn->prepare($q);
   try{
    $sta->execute();
   }
   catch(PDOException $EEE){

   }

   for($i=0;$i<count($selectedCourses);$i++)
     {
        // echo($selectedCourses[$i]);
        //insert this info into the database
        $code=$selectedCourses[$i];
        $q="insert into student_course(course_code,user_id)values('".$code."','".$un."')";
        $sta=$conn->prepare($q);
       try{
        $sta->execute();
       }
       catch(PDOException $EEE){

       }
    }
      }
    header("Location:home.php");
    die;
}
 else{

 } 
$r="HELLO";



$q="select * from course_details";

$s=$conn->prepare($q);
$s->execute();

$tab=$s->fetchAll(PDO::FETCH_ASSOC);

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
    <th scope="col">SELECT</th>
  </tr>
</thead>
<tbody>';

for($i=0;$i<count($tab);$i++)
   {
       $check='';
       $slno=$i+1;
       $code=$tab[$i]['course_code'];
       $cname=$tab[$i]['course_name'];
       $l=$tab[$i]['l'];
       $t=$tab[$i]['t'];
       $p=$tab[$i]['p'];
       $cr=$tab[$i]['cr'];

       for($j=0;$j<count($alreadySelectedCourses);$j++)
         {
             if($alreadySelectedCourses[$j]['course_code']==$code)
             {
             $check='checked';
             break;
             }
        }
       $r=$r.'<tr>
    <td scope="col">'.$slno.'</td>
    <td scope="col">'.$code.'</td>  
    <td scope="col">'.$cname.'</td>
    <td scope="col">'.$l.'</td>
    <td scope="col">'.$t.'</td>
    <td scope="col">'.$p.'</td>
    <td scope="col">'.$cr.'</td>
    <td scope="col"><input class="form-check-input" type="checkbox" name="course[]" value="'.$code.'" '.$check.' </td>
  </tr>';
 }

 $r=$r.'<tr>
 <td colspan="7"></td>
 <td><input class="btn btn-success" type="submit" name="okbutton" value="OK"></td>
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
    <title>SELECT COURSES</title>
</head>

<body>
     <div class="container">
        <div class="row">
          <div class="col d-flex justify-content-between bg-info" style="padding: 2%;">
              <h1 class="text-white"><?php echo("Welcome " .$fname." ".$mname." ".$lname."") ?> </h1>
          </div>
        </div>
        <div class="row">
           <div class="col">
           <form action="selectcourses.php" method="POST">
           <?php echo $r ?>
           </form>
           </div>
        </div>

     </div>
       
</body>

</html>