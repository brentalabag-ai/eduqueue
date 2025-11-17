<?php
require_once "db/config.php";

//Check if user is already logged in
if (isset($_SESSION['user'])) {
    header('Location: index.php'); 
    exit; 
}

//Preparing an error message
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') { //-> Check if login form was submitted

    //Get username and password from the form
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';  //-> ?? '' ensures it doesn’t break if input is missing.

    //Query the database for the username
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $password === $user['password']) { //-> Check if password matches

        //if login is correct -> redirected to dashboard.php
        $_SESSION['user'] = $user;
        header('Location: dashboard.php');
        exit;
    } else {
        $err = 'Invalid credentials'; //-> if login fails
    }
}
?>