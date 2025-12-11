<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json; charset=utf-8");

try {
    $db = new PDO("mysql:host=localhost;dbname=tutorial;charset=utf8mb4", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->exec("
    CREATE TABLE IF NOT EXISTS ads (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        category ENUM('kupno', 'sprzedaż', 'zamiana') NOT NULL
    )
    ");

    $method = $_SERVER["REQUEST_METHOD"];

    switch ($method) {
        case "GET":
            $stmt = $db->query("SELECT * FROM ads");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case "POST":
            $input = json_decode(file_get_contents("php://input"), true);
            if ($input && isset($input['title'], $input['description'], $input['category'])) {
                $stmt = $db->prepare("INSERT INTO ads(title, description, category) VALUES (:title, :description, :category)");
                $stmt->execute([
                    'title' => $input['title'],
                    'description' => $input['description'],
                    'category' => $input['category']
                ]);
                echo json_encode(["success" => true]);
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Nieprawidłowe dane"]);
            }
            break;

        case "DELETE":
            if (isset($_GET['id'])) {
                $stmt = $db->prepare("DELETE FROM ads WHERE id = :id");
                $stmt->execute(['id' => $_GET['id']]);
                echo json_encode(["success" => true]);
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Brak ID"]);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(["error" => "Metoda niedozwolona"]);
            break;
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
