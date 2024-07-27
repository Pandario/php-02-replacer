<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mysql";

try {
    $con = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $con->prepare("SELECT word FROM first_word ORDER BY id DESC");
    $stmt->execute();

    $words = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($words) > 0) {
        foreach ($words as $word) {
            echo htmlspecialchars($word['word']) . "<br>";
        }
    } else {
        echo "Add words!";
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$con = null;
?>