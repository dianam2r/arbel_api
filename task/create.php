<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// include database and object files
include_once '../setup/database.php';
include_once '../objects/task.php';

// instantiate database and task object
$database = new Database();
$db = $database->getConnection();
 
$task = new Task($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(
    !empty($data->title) &&
    !empty($data->status_id)
){
 
    // set task property values
    $task->id = $data->id;
    $task->title = $data->title;
    $task->description = $data->description;
    $task->estimated_points = $data->estimated_points;
    $task->attached_file = $data->attached_file;
    $task->assigned_to = $data->assigned_to;
    $task->status_id = $data->status_id;
    $task->created_at = $data->created_at;
    $task->updated_at = $data->updated_at;
    $task->created_by = $data->created_by;
    $task->updated_by = $data->updated_by;
 
    // create the task
    if($task->create()){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("message" => "Task was created."));
    }
 
    // if unable to create the task, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
        echo json_encode(array("message" => "Unable to create task."));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to create task. Data is incomplete."));
}