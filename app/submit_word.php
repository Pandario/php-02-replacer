<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mysql";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $word = strtolower($_POST['word']);


    if (!preg_match('/^[a-zA-Z]+$/', $word)) {
        header("Location: index.php?error=invalid");
        exit();
    }

    try {
        $con = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // smallest D check
        $stmt = $con->prepare("CALL GetSmallestAvailableID(@smallestID)");
        $stmt->execute();
        $stmt->closeCursor();


        $stmt = $con->query("SELECT @smallestID AS smallestID");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $smallestID = $row['smallestID'];

        
        $stmt = $con->prepare("INSERT INTO first_word (id, word) VALUES (:id, :word)");
        $stmt->bindParam(':id', $smallestID);
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