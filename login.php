<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        /* Basic styling */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            background-color: #2c3e50;
            color: #ecf0f1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        h2 {
            color: #ecf0f1;
            margin-bottom: 20px;
        }
        p {
            margin: 0;
        }
        /* Navigation Bar styling */
        .navbar {
            width: 100%;
            overflow: hidden;
            background-color: #2c3e50;
            position: fixed;
            top: 0;
        }

        .navbar a {
            float: left;
            display: block;
            color: #ecf0f1;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            transition: background-color 0.3s, color 0.3s;
        }

        .navbar a:hover {
            background-color: #34495e;
            color: #ecf0f1;
        }

        .navbar a.active {
            background-color: #e74c3c;
            color: white;
        }

        /* Form container */
        .login-container {
            width: 300px;
            background-color: #34495e;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .login-container h2 {
            text-align: center;
        }

        /* Form styling */
        .login-form {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            color: #ecf0f1;
            margin-bottom: 5px;
            display: block;
        }

        .form-group input {
            padding: 10px;
            border: none;
            border-radius: 3px;
            font-size: 16px;
        }

        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: calc(100% - 20px); /* Adjust according to padding */
        }

        .form-group input:focus {
            outline: none;
            box-shadow: 0 0 5px #3498db;
        }

        button[type="submit"] {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #c0392b;
        }

        .error-message {
            color: #e74c3c;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <div class="navbar">
        <a href="index.html">Register</a>
        <a href="login.php" class="active">Login</a>
        <a href="booking_details.php" class="">Booking Details</a>
    </div>

    <div class="login-container">
        <form action="login.php" method="post" class="login-form">
            <h2>Login</h2>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
            <div class="error-message">
                <?php
                session_start();
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    include 'db.php';

                    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
                    $password = mysqli_real_escape_string($conn, $_POST['password']);

                    $sql = "SELECT * FROM users WHERE phone='$phone'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        if (password_verify($password, $row['password'])) {
                            $_SESSION['user_id'] = $row['id'];
                            header("Location: dashboard.php");
                            exit;
                        } else {
                            echo "Invalid password.";
                        }
                    } else {
                        echo "No user found with that phone number.";
                    }

                    $conn->close();
                }
                ?>
            </div>
        </form>
    </div>
</body>
</html>
