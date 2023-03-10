<?php
if ($_POST["action"] == "login") {
    login();
} else if ($_POST["action"] == "logout") {
    logout();} 
else if ($_POST["action"] == "change_password") {
    change_password();}

function login()
{
    include '../include/db_connection.php';
    $arr = [];
    $id = $_POST["id"];
    $password = $_POST["password"];
    $sql = "SELECT * FROM `user` WHERE `name` = '$id' AND `password` = '$password'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        session_start();
        $_SESSION["status"] = "logged in";
        $_SESSION["id"] = $id;
        array_push($arr, 'logged in');
        while ($row = $result->fetch_assoc()) {
            $_SESSION["name"] = $row["name"];
            array_push($arr, $row['name']);

        }

        echo json_encode($arr);

    } else {
        echo "0 results";
    }
}

function logout()
{
    session_start();
    session_destroy();
    echo 'logout';
}
function change_password(){
echo "password changed";}
