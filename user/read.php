<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../setup/database.php';
include_once '../objects/user.php';
 
// instantiate database and user object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$user = new User($db);
 
// query users
$stmt = $user->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num > 0){
    // users array
    $users = array();
    $users_arr["records"] = array();
 
    // retrieve our table contents
    // using fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $user_item = array(
            "id" => $id,
            "name" => $name,
            "last_name" => $last_name,
            "username" => $username,
            "team_name" => $team_name
        );
 
        array_push($users_arr["records"], $user_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show users data in json format
    echo json_encode($users_arr);
} else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no users were found
    echo json_encode(array("message" => "No users found."));
}