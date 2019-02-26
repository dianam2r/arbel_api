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
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare task object
$task = new Task($db);
 
// get id of the task to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of the task to be edited
$task->id = $data->id;
 
// set task property values
$task->title = $data->title;
$task->description = $data->description;
$task->estimated_points = $data->estimated_points;
$task->attached_file = $data->attached_file;
$task->assigned_to = $data->assigned_to;
$task->status_id = $data->status_id;
$task->updated_by = $data->updated_by;
 
// update the task
if($task->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("message" => "Task was updated."));
}
 
// if unable to update the task, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
    echo json_encode(array("message" => "Unable to update the task."));
}