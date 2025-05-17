<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = new mysqli("localhost", "root", "", "negara");
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
    $username = $db->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT password FROM users WHERE username = '$username' LIMIT 1";
    $result = $db->query($sql);

    if ($result && $result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if ($row['password'] === $password) {
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit();
        } else {
            $error = "Username or password is incorrect.";
        }
    } else {
        $error = "Username or password is incorrect.";
    }
    $db->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Login - Country App</title>
<style>
    *{
        margin:0;
        padding:0;
        box-sizing:border-box;
        font-family:"poppins",sans-serif;
    }

    body{
        background-color: #647091;
    }

    .cointaner{
        background: #fff;
        width: 450px;
        padding: 2rem;
        margin: 50px auto;
        border-radius: 10px;
        box-shadow: 0 20px35px rgba(0,0,1,0.9)
    }

    form{
        margin: 1rem;
    }

    .form-title{
        font-size: 2rem;
        font-weight: bold;
        text-align: center;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    input{
        color: inherit;
        width: 100%;
        background-color: transparent;
        font-size: 15px;
        border: 2px solid;
        border-radius: 8px;
        padding: 5px;
        margin-bottom: 0.5rem;
    }

    .btn{
        font-size:1rem;
        padding:8px 0;
        border-radius: 8px;
        width:25%;
        background: #647091;
        margin-top:0.5rem;
        color:white;
        cursor:pointer;
    }

    .links{
        display:flex;
        justify-content:space-around;
        padding:0 3rem;
    }

    .btn:hover{
        background: #07001f;
        color: blue;
    }

    button{
        color:#647091;
        border:none;
        font-size:1rem;
        background-color:transparent;
    }
</style>
</head>
<body>
    <div class="cointaner" id="login">
        <h1 class="form-title">Login</h1>
        <form method="POST" action="">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" name="password" required>
            </div>
            <input type="submit" class="btn" value="Login"></input>
        </form>
    </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>