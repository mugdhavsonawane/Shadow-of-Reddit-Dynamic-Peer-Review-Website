<?php
// This class has a constructor to connect to a database. The given
// code assumes that there is a database named 'quotes' inside MariaDB.
//
// Call function startByScratch() to drop quotes if it exists and then create
// a new database named quotes and add the two tables.
// The function startByScratch() is only used for testing code at the bottom.
// 
// Author: Mugdha Sonawane
//
class DatabaseAdaptor {
  private $DB; // The instance variable used in every method below
  // Connect to an existing data based named 'first'
  public function __construct() {
    $dataBase ='mysql:dbname=quotes;charset=utf8;host=127.0.0.1';
    $user ='root';
    $password =''; // Empty string with XAMPP install
    try {
        $this->DB = new PDO ( $dataBase, $user, $password );
        $this->DB->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    } catch ( PDOException $e ) {
        echo ('Error establishing Connection');
        exit ();
    }
  }
    
// This function exists only for testing purposes. Do not call it any other time.
public function startFromScratch() {
  $stmt = $this->DB->prepare("DROP DATABASE IF EXISTS quotes;");
  $stmt->execute();
       
  // This will fail unless database "quotes" is created inside MariaDB.
  $stmt = $this->DB->prepare("create database quotes;");
  $stmt->execute();

  $stmt = $this->DB->prepare("use quotes;");
  $stmt->execute();
        
  $update = " CREATE TABLE quotations ( " .
            " id int(20) NOT NULL AUTO_INCREMENT, added datetime, quote varchar(2000), " .
            " author varchar(100), rating int(11), flagged tinyint(1), PRIMARY KEY (id));";       
  $stmt = $this->DB->prepare($update);
  $stmt->execute();
                
  $update = "CREATE TABLE users ( ". 
            "id int(6) unsigned AUTO_INCREMENT, username varchar(64),
            password varchar(255), PRIMARY KEY (id) );";    
  $stmt = $this->DB->prepare($update);
  $stmt->execute(); 
}
    


    public function getAllQuotations() {
        $stmt = $this->DB->prepare("SELECT * FROM quotations ORDER BY rating DESC;");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAllUsers(){
        $stmt = $this->DB->prepare("SELECT * FROM users;");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function addQuote($quote, $author) {
        $update = "INSERT INTO quotations VALUES (NULL, now(),'" . $quote . "','" . $author . "', 0, 0);";
        $stmt = $this->DB->prepare($update);
        $stmt->execute();
    }
    
    public function addUser($accountname, $psw){
        $update = "INSERT INTO users VALUES (NULL, '" . $accountname . "', '" . $psw . "');";
        $stmt = $this->DB->prepare($update);
        $stmt->execute();
    }   


    public function verifyCredentials($accountName, $psw){
        $stmt = $this->DB->prepare("select * from users where users.username = '" . $accountName . "';");
        $stmt->execute();
        $co = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($co)> 0) {
            $pass = ($co)[0]['password'];
            if ($pass === $psw) {
                return true;
            }
        } else {
            return false;
        }
        
    }
    
    public function raiseRating($ID) {
        $query = "UPDATE quotations SET rating=rating+1 WHERE id='" . $ID . "';" ;
        $stmt = $this->DB->prepare($query);
        $stmt->execute();
    }
    
    public function decreaseRating($ID) {
        $query = "UPDATE quotations SET rating=rating-1 WHERE id='" . $ID . "';" ;
        $stmt = $this->DB->prepare($query);
        $stmt->execute();
    }

    public function deleteQuote($ID) {
        $query = "DELETE FROM quotations WHERE id='" . $ID . "';" ;
        $stmt = $this->DB->prepare($query);
        $stmt->execute();
    }
    
    
    public function register($username, $password) {
        $stmt = $this->DB->prepare("select * from users where users.username = '" . $username . "';");
        $stmt->execute();
        $co = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // > 0 means username exists
        if (count($co)> 0) {
            return false;
        } else {
            // add username and password to users
            $this->addUser($username, $password);
            return true;
        }
        // if it doesn't, then add username and password and return true
        // if it does, return false
    }
}


?>