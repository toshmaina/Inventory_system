<?php
require_once(LIB_PATH_INC.DS."config.php");

class MySqli_DB {
    // Database connection handler
    private $con;
    // Stores the latest query result
    public $query_id;

    // Constructor - automatically connects to database when class is instantiated
    function __construct() {
      $this->db_connect();
    }

/*--------------------------------------------------------------*/
/* Establishes connection to MySQL database using credentials from config file
/* Returns: void
/* Dies with error message if connection fails
/*--------------------------------------------------------------*/
public function db_connect()
{
    // Create connection using defined constants
    $this->con = mysqli_connect(DB_HOST,DB_USER,DB_PASS);
    if(!$this->con)
         {
           die(" Database connection failed:". mysqli_connect_error());
         } else {
           $select_db = $this->con->select_db(DB_NAME);
             if(!$select_db)
             {
               die("Failed to Select Database". mysqli_connect_error());
             }
         }
}
/*--------------------------------------------------------------*/
/* Function for Close database connection
/*--------------------------------------------------------------*/

public function db_disconnect()
{
  if(isset($this->con))
  {
    mysqli_close($this->con);
    unset($this->con);
  }
}
/*--------------------------------------------------------------*/
/* Executes an SQL query
/* @param string $sql - The SQL query to execute
/* Returns: mysqli_result|false - Query result object or false on failure
/* Dies with query text in development mode if query fails
/*--------------------------------------------------------------*/
public function query($sql)
   {

      if (trim($sql != "")) {
          $this->query_id = $this->con->query($sql);
      }
      if (!$this->query_id)
        // only for Developer mode
              die("Error on this Query :<pre> " . $sql ."</pre>");
       // For production mode
        //  die("Error on Query");

       return $this->query_id;

   }

/*--------------------------------------------------------------*/
/* Query Helper Methods
/* These methods wrap common mysqli result functions for easier use
/*--------------------------------------------------------------*/
// Fetch row as numeric and associative array
public function fetch_array($statement)
{
  return mysqli_fetch_array($statement);
}
// Fetch row as object
public function fetch_object($statement)
{
  return mysqli_fetch_object($statement);
}
// Fetch row as associative array
public function fetch_assoc($statement)
{
  return mysqli_fetch_assoc($statement);
}
// Get number of rows in result
public function num_rows($statement)
{
  return mysqli_num_rows($statement);
}
// Get ID of last inserted row
public function insert_id()
{
  return mysqli_insert_id($this->con);
}
// Get number of affected rows from last query
public function affected_rows()
{
  return mysqli_affected_rows($this->con);
}
/*--------------------------------------------------------------*/
/* Escapes special characters in a string for SQL query safety
/* @param string $str - The string to escape
/* Returns: string - The escaped string
/*--------------------------------------------------------------*/
public function escape($str){
   return $this->con->real_escape_string($str);
 }
/*--------------------------------------------------------------*/
/* Converts mysqli result into an array of all rows
/* @param mysqli_result $loop - The query result to process
/* Returns: array - Array containing all rows from the result
/*--------------------------------------------------------------*/
public function while_loop($loop){
 global $db;
   $results = array();
   while ($result = $this->fetch_array($loop)) {
      $results[] = $result;
   }
 return $results;
}

}
//instanciate the class as an object

$db = new MySqli_DB();

?>
