<?php
$db = new PDO("mysql:host=localhost;dbname=tutorial;charset=utf8mb4", "root", "");

$db->exec("
CREATE TABLE IF NOT EXISTS ads (
id INT AUTO_INCREMENT PRIMARY KEY,
title VARCHAR(255) NOT NULL,
descroption TEXT NOT NULL,
category ENUM('kupno', 'sprzedaż', 'zamiana') NOT NULL
)
");

$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {
    case "GET":
        $stmt = $db->query("SELECT * FROM ads");
        $ads = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($ads);
        break;

    case "POST":    
        $input = json_decode(file_get_contents("php://input"), true);
        $stmt = $db->prepare("INSERT INTO ads(title, description, category)
        VALUES (:title, :description, :category)");
        $stmt->execute($input);
        break;

    case "DELETE":
        $id = $_GET["id"];
        $stmt = $db->prepare("DELETE FROM ads WHERE id = :id");
        $stmt->execute(["id" => $id]);
        break;

}

header("Content-Type", "application/json");


?>
