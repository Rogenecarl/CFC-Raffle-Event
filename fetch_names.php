<?php
include 'db.php';

$sql = "SELECT id, name FROM names";
$result = $conn->query($sql);

$names = [];
while ($row = $result->fetch_assoc()) {
    $names[] = $row;
}

echo json_encode($names);

$conn->close();
?>
