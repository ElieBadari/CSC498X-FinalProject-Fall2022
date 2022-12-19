<?php

include("connection.php");
include("functions.php");

$username = $email = $pass = "";
$id = "";
$results = [];
$response = [];
$flag = true;

//retrieving and validating info
//------------------------------

//validate user
//-------------
if (isset($_POST["username"]) && $_POST["username"] != ""){
    $check_user_query = $mysqli->prepare("SELECT `userId` FROM `users` WHERE `username` = ?");
    $param_username = filter_data($_POST["username"]);
    $check_user_query->bind_param("s",$param_username);
    if($check_user_query->execute()){
        $check_user_query->store_result();
        if($check_user_query->num_rows == 1){
            $results["Username Success"] = false;
            return;
        }else {
            $username = filter_data($_POST["username"]);
            $results["Username Success"] = true;
            
        }
    }
    $check_user_query->close();
}else {
    $results["Username Success"] = false;
    return;
}
//validate email
//--------------
if (isset($_POST["email"]) && $_POST["email"] != ""){
    $check_email_query = $mysqli->prepare("SELECT `userId` FROM `users` WHERE `email` = ?");
    $param_email = filter_data($_POST["email"]);
    $check_email_query->bind_param("s",$param_email);
    if($check_email_query->execute()){
        $check_email_query->store_result();
        if($check_email_query->num_rows == 1){
            $results["Email Success"] = false;
            return;
        }else {
            $email = filter_data($_POST["email"]);
            $results["Email Success"] = true;
        }
    }
    $check_email_query->close();
}else {
    $results["Email Success"] = false;
    return;
}
//validate password
//-----------------
if (isset($_POST["password"]) && $_POST["password"] != ""){
    $pass = hash('sha256', filter_data($_POST["password"]));
    $results["Password Success"] = true;
}else {
    $results["Password Success"] = false;
    return;
}

//adding the user to the database
//------------------------------
foreach($results as $key => $value){
    if(!$value){   
        $flag = false;
    }
    $response[$key] = $value;
}
unset($value);

if (!$flag){
    $response["User Success Values"] = false;
    return;
}else {
    $response["User Success Values"] = true;
    $query = $mysqli->prepare("INSERT INTO `users` (`username`, `email`, `password`) VALUES (?,?,?)");
    $query->bind_param("sss", $username, $email, $pass);
    if ($query->execute()){
        $response["User Success Query"] = true;
        $query->close();
    }else {
        $response["User Success Query"] = false;
        return;
    }
    
}
//user has successfully signed up now to directly log them in
$login_query = $mysqli->prepare("SELECT userId FROM users WHERE username = ? AND email = ?");
$login_query->bind_param("ss",$username,$email);
if ($login_query->execute()){
    $response["User Login Success"] = true;
    $array = $login_query->get_result();
    $response["userId"] = $array->fetch_assoc();
}else {
    $response["User Login Success"] = false;
}
echo json_encode($response);

?>