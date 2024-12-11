<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);

    if (!empty($name)) {
        // Check if name already exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM names WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            echo json_encode(["success" => false, "message" => "Name already exists."]);
        } else {
            // Add name to the database
            $stmt = $conn->prepare("INSERT INTO names (name) VALUES (?)");
            $stmt->bind_param("s", $name);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Name added successfully."]);
            } else {
                echo json_encode(["success" => false, "message" => "Error adding name."]);
            }

            $stmt->close();
        }
    } else {
        echo json_encode(["success" => false, "message" => "Name cannot be empty."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}

$conn->close();
?>
