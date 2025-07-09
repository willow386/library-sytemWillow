<?php
require_once 'dbconnect.php';

session_start();
$errors=[];

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['signup'])){
    $ID=$_POST['ID'];
    $email=filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $name=$_POST['name'];
    $password=$_POST['password'];
    $confirm_password=$_POST['confirm_password'];
    $created_at=date('Y-m-d H:i:s');

    if(!filter_var($email, FILTER_SANITIZE_EMAIL)){
        $errors['name']='Invalid email format';
    }

    if(empty($name)){
        $errors['name']='Name is required';
    }
    if(strlen($password)<8){
        $error['password']='Password must be at least 8 characters long.';
    }
    if($password !== $confirm_password){
        $errors['confirm_password']='Password does not match';
    }
    $stmt=$pdo->prepare('SELECT * FROM users WHERE email=:email');
    $stmt->execute(['email'=>$email]);
    if($stmt->fetch()){
        $errors['user_exist']='Email is already registered';
    }
    if (!empty($errors)){
        $_SESSION['errors']=$errors;
        header('Location: register.php');
        exit();
    }

    $hashPassword=password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare('INSERT INTO users(ID, email, password, name, created_at)
                         VALUES (:ID, :email, :password, :name, :created_at)');

    $stmt->execute([
        'ID'=>$ID,
        'email'=>$email,
        'password'=>$hashPassword,
        'name'=>$name,
        'created_at' => $created_at
    ]);
    header('Location: index.php');
    exit();
}

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['signin'])){
    $email=filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password=$_POST['password'];

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors['email'] = 'Invalid email format';
    }
    if(empty($password)){
        $error['password'] = 'Password cannot be empty';
    }
    if(!empty($errors)) {
        $_SESSION['errors']=$errors;
        header('Location: index.php');
        exit();
    }
    $stmt=$pdo->prepare("SELECT * FROM users WHERE email=:email");
    $stmt->execute(['email'=>$email]);
    $user=$stmt->fetch();

    if($user && password_verify($password, $user['password'])){
        $_SESSION['user']=[
            'id'=>$user['id'],
            'email'=>$user['email'],
            'name'=>$user['name'],
            'created_at'=>$user['created'],
        ];
        header('Location: home.php');
        exit();
    }
    else{
        $errors['login']='Invalid email or password';
        $_SESSION['errors']=$errors;
        header('Location: index.php');
        exit();
    }
}