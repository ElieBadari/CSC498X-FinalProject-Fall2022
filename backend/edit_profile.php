<?php
include("connection.php");
include("functions.php");

$bio = $pic_url = $password = $email = $username  = $user_id ="";
$results = [];
$response = [];

//retrieving and validating info
//------------------------------
//user_id doesnt need validation because itll be sent from the frontend

if (isset($_POST["username"]) && $_POST["username"] != ""){
    //user wants to change username
    $check_user_query = $mysqli->prepare("SELECT user_id FROM users WHERE username = ?");
    $param_username = filter_data($_POST["username"]);
    $check_user_query->bind_param("s",$param_username);
    if($check_user_query->execute()){
        $check_user_query->store_result();
        if($check_user_query->num_rows == 1){
            $results["Username Success"] = false;
            return;
        }else {
            $param_username = filter_data($_POST["username"]);
            $param_user_id = filter_data($_POST["user_id"]);
            //we update the username
            $update_username_query = $mysqli->prepare("UPDATE users SET username = ? WHERE user_id = ?");
            $update_username_query->bind_param("ss",$param_username,$param_user_id);
            if ($update_username_query->execute()){
                $results["Username Success"] = true;
            }else {
                $results["Username Success"] = false;
                return;
            }            
        }
    }
    $check_user_query->close();
    $update_username_query->close();
}else {
    $results["Username Success"] = false;
    return;
}

if (isset($_POST["email"]) && $_POST["email"] != ""){
    //user wants to change email
    $check_email_query = $mysqli->prepare("SELECT user_id FROM users WHERE email = ?");
    $param_email = filter_data($_POST["email"]);
    $check_email_query->bind_param("s",$param_email);
    if($check_email_query->execute()){
        $check_email_query->store_result();
        if($check_email_query->num_rows == 1){
            $results["Email Success"] = false;
            return;
        }else {
            $param_email = filter_data($_POST["email"]);
            $param_user_id = filter_data($_POST["user_id"]);
            //we update the email
            $update_email_query = $mysqli->prepare("UPDATE users SET email = ? WHERE user_id = ?");
            $update_email_query->bind_param("ss",$param_email,$param_user_id);
            if ($update_email_query->execute()){
                $results["Email Success"] = true;
            }else {
                $results["Email Success"] = false;
                return;
            }            
        }
    }
    $check_email_query->close();
    $update_email_query->close();
}else {
    $results["Email Success"] = false;
    return;
}

if (isset($_POST["password"]) && $_POST["password"] != ""){
    //user wants to change password
    $param_password = hash('sha256', filter_data($_POST["password"]));
    $param_user_id = filter_data($_POST["user_id"]);
    //we update the password
    $update_password_query = $mysqli->prepare("UPDATE users SET password = ? WHERE user_id = ?");
    $update_password_query->bind_param("ss",$param_password,$param_user_id);
    if ($update_password_query->execute()){
        $results["Password Success"] = true;
    }else {
        $results["Password Success"] = false;
        return;
    }            
    $update_password_query->close();
}else {
    $results["Password Success"] = false;
    return;
}

if (isset($_POST["bio"]) && $_POST["bio"] != ""){
    //user wants to change bio
    $param_bio = filter_data($_POST["bio"]);
    $param_user_id = filter_data($_POST["user_id"]);
    //we update the bio
    $update_bio_query = $mysqli->prepare("UPDATE users SET bio = ? WHERE user_id = ?");
    $update_bio_query->bind_param("ss",$param_bio,$param_user_id);
    if ($update_bio_query->execute()){
        $results["Bio Success"] = true;
    }else {
        $results["Bio Success"] = false;
        return;
    }            
    $update_bio_query->close();
}else{
    $results["Bio Success"] = false;
    return;
}

if (isset($_POST["pic_url"]) && $_POST["pic_url"] != ""){
    //user wants to change pic_url
    $param_pic_url = filter_data($_POST["pic_url"]);
    $param_user_id = filter_data($_POST["user_id"]);
    //we update the pic_url
    $update_pic_url_query = $mysqli->prepare("UPDATE users SET pic_url = ? WHERE user_id = ?");
    $update_pic_url_query->bind_param("ss",$param_pic_url,$param_user_id);
    if ($update_pic_url_query->execute()){
        $results["Pic_url Success"] = true;
    }else {
        $results["Pic_url Success"] = false;
        return;
    }            
    $update_pic_url_query->close();
}else{
    $results["Pic_url Success"] = false;
    return;
}


foreach ($results as $key => $value) { 
    $response[$key] = $value;
}
unset($value);

echo json_encode($response);
