<!DOCTYPE html>
<html>
<head>
    <title>Input</title>
</head>
<body>
    <?php if (isset($_GET['error']) && $_GET['error'] == 'invalid'): ?>
        <p>Letters only, Alstublieft!</p>
    <?php endif; ?>

    <form action="submit_word.php" method="post">
        <label for="word">Enter a word:</label>
        <input type="text" id="word" name="word" required>
        <button type="submit">Send</button>
    </form>

    <br>
    <form action="" method="get">
        <input type="hidden" name="show" value="<?php echo isset($_GET['show']) && $_GET['show'] == 'all' ? 'none' : 'all'; ?>">
        <button type="submit">Toggle words</button>
    </form>

    <?php if (isset($_GET['show']) && $_GET['show'] == 'all'): ?>
        <div id="word-list">
            <?php include 'fetch_words.php'; ?>
        </div>
    <?php endif; ?>

</body>
</html>