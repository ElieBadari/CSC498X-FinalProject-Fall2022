<?php

include("connection.php");
include("functions.php");

$user_id = $user_id_type = -1;
$user_id_type = -1; // 0 = admin, 1 = lounge, 2 = user

if (isset($_POST["user_id"]) && $_POST["user_id"] != ""){
    $user_id = filter_data($_POST["user_id"]);
    $get_user_query = $mysqli->prepare("SELECT * FROM users WHERE user_id = ?");
    $get_user_query->bind_param("s",$user_id);
    if ($get_user_query->execute()){
       //change user_id_type
       if(isset($_POST["user_id_type"]) && $_POST["user_id_type"] != ""){
           $user_id_type = filter_data($_POST["user_id_type"]);
           $change_user_type_query = $mysqli->prepare("UPDATE users SET user_id_type = ? WHERE user_id = ?");
           $change_user_type_query->bind_param("ss",$user_id_type,$user_id);
           if($change_user_type_query->execute()){
               $response["Success"] = "User type changed";
           }else{
               $response["Error"] = "Error changing user type";
           }
        }else{
            $response["Error"] = "Error getting user";
        }
    $get_user_query->close();
    $change_user_type_query->close();
    }else {
        $response["Error"] = "No user id provided";
    }
}

?>