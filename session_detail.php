<?php
session_start();
include "connection.php";
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$uid = $_SESSION["user_id"];
$game_date = $_GET['date'] ?? '';

if (empty($game_date)) {
    header("Location: view_history.php");
    exit();
}

// Fetch all questions for this specific session
// Locked to this user only for security
$stmt = $conn->prepare("
    SELECT question_text, user_answer, correct_answer, 
           is_correct, player, game_type, game_date
    FROM permanent_results
    WHERE user_id = ? AND game_date = ?
    ORDER BY id ASC
");
$stmt->bind_param("is", $uid, $game_date);
$stmt->execute();
$res = $stmt->get_result();
$questions = $res->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$gameType = !empty($questions) ? $questions[0]['game_type'] : 'jungle';
$correct = array_sum(array_column($questions, 'is_correct'));
$total = count($questions);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Detail - Jungle Professor</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/theme.css">
</head>
<body data-jp-bg="<?php echo $gameType; ?>">

<?php include "navbar.php"; ?>

<div class="container mt-5" style="max-width: 800px;">

    <a href="view_history.php" class="btn btn-jp-secondary btn-sm mb-4">
        ← Back to History
    </a>

    <div class="jp-card p-4 mb-4">
        <h3 style="color:#2ecc71;">
            <?php echo $gameType === 'jungle' ? '🌿 Jungle Professor' : '🏙️ City Tour'; ?>
        </h3>
        <p style="color:#a9dfbf;">
            🕒 <?php echo date("F j, Y g:i A", strtotime($game_date)); ?>
        </p>
        <div style="
            background: rgba(46,204,113,0.1);
            border: 1px solid rgba(46,204,113,0.3);
            border-radius: 8px;
            padding: 12px 16px;">
            <strong style="color:#2ecc71;">
                Score: <?php echo $correct; ?> / <?php echo $total; ?> correct
            </strong>
        </div>
    </div>

    <?php foreach ($questions as $q): ?>
        <div class="jp-card p-4 mb-3" style="
            border-left: 4px solid <?php echo $q['is_correct'] ? '#2ecc71' : '#e74c3c'; ?>;">

            <p style="font-weight:bold; font-size:1.05rem;">
                ❓ <?php echo htmlspecialchars($q['question_text']); ?>
            </p>

            <div class="p-2 mb-2" style="
                background: <?php echo $q['is_correct'] 
                    ? 'rgba(46,204,113,0.15)' 
                    : 'rgba(231,76,60,0.15)'; ?>;
                border-radius: 6px;">
                <?php echo $q['is_correct'] ? '✅' : '❌'; ?>
                <strong>Your answer:</strong>
                <?php echo htmlspecialchars($q['user_answer']); ?>
            </div>

            <?php if (!$q['is_correct']): ?>
                <div class="p-2" style="
                    background: rgba(46,204,113,0.1);
                    border-radius: 6px;">
                    ✔️ <strong>Correct answer:</strong>
                    <?php echo htmlspecialchars($q['correct_answer']); ?>
                </div>
            <?php endif; ?>

        </div>
    <?php endforeach; ?>

</div>

<script src="bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
<script src="js/slideshow.js"></script>
</body>
</html>
