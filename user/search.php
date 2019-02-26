<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
// include_once '../setup/core.php';
include_once '../setup/database.php';
include_once '../objects/user.php';
 
// instantiate database and user object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$user = new User($db);
 
// get keywords
$keywords = isset($_GET["keyword"]) ? $_GET["keyword"] : "";
 
// query users
$stmt = $user->search($keywords);
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num > 0){
 
    // users array
    $users_arr = array();
    $users_arr["records"] = array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $user_item = array(
            "id" => $id,
            "name" => $name,
            "last_name" => $last_name,
            "team_name" => $team_name,
            "username" => $username,
            "created_at" => $created_at
        );
 
        array_push($users_arr["records"], $user_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show users data
    echo json_encode($users_arr);
} else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no users found
    echo json_encode(
        array("message" => "No users found.")
    );
}