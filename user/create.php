<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// include database and object files
include_once '../setup/database.php';
include_once '../objects/user.php';

// instantiate database and user object
$database = new Database();
$db = $database->getConnection();
 
$user = new User($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(
    !empty($data->name) &&
    !empty($data->last_name) &&
    !empty($data->username) &&
    !empty($data->password) &&
    !empty($data->group_id)
){
 
    // set user property values
    //$user->id = $data->id;
    $user->name = $data->name;
    $user->last_name = $data->last_name;
    $user->group_id = $data->group_id;
    $user->username = $data->username;
    $user->password = $data->password;
    $user->created_at = $data->created_at;
    $user->updated_at = $data->updated_at;
 
    // create the user
    if($user->create()){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("message" => "The user was created."));
    }
 
    // if unable to create the user, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
        echo json_encode(array("message" => "Unable to create user."));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
}