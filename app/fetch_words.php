<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mysql";

$selectedWordId = null;
$selectedWord = null;

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['selected_word_id'])) {
    $selectedWordId = $_GET['selected_word_id'];
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

try {
    $con = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $con->prepare("SELECT id, word FROM first_word ORDER BY id DESC");
    $stmt->execute();

    $words = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}

$con = null;
?>

<form action="index.php" method="get">
    <label for="selected_word_id">Select word:</label>
    <select name="selected_word_id" id="selected_word_id" required>
        <?php if (count($words) > 0): ?>
            <?php foreach ($words as $word): ?>
                <option value="<?php echo htmlspecialchars($word['id']); ?>" <?php echo $selectedWordId == $word['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($word['word']); ?>
                </option>
            <?php endforeach; ?>
        <?php else: ?>
            <option value="">Add words!</option>
        <?php endif; ?>
    </select>
    <?php if ($selectedWordId): ?>
        <input type="hidden" name="selected_word" value="<?php echo htmlspecialchars($selectedWord); ?>">
    <?php endif; ?>
    <button type="submit">Choose</button>
</form>

<?php if ($selectedWord): ?>
    <p>You selected: <?php echo htmlspecialchars($selectedWord); ?></p>
    <a href="index.php">Back</a>
<?php endif; ?>