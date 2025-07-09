<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="style.css" />
    
</head>
<body>
    <maim class ="content">
    <h2 class="button-like">User Profile</h2>
    <form class = "profile-form">
        <div class="input-group">
            <label for="fullname">Full Name:</label>
            <input type="text" id="fullname" name="fullname" value=" Olaseinde Timileyin" />
        </div>
        <div class="input-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="Olatimi2014@gmail.com"/>
     </div>
     <div class="input-group">
        <label for="Username">Username:</label>
        <input type="text" id="username" name="username" value="Timmy" />
     </div>
     <button class="btn" type="submit">Update Profile</button>
     <button class="btn" type="button" onclick="window.location.href='dashboard.php'">Back to Dashboard</button>
    </form>
    
</body>
</html>