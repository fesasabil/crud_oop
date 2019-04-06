<?php

class Database {
    private $db_host = "localhost";
    private $db_user = "root";
    private $db_pass = "siapalagi04";
    private $db_name = "tugas";

    private $conn = false;
    private $myconn = "";
    private $result = array();
    private $myQuery = "";
    private $numResults = "";

    //function to make connection to database
    public function connect() {
        if(!$this->conn) {
            $this->myconn = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
        if($this->myconn->connect_errno > 0) {
            array_push($this->result,$this->myconn->connect_error);
            return false;
        }else{
            $this->conn = true;
            return true;
        }
        }else{
            return true;
        }
    }

    //Function to disconnect from the database
    public function disconnect() {
        if($this->conn) {
            if($this->myconn->close()){
                $this->conn = false;
                return true;
            }else{
                return false;
            }
        }
    }
    //
    public function sql($sql){
        $query = $this->myconn->query($sql);
        $this->myQuery = $sql;
        if($query){
            $this->numResults = $query->num_rows;

            for($i = 0; $i < $this->numResults; $i++){
                $r = $query->fetch_array();
            $key = arrray_keys($r);
            for($x = 0; $x < count($key); $x++){
                if(!is_int($key[$x])){
                    if($query->num_rows >= 1){
                        $this->result[$i][$key[$x]] = $r[$key[$x]];
                    }else{
                        $this->result = null;
                    }
                }
            }
            }
            return true;//Query was successful
        }else{
                array_push($this->result, $this->myconn->error);
                return false;
            }
    }

    //Function to Select from the database
    public function select($table, $rows = '*', $join = null, $where = null, $order = null, $limit = null){
        //create query from the variables passed to the function
        $q = 'SELECT '.$rows.' FROM '.$table;
        if($join != null){
            $q .= ' JOIN '.$join;
        }
        if($where != null){
            $q .= ' WHERE '.$where;
        }
        if($order != null){
            $q .= ' ORDER BY '.$order;
        }
        if($limit != null){
            $q .= ' LIMIT '.$limit;
        }
        //echo table
        $this->myQuery = $q;//pas back the SQL
        //check to see if the table exists
        if($this->tableExists($table)){
          //the table exists, run the query
          $query = $this->myconn->$query($q);
          if($query){
              // If the query returns >= 1 assign the number of rows to numResults
              $this->numResults = $query->num_rows;
              // Loop through the query results by the number of rows returned
              for($i = 0; $i < $this->numResults; $i++){
                  $r = $query->fetch_array();
                  $key = array_keys($r);

                for($x = 0; $x < count($key); $x++){
                    // Sanitizes keys so only alphavalues are allowed
                if(!is_int($key[$x])){
                    if($query->num_rows >= 1){
                        $this->result[$i][$key[$x]] = $r[$key[$x]];
                    }else{
                        $this->result[$i][$key[$x]] = null;
                    }
                }
                }
              }
              return true;//Query was succesful
          }else{
              array_push($this->result, $this->myconn->error);
              return false;
          }
        }else{
            return false;//table does not exist
        }
    }

    //function to insert to database
    public function insert($table,$params=array()){
        //check if the table exist
        if($this->tableExists($table)){
            $sql='INSERT INTO '.$table.' ( '.implode(', ',array_keys($params)).') VALUES("' .implode('", "', $params) . '")';
        $this->myQuery = $sql;//pas back the SQL
        //Make the query to insert to the database
        if($ins = $this->myconn->query($sql)){
            array_push($this->result,$this->myconn->insert_id);
            return true; //the data has not been inserted
        }
        }else{
            return false;
        }
    }

    //function to delete table or row(s) from database
    public function delete($table, $where = null){
        //check to see if table exist
        if($this->tableExists($table)){
            // The table exists check to see if we are deleting rows or table
            if($where == null){
                $delete = 'DROP TABLE '.$table;//Create query to delete table
            }else{
                $delete = 'DELETE FROM '.$table.' WHERE '.$where;
            }
            //submit query to database
            if($del = $this->myconn->query($delete)){
                array_push($this->result,$this->myconn->affected_rows);
                $this->myQuery = $delete;//pass pack the SQL
                return true;
            }else{
                array_push($this->result,$this->myconn->error);
                return false;// The query did not execute correctly
            }
        }else{
            return false;
        }
    }

    //function to update row in database
    public function update($table, $params=array(),$where){
        //check to see if table exists
        if($this->tableExists($table)){
            //create array to hold all the columns to update
            $args=array();
        foreach ($params as $field=>$value) {
            // Seperate each column out with it's corresponding value
            $args[]=$field.'="'.$value.'"';
        }
        //create the query
        $sql = 'UPDATE '.$table.' SET '.implode(',',$args).' WHERE '.$where;
        //make query to database
        $this->myQuery = $sql;//pass back the sql
        if($query = $this->myconn->query($sql)){
            array_push($this->result,$this->myconn->affected_rows);
            return true;// Update has been successful
        }else{
            array_push($this->result,$this->myconn->error);
            return false;// Update has not been successful
        }
        }else{
            return false;
        }
    } 
    // Private function to check if table exists for use with queries
    private function tableExists($table){
        $tablesInDb = $this->myconn->query('SHOW TABLES FROM '.$this->db_name.' LIKE "'.$table.'"');
    if ($tablesInDb) {
        if($tablesInDb->num_rows == 1) {
            return true;
        }else{
            array_push($this->result,$table."does not exist in this database");
            return false;
        }
    }
    }
    
    //public function to return the data to the user
    public function getResult(){
        $val = $this->result;
        $this->result = array();
        return $val;
    }

    //Pass the SQL back for debugging
    public function getSql(){
        $val = $this->myQuery;
        $this->myQuery = array();
        return $val;
    }

    //pass the number of rows back
    public function numRows(){
        $val = $this->numResults;
        $this->myQuery = array();
        return $val;
    }

    //escape your string
    public function escapeString($data){
        return $this->myconn->real_escape_string($data);
    }
}