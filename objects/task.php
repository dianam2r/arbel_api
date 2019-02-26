<?php

/*
 * This is encapsulates the Task object
 * in order to interact better with the database
 */
class Task{

    // database connection and table name
    private $conn;
    private $table_name = "tasks";

    // object properties
    public $id;
    public $title;
    public $description;
    public $estimated_points;
    public $attached_file;
    public $assigned_to;
    public $status_id;
    public $created_by;
    public $created_at;
    public $updated_by;
    public $updated_at;

    // constructor with db connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read tasks
    function read(){
        // select all query
        $query = "SELECT t.id as task_id,
                        t.title as task_title, 
                        t.description as task_description, 
                        t.estimated_points as estimated_points, 
                        concat(u.name, ' ', u.last_name) as assigned_to, 
                        s.id as status_id, 
                        s.title as task_status".
                " FROM " . $this->table_name . " t " .
                " LEFT JOIN users u ON t.assigned_to = u.id " .
                " JOIN status as s on s.id = t.status_id " .
                " ORDER BY t.id DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // create task
    function create(){ 

        // sanitize
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = !empty($this->description) ? htmlspecialchars(strip_tags($this->description)) : NULL;
        $this->estimated_points = !empty($this->estimated_points) ? htmlspecialchars(strip_tags($this->estimated_points)) : NULL;
        $this->attached_file = !empty($this->attached_file) ? htmlspecialchars(strip_tags($this->attached_file)) : "NULL";
        $this->assigned_to = !empty($this->assigned_to) ? htmlspecialchars(strip_tags($this->assigned_to)) : "NULL";
        $this->status_id = htmlspecialchars(strip_tags($this->status_id));
        $this->created_at = !empty($this->created_at) ?  htmlspecialchars(strip_tags($this->created_at)) : "CURRENT_TIMESTAMP";
        $this->updated_at = !empty($this->updated_at) ? htmlspecialchars(strip_tags($this->updated_at)) : "CURRENT_TIMESTAMP";
        $this->created_by = htmlspecialchars(strip_tags($this->created_by));
        $this->updated_by = htmlspecialchars(strip_tags($this->updated_by));

        // query to insert record
        $query = "INSERT INTO " . $this->table_name . 
            " (`id`, `title`, `description`, `estimated_points`, 
            `attached_file`, `assigned_to`, `status_id`, 
            `created_at`, `updated_at`, `created_by`, 
            `updated_by`) " .
            "VALUES (NULL, " . 
                "'" . $this->title . "', " .
                "'" . $this->description . "', " .
                "'" . $this->estimated_points . "', " .
                $this->attached_file . ", " .
                $this->assigned_to . ", " .
                $this->status_id . ", " .
                $this->created_at. ", " .
                $this->updated_at. ", " .
                $this->created_by . ", " .
                $this->updated_by . ")";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;        
    }

    // used when filling up the update task form
    function readOne(){  
        // query to read single record
        $query = "SELECT t.id as id, 
                        t.title as title, 
                        t.description as description, 
                        t.estimated_points as estimated_points, 
                        t.attached_file as attached_file, 
                        u.name as assigned_to, 
                        s.title as status_id, 
                        t.created_at as created_at, 
                        t.updated_at as updated_at, 
                        u2.name as created_by, 
                        u3.name as updated_by".
                " FROM " . $this->table_name . " t " .
                " LEFT JOIN users u ON t.assigned_to = u.id " .
                " JOIN status as s on s.id = t.status_id " .
                " LEFT JOIN users u2 ON t.created_by = u2.id " .
                " LEFT JOIN users u3 ON t.updated_by = u3.id " .
                " WHERE t.id = " . $this->id .
                " LIMIT 0,1";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // execute query
        $stmt->execute();
    
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // set values to object properties
        $this->title = $row['title'];
        $this->description = $row['description'];
        $this->estimated_points = $row['estimated_points'];
        $this->attached_file = $row['attached_file'];
        $this->assigned_to = $row['assigned_to'];
        $this->status_id = $row['status_id'];
        $this->created_at = $row['created_at'];
        $this->updated_at = $row['updated_at'];
        $this->created_by = $row['created_by'];
        $this->updated_by = $row['updated_by'];
    }

    // update the task
    function update(){
        
        // sanitize
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = !empty($this->description) ? htmlspecialchars(strip_tags($this->description)) : NULL;
        $this->estimated_points = !empty($this->estimated_points) ? htmlspecialchars(strip_tags($this->estimated_points)) : "NULL";
        $this->attached_file = !empty($this->attached_file) ? htmlspecialchars(strip_tags($this->attached_file)) : "NULL";
        $this->assigned_to = !empty($this->assigned_to) ? htmlspecialchars(strip_tags($this->assigned_to)) : "NULL";
        $this->status_id = htmlspecialchars(strip_tags($this->status_id));
        $this->updated_at = !empty($this->updated_at) ? htmlspecialchars(strip_tags($this->updated_at)) : "CURRENT_TIMESTAMP";
        $this->updated_by = htmlspecialchars(strip_tags($this->updated_by));

        // update query
        $query = "UPDATE " . $this->table_name .
                " SET title = '". $this->title . "', 
                    description = '" . $this->description . "', 
                    estimated_points = " . $this->estimated_points . ", 
                    attached_file = " . $this->attached_file . ", 
                    assigned_to = " . $this->assigned_to . ", 
                    status_id = " . $this->status_id . ", 
                    updated_at = " . $this->updated_at . ", 
                    updated_by = " . $this->updated_by .
                " WHERE id = " . $this->id;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute the query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }

    // delete the task
    function delete(){
        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // delete query
        $query = "DELETE FROM " . $this->table_name . 
                " WHERE id = " . $this->id;
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;        
    }

    // search tasks
    function search($keywords){
        
        // sanitize
        $keywords=htmlspecialchars(strip_tags($keywords));
        if(!$keywords) {
            $keywords = '|';
        }

        // select all query
        $query = "SELECT t.id as id, 
                    t.title as title, 
                    t.description as description, 
                    t.estimated_points as estimated_points, 
                    u.name as assigned_to, 
                    s.title as task_status " .
                " FROM " . $this->table_name . " t " .
                " LEFT JOIN users u
                    ON u.id = t.assigned_to 
                  JOIN status s 
                    ON s.id = t.status_id  
                WHERE
                    t.title REGEXP '" . $keywords . "'" . 
                " OR t.description REGEXP '" . $keywords . "'" .
                " OR t.estimated_points REGEXP '" . $keywords . "'" .
                " OR u.name REGEXP '" . $keywords . "'" .
                " OR s.title REGEXP '" . $keywords . "'" .
                " ORDER BY t.id ASC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
    
        return $stmt;
    }
}