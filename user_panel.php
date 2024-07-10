user_panel<?php
include 'db.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT b.name, b.time, bk.booking_time FROM bookings bk JOIN buses b ON bk.bus_id = b.id WHERE bk.user_id = '$user_id'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Panel</title>
</head>
<body>
    <h1>Your Bookings</h1>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "Bus Name: " . $row['name'] . " - Time: " . $row['time'] . " - Booking Time: " . $row['booking_time'] . "<br>";
        }
    } else {
        echo "No bookings found.";
    }
    ?>
</body>
</html>

<?php
$conn->close();
?>
