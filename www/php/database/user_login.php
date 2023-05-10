<?php

// load view
include($_SERVER['DOCUMENT_ROOT'] . '/layout/header.php');
include($_SERVER['DOCUMENT_ROOT'] . '/layout/loading.php');
include($_SERVER['DOCUMENT_ROOT'] . '/layout/footer.php');

/* DATABASE VARIABLES */

$servername = DB_SERVER;
$user_db = DB_USER;
$pass_db = DB_PASSWORD;
$database = DB_DATABASE;

/* POST VARIABLES */

$email = $_POST['email'];
$password = $_POST['password'];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $user_db, $pass_db);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // prepare/execute/fetch query
    $sql = "SELECT * FROM users WHERE email=:email AND password=:password";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['email' => $email, 'password' => $password]);
    $result = $stmt->fetch();
    $count = $stmt->rowCount(); // if query returns > 0 columns: login ok!

    // login result
    if($count !== 0){
        
        /* TODO: SAVE SESSION / COOKIE HERE + REDIRECTION TO HOME */
        
        foreach ($result as $key => $value) {
            if($key !== 'password'){   //not save password to sessions
                $_SESSION[$key] = $result[$key];
            }
        }
        
        wait_spinning('Login successfully, now loading...'); //fake loading

    } else  {
        echo "Incorrect e-mail and/or password";

        /* TODO: COUNT ERROR NUMBER FOR BLOCK ACCOUNT */
    }

    
} catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
