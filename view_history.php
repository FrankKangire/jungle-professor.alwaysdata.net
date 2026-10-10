<?php
session_start();
include "connection.php";
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$uid = $_SESSION["user_id"];

// Fetch this user's results grouped by game session
// A session = same user_id + same game_date
$sql = "SELECT
            MIN(id) as session_id,
            player,
            game_type,
            game_date,
            DATE(game_date) as date_group,
            COUNT(*) as total_questions,
            SUM(is_correct) as correct_answers
        FROM permanent_results
        WHERE user_id = ?
        GROUP BY user_id, game_type, game_date
        ORDER BY game_date DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Query failed: " . $conn->error);
}
$stmt->bind_param("i", $uid);
$stmt->execute();
$res = $stmt->get_result();

// Group sessions by calendar date for display
$history = [];
while ($row = $res->fetch_assoc()) {
    $date = $row['date_group'];
    $history[$date][] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game History - Jungle Professor</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/theme.css">
</head>
<!-- data-jp-bg="general" = general background images on this page -->
<body data-jp-bg="general">
<?php $currentPage = 'history';?>
<?php include "navbar.php"; ?>

<div class="container mt-5" style="max-width: 900px;">

    <div class="text-center mb-5">
        <h1 style="color:#2ecc71; text-shadow: 0 0 15px rgba(46,204,113,0.6);">
            📜 Your Adventure Logs
        </h1>
        <p style="color:#a9dfbf;">Your saved game sessions</p>
    </div>

    <?php if (empty($history)): ?>

        <div class="jp-card p-5 text-center">
            <h3>🎮 No saved results yet</h3>
            <p style="color:#a9dfbf;" class="mt-2">
                Complete a game and save your results to see them here.
            </p>
            <a href="server.php" class="btn btn-jp-primary mt-3">
                🎮 Play a Game
            </a>
        </div>

    <?php else: ?>

        <?php foreach ($history as $date => $sessions): ?>

            <!-- Date header -->
            <h4 class="day-header mt-4">
                📅 <?php echo date("F j, Y", strtotime($date)); ?>
            </h4>

            <?php foreach ($sessions as $session): ?>

                <!-- One card per game session -->
                <div class="jp-card p-4 mb-4">

                    <!-- Session header -->
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <div>
                            <span style="font-size:1.3rem;">
                                <?php echo $session['game_type'] === 'jungle' 
                                    ? '🌿 Jungle Professor' 
                                    : '🏙️ City Tour'; ?>
                            </span>
                        </div>
                        <div style="color:#a9dfbf; font-size:0.9rem;">
                            🕒 <?php echo date("g:i A", strtotime($session['game_date'])); ?>
                        </div>
                    </div>

                    <!-- Score summary -->
                    <div class="mb-3 p-3" style="
                        background: rgba(46,204,113,0.1); 
                        border-radius:8px; 
                        border: 1px solid rgba(46,204,113,0.3);">
                        <span style="color:#2ecc71; font-weight:bold;">
                            ✅ Score: <?php echo $session['correct_answers']; ?> 
                            / <?php echo $session['total_questions']; ?> correct
                        </span>
                    </div>

                    <!-- View full session button -->
                    <a href="session_detail.php?date=<?php echo urlencode($session['game_date']); ?>&uid=<?php echo $uid; ?>" 
                       class="btn btn-jp-secondary btn-sm">
                        View All Questions →
                    </a>

                </div>

            <?php endforeach; ?>

        <?php endforeach; ?>

    <?php endif; ?>

</div>

<script src="bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
<script src="js/slideshow.js"></script>
</body>
</html>
