<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, phone, password) VALUES ('$name', '$phone', '$password')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: login.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <form action="register.php" method="post">
        Name: <input type="text" name="name" required><br>
        Phone: <input type="text" name="phone" required><br>
        Password: <input type="password" name="password" required><br>
        <button type="submit">Register</button>
    </form>
</body>
</html>
