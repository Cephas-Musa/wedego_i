<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
        header('location: login.php');
    }
}
function cleanInput($data)
{
    return htmlspecialchars(trim($data));
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        .body {
            background-image: url("https://images.unsplash.com/photo-1477346611705-65d1883cee1e?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
        }

        .box {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 90vh;
        }

        .container {
            width: 350px;
            display: flex;
            flex-direction: column;
            padding: 0 15px 0 15px;
        }

        span {
            color: #fff;
            font-size:3.5rem;
            font-weight: bolder;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            display: flex;
            justify-content: center;
            padding: 10px 0 80px 0;
        }

        header {
            color: #fff;
            font-size: 30px;
            display: flex;
            justify-content: center;
            padding: 0 0 15px 0;
        }

        .input-field {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
        }

        .input-field .input {
            height: 45px;
            width: 87%;
            border: none;
            outline: none;
            border-radius: 30px;
            color: #fff;
            padding: 0 0 0 42px;
            background: rgba(255, 255, 255, 0.1);
        }

        i {
            position: relative;
            top: -31px;
            left: 17px;
            color: #fff;
        }

        ::-webkit-input-placeholder {
            color: #fff;
        }

        .submit {
            border: none;
            border-radius: 30px;
            font-size: 15px;
            height: 45px;
            outline: none;
            width: 100%;
            background: rgba(255, 255, 255, 0.7);
            cursor: pointer;
            transition: .3s;
        }

        .submit:hover {
            box-shadow: 1px 5px 7px 1px rgba(0, 0, 0, 0.9);
            background-color: #8ab19f76;
        }

        .bottom {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            font-size: small;
            color: #fff;
            margin-top: 10px;
        }

        .left {
            display: flex;
        }

        label a {
            color: #fff;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="body">
        <div class="box">
            <div class="container">
                <div class="top-header">
                    <span>We.De.Goo</span>
                    <header>inscription</header>
                </div>
                <form action="" method="POST">
                    <div class="input-field">
                        <input type="text" name="username" class="input" placeholder="username" required>
                        <i class="bx bx-user"></i>
                    </div>
                    <div class="input-field">
                        <input type="text" name="email" class="input" placeholder="Email" required>
                        <i class="bx bx-user"></i>
                    </div>
                    <div class="input-field">
                        <input type="password" name="password" class="input" placeholder="password" required>
                        <i class="bx bx-lock-alt"></i>
                    </div>
                                        <label style="color: #fff;">Role</label><br>
                    <select name="role">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                    <div class="input-field">
                        <button type="submit" class="submit">Register</button>
                    </div>


                </form>
            </div>
        </div>
    </div>
</body>

</html>