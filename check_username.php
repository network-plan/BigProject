<?php
require_once 'db_connection.php';
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['username'])) {
    $username = $_POST['username'];
    
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM members WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    echo json_encode(['exists' => $row['count'] > 0]);
    $stmt->close();
    $conn->close();
    exit();
}
?>