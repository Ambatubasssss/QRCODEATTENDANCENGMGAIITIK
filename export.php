<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: /QRCodeAttendance/index.php");
    exit();
}

// Database credentials
$server = "localhost";
$username = "root";
$password = "";
$dbname = "qrdb";

// Create a connection to the database
$conn = new mysqli($server, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set the filename for the export
$filename = 'AttendanceReport-' . date('Y-m-d') . '.csv';

// Query to fetch attendance data
$sql = "SELECT student.LASTNAME, student.FIRSTNAME, student.MNAME, attendance.STUDENTID, attendance.TIMEIN, attendance.TIMEOUT, attendance.LOGDATE
        FROM attendance
        LEFT JOIN student ON attendance.STUDENTID = student.STUDENTID";
$result = $conn->query($sql);

// Open the output stream to write the CSV file
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Open the PHP output stream
$output = fopen('php://output', 'w');

// Add the column headers for the CSV
fputcsv($output, ['LASTNAME', 'FIRSTNAME', 'MIDDLE NAME', 'STUDENT ID', 'TIME IN', 'TIME OUT', 'LOG DATE']);

// Fetch and write each row of data to the CSV
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['LASTNAME'],
        $row['FIRSTNAME'],
        $row['MNAME'],
        $row['STUDENTID'],
        $row['TIMEIN'],
        $row['TIMEOUT'],
        $row['LOGDATE']
    ]);
}

// Close the output stream
fclose($output);

// Close the database connection
$conn->close();
exit();
?>
