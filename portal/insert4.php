<?php
include 'db_connection.php';
$array = [];

$ID = $_POST['id'];

$sql = "SELECT * FROM `users` where `user_id`='$ID'";
$query = mysqli_query($conn, $sql) or die("Query Unsuccessful");
$str = "";


while ($row = mysqli_fetch_assoc($query)) {

    $str = "{$row['employee_name']}";
}
array_push($array, $str);

date_default_timezone_set("Asia/karachi");
$name = $str;


$sign_in = date("h:i:sa");
$date = date("n, j, Y");
$emp_time = "";
$query = mysqli_query($conn, $sql) or die("Query Unsuccessful");

$row = mysqli_fetch_assoc($query);
$emp_time = $row['time_in'];

if ($sign_in > $emp_time) {
    $status = 'Late';
} else {
    $status = "On Time";
}

$activity = "";
$curr_date = "";
array_push($array, $sign_in);
$sql2 = "SELECT * FROM `signin` WHERE Name='$name'  ORDER BY auto DESC LIMIT 1;";
$result = mysqli_query($conn, $sql2);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while ($row = mysqli_fetch_assoc($result)) {
        $activity = $row["activity"];
        $curr_date = $row["Date"];
        array_push($array, null);
    }
}
$emp_timeout = '';
$query = mysqli_query($conn, $sql) or die("Query Unsuccessful");

$row = mysqli_fetch_assoc($query);
$emp_timeout = $row['time_out'];

if ($activity == "Signed in") {
    if ($emp_timeout < '04:00:00am' && $sign_in > "06:00:00pm") {
        $day1 = '11,01,2022 ';
        $day2 = '11,02,2022 ';
        $new_signin = $day1 . $sign_in;
        $new_timeout = $day2 . $emp_timeout;

        if ($new_signin < $new_timeout) {
            $status1 = 'Early going';
        } else {
            $status1 = 'Over Time';
        }
    } else {
        if ($sign_in < $emp_timeout) {

            array_push($array, $sign_in);
            array_push($array, $emp_timeout);


            $status1 = 'Early Going';
        } else {
            $status1 = 'Overtime';
        }
    }
    $sql = "UPDATE `signin` SET `activity`='Signed Out',`Signout_Status`='$status1' WHERE Name='$name' ORDER BY auto DESC LIMIT 1;";
    $result = mysqli_query($conn, $sql);

    $sql4 = "UPDATE `signin` SET `Signout`='$sign_in' WHERE Name='$name' order by auto desc limit 1";
    $result = mysqli_query($conn, $sql4);
} elseif ($activity == "Signed Out" && $curr_date == $date) {
    $sql = "INSERT INTO `signin`(`user_id`, `Name`,`Signin`, `Status`,`Signout_Status`, `Signout`,`activity`,`Date`,`attendance`) VALUES ('$ID','$name','$sign_in','Welcome Back','-','','Signed in','$date','-')";
    // $sql5="SELECT * FROM 'signin' where "

    $result = mysqli_query($conn, $sql);
} else {

    $sql = "INSERT INTO `signin`( `user_id`,`Name`,`Signin`, `Status`, `Signout`,`activity`,`Date`,`attendance`) VALUES ('$ID','$name','$sign_in','$status','-','Signed in','$date','Present')";
    // $sql5="SELECT * FROM 'signin' where "

    $result = mysqli_query($conn, $sql);
    $sql1 = "SELECT STATUS FROM `signin` WHERE Name='$name'  ORDER BY auto DESC LIMIT 1;";

    $result = mysqli_query($conn, $sql1);

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($array, $row["STATUS"]);
        }
    }
}


if ($activity == "Signed in" and $date == $curr_date and $sign_in>="09:00:00pm") {
  
    $emparr = [];
    $sqlll = "SELECT * FROM `users` where user_status='Active   '";
    $query = mysqli_query($conn, $sqlll) or die("Query Unsuccessful");
    while ($row = mysqli_fetch_assoc($query)) {
        
        $empnames = "{$row['employee_name']}";
        array_push($emparr, $empnames);
    }
    //  
    $dailyarr = [];
    $sqo = "SELECT * FROM `signin` WHERE  Date='$date'";
    $query5 = mysqli_query($conn, $sqo) or die("Query Unsuccessful");
    while ($row = mysqli_fetch_assoc($query5)) {
       
        $dailynames = "{$row['user_id']}";
        array_push($dailyarr, $dailynames);
    }
    //   array_push($array,$dailyarr);
    $b = array_diff($emparr, $dailyarr);
    array_push($array, $b);
    $arrayLength = count($b);
    $i = 0;
    $absenties = '';
    while ($i < $arrayLength) {
      
        $absenties = $b[$i];
        $sql69 = "INSERT INTO `signin`(`user_id`,`Name`,`Signin`,`Status`,`Signout_Status`,`Signout`,`activity`,`Date`,`attendance`) VALUES ('$ID','$absenties','sign_in','Welcome Back','-','-','Signed in','$date','Absent')";
        $query6 = mysqli_query($conn, $sql69) or die("Query Unsuccessful");
        $i++;
    }
}


echo json_encode($array);
