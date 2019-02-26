<?php

/*
 * This is encapsulates the User object
 * in order to interact better with the database
 */
class User{
    // database connection and table name
    private $conn;
    private $table_name = "users";

    // object properties
    public $id;
    public $name;
    public $last_name;
    public $group_id;
    public $username;
    public $password;
    public $created_at;
    public $updated_at;

    // constructor with db connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read users
    function read(){
        // select all query
        $query = "SELECT u.id as id, 
                        u.name as name, 
                        u.last_name as last_name, 
                        u.username as username, 
                        t.name as team_name " .
                " FROM " . $this->table_name . " u " .
                " JOIN teams t 
                    ON t.id = u.group_id " .
                " ORDER BY u.id ASC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // create user
    function create(){ 

        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->group_id = htmlspecialchars(strip_tags($this->group_id));
        $this->password = hash('sha512', htmlspecialchars(strip_tags($this->password)));
        $this->created_at = !empty($this->created_at) ?  htmlspecialchars(strip_tags($this->created_at)) : "CURRENT_TIMESTAMP";
        $this->updated_at = !empty($this->updated_at) ? htmlspecialchars(strip_tags($this->updated_at)) : "CURRENT_TIMESTAMP";

        // query to insert record
        $query = "INSERT INTO " . $this->table_name . 
            " (`id`, `name`, `last_name`, `group_id`, 
            `username`, `password`, `created_at`, 
            `updated_at`) " .
            "VALUES (NULL, " . 
                "'" . $this->name . "', " .
                "'" . $this->last_name . "', " .
                $this->group_id . ", " .
                "'" . $this->username . "', " .
                "'" . $this->password . "', " .
                $this->created_at. ", " .
                $this->updated_at. ")";

        // prepare query
        $stmt = $this->conn->prepare($query);
 
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;  
    }

    // used when filling up the update user form
    function readOne(){  
        // query to read single record
        $query = "SELECT u.id as id, 
                        u.name as name, 
                        u.last_name as last_name, 
                        t.name as team, 
                        u.username as username, 
                        u.created_at as created_at, 
                        u.updated_at as updated_at " .
                " FROM " . $this->table_name . " u " .
                " LEFT JOIN teams t ON t.id = u.group_id " .
                " WHERE u.id = " . $this->id .
                " LIMIT 0,1";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // execute query
        $stmt->execute();
    
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // set values to object properties
        $this->name = $row['name'];
        $this->last_name = $row['last_name'];
        $this->group_id = $row['team'];
        $this->username = $row['username'];
        $this->created_at = $row['created_at'];
        $this->updated_at = $row['updated_at'];
    }

    // update the user
    function update(){
        
        // sanitize
        $this->name = !empty($this->name) ? htmlspecialchars(strip_tags($this->name)) : "";
        $this->last_name = !empty($this->last_name) ? htmlspecialchars(strip_tags($this->last_name)) : "";
        $this->group_id = !empty($this->group_id) ? htmlspecialchars(strip_tags($this->group_id)) : 0;
        $this->username = !empty($this->username) ? htmlspecialchars(strip_tags($this->username)) : "";
        $this->password = hash('sha512', htmlspecialchars(strip_tags($this->password)));
        $this->updated_at = !empty($this->updated_at) ? htmlspecialchars(strip_tags($this->updated_at)) : "CURRENT_TIMESTAMP";

        // update query
        $query = "UPDATE " . $this->table_name .
                " SET name = '". $this->name . "', 
                    last_name = '" . $this->last_name . "', 
                    group_id = '" . $this->group_id . "', 
                    username = '" . $this->username . "', 
                    password = '" . $this->password . "',  
                    updated_at = " . $this->updated_at .
                " WHERE id = " . $this->id;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute the query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }

    // delete the user
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

    // search users
    function search($keywords){
        
        // sanitize
        $keywords = htmlspecialchars(strip_tags($keywords));
        if(!$keywords) {
            $keywords = '|';
        }

        // select all query
        $query = "SELECT u.id as id, 
                u.name as name, 
                u.last_name as last_name, 
                t.name as team_name, 
                u.username as username, 
                u.created_at as created_at " .
            " FROM " . $this->table_name . " u " .
            " LEFT JOIN teams t
                ON t.id = u.group_id 
            WHERE
                u.name REGEXP '" . $keywords . "'" . 
            " OR u.last_name REGEXP '" . $keywords . "'" .
            " OR u.username REGEXP '" . $keywords . "'" .
            " OR t.name REGEXP '" . $keywords . "'" .
            " ORDER BY u.id ASC";                
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
    
        return $stmt;
    }
}