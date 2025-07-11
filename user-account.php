
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
    $role = isset($_POST['role']) ? $_POST['role'] : 'user';  

    if(!filter_var($email, FILTER_SANITIZE_EMAIL)){
        $errors['email']='Invalid email format';
    }

    if(empty($name)){
        $errors['name']='Name is required';
    }
    if(strlen($password)<8){
        $errors['password']='Password must be at least 8 characters long.';
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
    $stmt = $pdo->prepare('INSERT INTO users(ID, email, password, name, created_at, role)
                         VALUES (:ID, :email, :password, :name, :created_at, :role)');

    $stmt->execute([
        'ID'=>$ID,
        'email'=>$email,
        'password'=>$hashPassword,
        'name'=>$name,
        'created_at' => $created_at,
        'role'=>$role
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
        $errors['password'] = 'Password cannot be empty';
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
            'ID'=>$user['ID'],
            'email'=>$user['email'],
            'name'=>$user['name'],
            'created_at'=>$user['created'],
            'role'=>$user['role']
        ];
        $_SESSION['user_id'] = $user['ID'];
        // Role-based redirect here:
    if ($user['role'] === 'admin') {
        header('Location: Admindashboard.php');
    } else {
        header('Location: userdashboard.php');
    }

    }
    else{
        $errors['login']='Invalid email or password';
        $_SESSION['errors']=$errors;
        header('Location: index.php');
        exit();
    }
}
