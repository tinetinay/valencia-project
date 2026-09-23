<?php
$conn = new mysqli(
    "localhost",
    "root",
    "",
    "phpcrudkristine"
);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>