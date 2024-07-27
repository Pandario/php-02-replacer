<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mysql";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $con = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $word = strtolower($_POST['word']);

    
        $stmt = $con->prepare("INSERT INTO first_word (word) VALUES (:word)");
        $stmt->bindParam(':word', $word);

        $stmt->execute();

 
        header("Location: index.php");
        exit();
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $con = null;
}
?>