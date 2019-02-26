<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
// include database and object files
include_once '../setup/database.php';
include_once '../objects/task.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare task object
$task = new Task($db);
 
// set ID property of record to read
$task->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of task to be edited
$task->readOne();
 
if($task->title!=null){
    // create array
    $task_arr = array(
        "id" =>  $task->id,
        "title" => $task->title,
        "description" => $task->description,
        "estimated_points" => $task->estimated_points,
        "attached_file" => $task->attached_file,
        "assigned_to" => $task->assigned_to,
        "task_status" => $task->status_id,
        "created_at" => $task->created_at,
        "updated_at" => $task->updated_at,
        "created_by" => $task->created_by,
        "updated_by" => $task->updated_by,
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
    echo json_encode($task_arr);
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user task does not exist
    echo json_encode(array("message" => "Task not found."));
}