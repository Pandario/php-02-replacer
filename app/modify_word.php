<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mysql";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $originalWordId = $_POST['original_word_id'];
    $originalWord = strtolower($_POST['original_word']);
    $letter1 = strtolower($_POST['letter1']);
    $letter2 = strtolower($_POST['letter2']);

    
    if (!preg_match('/^[a-zA-Z]$/', $letter1) || !preg_match('/^[a-zA-Z]$/', $letter2) || $letter1 == $letter2) {
        header("Location: index.php?error=invalid&selected_word_id=$originalWordId&selected_word=$originalWord");
        exit();
    }

    $newWord = str_replace($letter1, $letter2, $originalWord);

    try {
        $con = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $con->prepare("CALL GetSmallestAvailableID(@smallestID)");
        $stmt->execute();
        $stmt->closeCursor();

        $stmt = $con->query("SELECT @smallestID AS smallestID");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $smallestID = $row['smallestID'];

        $stmt = $con->prepare("INSERT INTO first_word (id, word, original_word_id) VALUES (:id, :word, :original_word_id)");
        $stmt->bindParam(':id', $smallestID);
        $stmt->bindParam(':word', $newWord);
        $stmt->bindParam(':original_word_id', $originalWordId);

        $stmt->execute();

        header("Location: index.php");
        exit();
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $con = null;
}
?>