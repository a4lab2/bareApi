<?php
require_once "database.php";
class USER
{
    private $connection;
    public function __construct()
    {
        $db=new Database;
        $this->connection=$db->makeConnection();
    }
    
    // User Registration, Username and email Validation, insert into user table and savings.
    public function register($email,$pass)
    {   
        try{            
            $stmt = $this->connection->prepare("INSERT INTO users(email,pass) 
			                                             VALUES(:b,:c)");
            $stmt->bindparam(":b", $email);
            $stmt->bindparam(":c", $pass);

            $stmt->execute();

            return true;
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }

    //Page Redirection
    public function redirect($url)
    {
        header("Location: $url");
    }
    // random characters generation
    public function random($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }

    //User Login with Email
    public function fetchByMail($email)
    {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM users WHERE email=:a");
            $stmt->bindparam(':a', $email);
            $stmt->execute();
            $resultRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($resultRow > 0) {
               return $resultRow;
            } else {
                return false;
            }
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }




    public function deleteUser($email)
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM users WHERE email=$email");
            $stmt->bindparam(':a', $email);
            if ($stmt->execute()) {
               return true;
            } else {
                return false;
            }
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }
    public function login($email, $pass)
    {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM users WHERE email=:u");
            $stmt->bindparam(':u', $email);
            $stmt->execute();
            return $resultRow=$stmt->fetch(PDO::FETCH_ASSOC);
           
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }
    
    // check if user is logged in
    public function isLoggedIn()
    {
        if (isset($_SESSION['userStatus'])) {
            return true;
        } else {
            return false;
        }
    }
    // logout
    public function logout()
    {
        unset($_SESSION['userStatus']);
        unset($_SESSION);
        session_destroy();
    }


    // check email (@/com)
    public function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }


    // fetch all users for cron job.
    public function fetchAllUsers()
    {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM users");
            $stmt->execute();
            $resultRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resultRow;
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }
 

}
