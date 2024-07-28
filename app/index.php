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
        <form action="index.php" method="get">
            <label for="selected_word_id">Select word:</label>
            <select name="selected_word_id" id="selected_word_id" required>
                <?php
                try {
                    $con = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $stmt = $con->prepare("SELECT id, word FROM first_word ORDER BY id DESC");
                    $stmt->execute();

                    $words = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (count($words) > 0) {
                        foreach ($words as $word) {
                            echo '<option value="' . htmlspecialchars($word['id']) . '">' . htmlspecialchars($word['word']) . '</option>';
                        }
                    } else {
                        echo '<option value="">Add words!</option>';
                    }
                } catch(PDOException $e) {
                    echo 'Error: ' . $e->getMessage();
                }

                $con = null;
                ?>
            </select>
            <button type="submit">Choose</button>
        </form>
    <?php endif; ?>

    <?php if (isset($_GET['selected_word_id'])): ?>
        <?php include 'modify_form.php'; ?>
    <?php endif; ?>
</body>
</html>