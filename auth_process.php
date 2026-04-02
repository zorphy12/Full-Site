<?php
session_start();
require_once 'config.php';

if (isset($_POST['register_btn'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check_email = $conn->query("SELECT email FROM users WHERE email ='$email'");
    if($check_email->num_rows > 0){
        $_SESSION['alerts'][] = [
            'type' => 'error',
            'message' => 'Email already registered!'
        ];
        $_SESSION['active_form'] = 'register';
    } else {
        $conn->query("INSERT INTO users(name, email, password) VALUES('$name', '$email', '$password')");
        $_SESSION['alerts'][] = [
            'type' => 'success',
            'message' => 'Registration successful! Please login.'
        ];
        $_SESSION['active_form'] = 'login';
    }
    header("Location: sample.php");
    exit();   
}

if (isset($_POST["login_btn"])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email = '$email'");
    
    if($result && $result->num_rows > 0){
        $user = $result->fetch_assoc();
        if(password_verify($password, $user['password'])){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['logged_in'] = true;
            
            $_SESSION['alerts'][] = [
                'type' => 'success',
                'message' => 'Login successful! Welcome back, ' . $user['name'] . '!'
            ];
        } else {
            $_SESSION['alerts'][] = [
                'type' => 'error',
                'message' => 'Invalid email or password!'
            ];
            $_SESSION['active_form'] = 'login';
        }
    } else {
        $_SESSION['alerts'][] = [
            'type' => 'error',
            'message' => 'Invalid email or password!'
        ];
        $_SESSION['active_form'] = 'login';
    }
    
    header('Location: sample.php');
    exit();
}
?>