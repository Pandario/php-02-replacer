<?php
if (isset($_GET['selected_word_id'])) {
    $selectedWordId = $_GET['selected_word_id'];
    $selectedWord = '';

    try {
        $con = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $con->prepare("SELECT word FROM first_word WHERE id = :id");
        $stmt->bindParam(':id', $selectedWordId);
        $stmt->execute();

        $selectedWord = $stmt->fetch(PDO::FETCH_ASSOC)['word'];
    } catch(PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }

    $con = null;
}
?>

<form action="modify_word.php" method="post">
    <input type="hidden" name="original_word_id" value="<?php echo htmlspecialchars($selectedWordId); ?>">
    <input type="hidden" name="original_word" value="<?php echo htmlspecialchars($selectedWord); ?>">
    <input readonly name="original_word" value="<?php echo htmlspecialchars($selectedWord); ?>">
    <br>
    <label for="letter1">letter you wanna change:</label>
    <input type="text" id="letter1" name="letter1" maxlength="1" required>
    <br>
    <label for="letter2">letter would be instead:</label>
    <input type="text" id="letter2" name="letter2" maxlength="1" required>
    <br>
    <button type="submit">Modify</button>
</form>