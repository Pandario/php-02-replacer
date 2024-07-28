<!DOCTYPE html>
<html>
<head>
    <title>Input</title>
</head>
<body>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "mysql";

    if (isset($_GET['error']) && $_GET['error'] == 'invalid'): ?>
        <p style="color: red;">Letters only, Alstublieft!</p>
    <?php endif; ?>

    <form action="submit_word.php" method="post">
        <label for="word">Enter a word:</label>
        <input type="text" id="word" name="word" required>
        <button type="submit">Send</button>
    </form>

    <br>
    <form action="" method="get">
        <input type="hidden" name="show" value="<?php echo isset($_GET['show']) && $_GET['show'] == 'all' ? 'none' : 'all'; ?>">
        <button type="submit"><?php echo isset($_GET['show']) && $_GET['show'] == 'all' ? 'Hide words' : 'Show all words'; ?></button>
    </form>

    <?php if (isset($_GET['show']) && $_GET['show'] == 'all'): ?>
        <?php include 'fetch_words.php'; ?>
    <?php endif; ?>

    <?php if (isset($_GET['selected_word_id'])): ?>
        <?php include 'modify_form.php'; ?>

        
        <form action="index.php" method="post">
            <label for="related_words_child">Child words for "<?php echo htmlspecialchars($selectedWord); ?>":</label>
            <select name="related_words_child" id="related_words_child">
                <?php
                // child 
                try {
                    $con = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $stmt = $con->prepare("SELECT id, word FROM first_word WHERE original_word_id = :original_word_id");
                    $stmt->bindParam(':original_word_id', $selectedWordId);
                    $stmt->execute();

                    $childWords = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch(PDOException $e) {
                    echo 'Error: ' . $e->getMessage();
                }
                $con = null;
                ?>

                <?php if (count($childWords) > 0): ?>
                    <?php foreach ($childWords as $word): ?>
                        <option value="<?php echo htmlspecialchars($word['id']); ?>">
                            <?php echo htmlspecialchars($word['word']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">No child</option>
                <?php endif; ?>
            </select>
            <br>
            <label for="related_words_parent">Parent <?php echo htmlspecialchars($selectedWord); ?>":</label>
            <select name="related_words_parent" id="related_words_parent">
                <?php
                // parent
                try {
                    $con = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $stmt = $con->prepare("SELECT id, word FROM first_word WHERE id = (SELECT original_word_id FROM first_word WHERE id = :id)");
                    $stmt->bindParam(':id', $selectedWordId);
                    $stmt->execute();

                    $parentWords = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch(PDOException $e) {
                    echo 'Error: ' . $e->getMessage();
                }
                $con = null;
                ?>

                <?php if (count($parentWords) > 0): ?>
                    <?php foreach ($parentWords as $word): ?>
                        <option value="<?php echo htmlspecialchars($word['id']); ?>">
                            <?php echo htmlspecialchars($word['word']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">No parent</option>
                <?php endif; ?>
            </select>
            <br>
            <button type="submit">Select</button>
        </form>
    <?php endif; ?>
</body>
</html>