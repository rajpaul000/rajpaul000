<?php
include 'db.php';
session_start();

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE phone='$phone'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            header("Location: booking_details.php");
            exit();
        } else {
            $login_error = "Invalid password.";
        }
    } else {
        $login_error = "No user found with that phone number.";
    }
}

// Check if user is already logged in
if (!isset($_SESSION['user_id'])) {
    // Display login form
    ?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Login to View Booking Details</title>
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

            h1,
            h2 {
                color: #ecf0f1;
                margin-bottom: 20px;
            }

            form {
                width: 300px;
                background-color: #34495e;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                transition: transform 0.3s, box-shadow 0.3s;
            }

            form:hover {
                transform: scale(1.05);
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            }

            .form-group {
                margin-bottom: 15px;
            }

            .form-group label {
                display: block;
                margin-bottom: 5px;
            }

            .form-group input {
                width: calc(100% - 20px);
                padding: 10px;
                border: none;
                border-radius: 5px;
                outline: none;
                transition: background-color 0.3s;
            }

            .form-group input:focus {
                background-color: #3c556a;
            }

            button {
                width: 100%;
                padding: 10px;
                border: none;
                border-radius: 5px;
                background-color: #e74c3c;
                color: #ecf0f1;
                font-size: 16px;
                cursor: pointer;
                transition: background-color 0.3s;
            }

            button:hover {
                background-color: #c0392b;
            }

            .error-message {
                color: #e74c3c;
                margin-top: 10px;
                text-align: center;
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

            /* Animation for headings */
            .animated-heading {
                animation: color-change 3s infinite alternate;
            }

            @keyframes color-change {
                0% {
                    color: #ecf0f1;
                }

                100% {
                    color: #e74c3c;
                }
            }

            /* Animation for booking details */
            .booking-item {
                animation: slide-in 1s ease-out;
                background-color: #34495e;
                margin: 10px;
                padding: 10px;
                border-radius: 5px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            }

            @keyframes slide-in {
                0% {
                    opacity: 0;
                    transform: translateY(-20px);
                }

                100% {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    </head>

    <body>
        <!-- Navigation Bar -->
        <div class="navbar">
            <a href="index.html">Register</a>
            <a href="login.php">Login</a>
            <a href="booking_details.php" class="active">Booking Details</a>
        </div>

        <h1 class="animated-heading">Login to View Your Booking Details</h1>
        <form action="booking_details.php" method="post">
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
            <?php if (isset($login_error)) { ?>
                <div class="error-message"><?php echo $login_error; ?></div>
            <?php } ?>
        </form>
    </body>

    </html>
    <?php
    exit(); // Stop further execution if login form is displayed
}

// User is logged in, fetch and display booking details
$user_id = $_SESSION['user_id'];

// Query to fetch user's name
$sql_user = "SELECT name FROM users WHERE id='$user_id'";
$result_user = $conn->query($sql_user);

// Initialize user's name variable
$user_name = "";
if ($result_user->num_rows > 0) {
    $row_user = $result_user->fetch_assoc();
    $user_name = $row_user['name'];
}

// Query to fetch user's bookings
$sql_bookings = "SELECT b.name AS bus_name, b.time AS bus_time, bk.booking_time FROM bookings bk JOIN buses b ON bk.bus_id = b.id WHERE bk.user_id = '$user_id'";
$result_bookings = $conn->query($sql_bookings);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Booking Details</title>
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

        h1,
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

        /* Animation for headings */
        .animated-heading {
            animation: color-change 3s infinite alternate;
        }

        @keyframes color-change {
            0% {
                color: #ecf0f1;
            }

            100% {
                color: #e74c3c;
            }
        }

        /* Animation for booking details */
        .booking-item {
            animation: slide-in 1s ease-out;
            background-color: #34495e;
            margin: 10px;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        @keyframes slide-in {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <div class="navbar">
        <a href="index.html">Register</a>
        <a href="login.php">Login</a>
        <a href="booking_details.php" class="active">Booking Details</a>
    </div>

    <h1 class="animated-heading">Welcome <?php echo $user_name; ?></h1>
    <h2 class="animated-heading">Your Booking Details</h2>
    <?php
    if ($result_bookings->num_rows > 0) {
        while ($row = $result_bookings->fetch_assoc()) {
            echo "<p class='booking-item'>Bus Name: " . $row['bus_name'] . " - Time: " . $row['bus_time'] . " - Booking Time: " . $row['booking_time'] . "</p>";
        }
    } else {
        echo "<p>No bookings found.</p>";
    }
    ?>
</body>

</html>

<?php
$conn->close();
?>