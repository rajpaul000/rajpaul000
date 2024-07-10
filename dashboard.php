<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

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

// Query to get available buses
$sql = "SELECT * FROM buses";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <style>
        /* Basic styling */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            background-color: #f0f0f0;
            color: #333;
            text-align: center;
        }

        h1,
        h2 {
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        p {
            margin: 0;
        }

        button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            border-radius: 4px;
        }

        button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .fade-in {
            animation: fadeIn 1s ease-in-out;
        }

        .thank-you-message {
            display: none;
            color: #28a745;
            font-weight: bold;
            margin-top: 20px;
            animation: fadeIn 1s ease-in-out;
        }

        /* Navigation Bar styling */
        .navbar {
            overflow: hidden;
            background-color: #343a40;
            padding: 10px 0;
        }

        .navbar a {
            float: left;
            display: block;
            color: #f8f9fa;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            font-size: 18px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .navbar a:hover {
            background-color: #495057;
            color: white;
        }

        .navbar a.active {
            background-color: #007BFF;
            color: white;
        }

        /* Main container styling */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* User welcome message */
        .welcome {
            background-color: #007BFF;
            color: white;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        /* Bus info styling */
        .bus-info {
            background-color: white;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
    <script>
        // Function to show thank you message
        function showThankYouMessage() {
            document.getElementById('thank-you').style.display = 'block';
        }
    </script>
</head>

<body>
    <!-- Navigation Bar -->
    <div class="navbar">
        <a href="index.html">Register</a>
        <a href="login.php">Login</a>
        <a href="booking_details.php">Booking Details</a>
    </div>

    <div class="container">
        <div class="welcome">
            <h1>Welcome <?php echo $user_name; ?></h1>
        </div>
        <h2>Available Buses</h2>
        <form action="dashboard.php" method="post">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='bus-info fade-in'>";
                    echo "<p>Bus Name: " . $row['name'] . " - Time: " . $row['time'] . "</p>";
                    echo "<button type='submit' name='book_bus' value='" . $row['id'] . "' onclick='showThankYouMessage()'>Book Now</button>";
                    echo "</div>";
                }
            } else {
                echo "<p class='fade-in'>No buses available.</p>";
            }
            ?>
        </form>

        <!-- Thank You Message -->
        <div id="thank-you" class="thank-you-message">
            Thank you for booking!
        </div>
    </div>
</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_bus'])) {
    $bus_id = $_POST['book_bus'];

    // Insert booking into bookings table
    $sql_insert_booking = "INSERT INTO bookings (user_id, bus_id, booking_time) VALUES ('$user_id', '$bus_id', NOW())";
    if ($conn->query($sql_insert_booking) === TRUE) {
        // Display a JavaScript alert (optional)
        echo "<script>alert('Booking successful!');</script>";
    } else {
        echo "Error: " . $sql_insert_booking . "<br>" . $conn->error;
    }
}

$conn->close();
?>
