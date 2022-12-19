<?php

include("connection.php");
include("functions.php");

$id = -1;
$results = [];
$response = [];
$flag = true;

if (isset($_POST["lounge_id"]) && $_POST["lounge_id"] != ""){
    //we are getting a specific lounge by id 
    $lounge_id = filter_data($_POST["lounge_id"]);
    $get_lounge_query = $mysqli->prepare("SELECT * FROM lounges WHERE lounge_id = ?");
    $get_lounge_query->bind_param("s",$lounge_id);
    if ($get_lounge_query->execute()){
        $response["lounge Success"] = true;
        $array = $get_lounge_query->get_result();
        $resposne["lounge"] = $array->fetch_assoc();
    }else{
        $response["lounge Success"] = false;
    }
}else {
    //we are getting all lounges
    $get_lounges_query = $mysqli->prepare("SELECT * FROM lounges");
    if ($get_lounges_query->execute()){
        while($lounge = $get_lounge_query->fetch_assoc()){
            $response[] = $lounge;
        }
        $response["lounges Success"] = true;   
    }else{
        $response["lounges Success"] = false;
    }
}
echo json_encode($response);
?>