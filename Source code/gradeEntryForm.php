<?php
session_start();
if (isset($_POST['btnLogout'])) {
  header('location:login.php');
  die;
}
$select = '';
if (isset($_POST['select_course'])) {
  $select = $_POST['select_course'];
}
$servername = "localhost";
$username = "root";
$password = "";
$conn = "";
try {
  //$conn = new PDO("mysql:host=$servername;port=3307;dbname=mca2nd", $username, $password);
  $conn = new PDO("mysql:host=$servername;dbname=mca2nd", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // echo "Database created successfully<br>";
} catch (PDOException $e) {
  //echo "Database created successfully" . "<br>" . $e->getMessage();
}
$q = "select * from course_details";
$statement = $conn->prepare($q);
$statement->execute();
$rows = $statement->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="bootstrap.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="ourcss.css">
  <title>Grade Entry Form|Admin</title>
</head>

<body>
  <div class="container">
    <div class="row">
      <div class="col d-flex justify-content-between bg-info" style="padding:2%;">
        <h3 class="text-white"> <?php echo ("GRADE ENTRY FORM"); ?> </h3>
        <form method="POST" action="gradeEntryForm.php">
          <input class="btn btn-danger" type="submit" value="LOGOUT" name="btnLogout">
        </form>
      </div>
    </div>
    <div class="row">
      <form action="gradeEntryForm.php" method="POST" id="grade_entry_form">
        <div class="col d-flex justify-content-between" style="padding: 2%;">
          <label for="course" style="margin: 1%;">SELECT COURSE</label>
          <select class="form-select form-select-sm" style="margin: 1%; width: 70%; height: 46%" name="select_course">
            <option <?php if ($select == '') {
                      echo 'selected';
                    } ?>>Select Course</option>
            <?php foreach ($rows as $output) { ?>
              <option name="etc" <?php if ($select == $output['course_code']) {
                                    echo 'selected';
                                  } ?> value="<?php echo $output['course_code'] ?>"><?php echo $output['course_code'] . ""?></option>
            <?php } ?>
          </select>
          <button class="btn btn-info" role="button" id="load_student" name="load_student" style="height: min-content; margin: 1%;">Load Student</button>
        </div>
    </div>
  </div>
  <div class="emp_tab m-auto" style="margin:20%; width: 70%;">
    <form action="gradeEntryForm.php" method="POST" id="grade_entry_form">
      <table class="table table-bordered mt-4">
        <tr>
          <!-- <th> Id </th>-->

          <th>
            <center>#</center>
          </th>
          <th>
            <center>NAME</center>
          </th>
          <th>
            <center>ROLL NO </center>
          </th>
          <th>
            <center>GRADE</center>
          </th>
        </tr>
        <?php
        if (isset($_POST['load_student'])) {
          $select = $_POST['select_course'];
          $q = "select * from student_course join user_details where student_course.user_id = user_details.user_name and student_course.course_code='" . $select . "'";
          $statement = $conn->prepare($q);
          $statement->execute();
        }
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        $index = 1;
        foreach ($rows as $res) {
        ?>
          <tr class="text-center">

            <td> <?php echo $index; ?> </td>
            <td> <?php echo $res['fname'] . " " . $res['mname'] . " " . $res['lname']; ?> </td>
            <td> <?php echo $res['rollno']; ?> </td>
            <td>
              <select name="grade-<?php echo $index; ?>">
               <!--<option value="">None</option>-->
               <option value="<?php echo $res['user_id']; ?>:none" <?php if ($res['grade'] == 'none') {
                                                                      echo 'selected';
                                                                    } ?>> none</option>
                <option value="<?php echo $res['user_id']; ?>:O" <?php if ($res['grade'] == 'O') {
                                                                      echo 'selected';
                                                                    } ?>> O</option>
                <option value="<?php echo $res['user_id']; ?>:A+" <?php if ($res['grade'] == 'A+') {
                                                                      echo 'selected';
                                                                    } ?>>A+</option>
                <option value="<?php echo $res['user_id']; ?>:A" <?php if ($res['grade'] == 'A') {
                                                                      echo 'selected';
                                                                    } ?>>A</option>
              </select>
            </td>
          </tr>
        <?php
          $index++;
        }
        ?>
      </table>
  </div>
  <div class="row">
    <div class="col text-left ml-3">
      <input type="text" name="total_records" value="<?php echo $index - 1; ?>" hidden>
      <button class="btn btn-info" role="button" type="submit" name="save_grades" style="margin-left: 1170px; width: 100px;">Save</button>
    </div>
  </div>
  </form>
  <?php
  if (isset($_POST['save_grades'])) {
    $select = $_POST['select_course'];
    $total_Records = $_POST['total_records'];
    $q = "select * from student_course join user_details where student_course.user_id = user_details.user_name and student_course.course_code='" . $select . "'";
    $statement = $conn->prepare($q);
    $statement->execute();
    $res = $statement->fetchAll(PDO::FETCH_ASSOC);
    $query = '';
    for ($i = 1; $i <= $total_Records; $i++) {
      $data = explode(':', $_POST['grade-' . $i]);
      $rollno = $data[0];
      $new_grade = $data[1];
      $query = "UPDATE student_course SET grade='" . $new_grade . "' WHERE user_id='" . $rollno . "' AND course_code='" . $select . "';";
      $statement = $conn->prepare($query);
      try {
        $result = $statement->execute();
      } catch (PDOException $eee) {
      }
    }
  }
  ?>

</body>

</html>