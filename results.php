<?php
session_start();
if(!isset($_SESSION["user_id"])){ header("Location: login.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Game Results</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <script src="js/sweetalert2.all.min.js"></script>
    <style>
        body { background: #2c3e50; font-family: sans-serif; padding-top: 20px; color: white; }
        .result-card { border-radius: 15px; margin-bottom: 15px; border: none; }
        .correct { background-color: #d4edda; color: #155724; border-left: 10px solid #28a745; }
        .incorrect { background-color: #f8d7da; color: #721c24; border-left: 10px solid #dc3545; }
        .footer-btns { position: fixed; bottom: 0; left: 0; width: 100%; background: #34495e; padding: 15px; display: flex; justify-content: space-around; }
        .container { margin-bottom: 100px; }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Game Results</h2>
        <div id="results-list">
            <!-- Results will be injected here via JS -->
        </div>
    </div>

    <div class="footer-btns shadow-lg">
        <button class="btn btn-dark px-4" onclick="saveToDB()"><i class="bi bi-save"></i> Save</button>
        <button class="btn btn-secondary px-4">Share</button>
        <button class="btn btn-danger px-4" onclick="window.location.href='welcome.php'">Done</button>
    </div>

    <script>
        // Retrieve results from Session Storage (saved from the game)
        const gameResults = JSON.parse(sessionStorage.getItem('lastGameResults') || "[]");
        const list = document.getElementById('results-list');

        gameResults.forEach(res => {
            const div = document.createElement('div');
            div.className = `card result-card p-3 ${res.is_correct ? 'correct' : 'incorrect'}`;
            div.innerHTML = `
                <h6>${res.question}</h6>
                <small>Your answer: ${res.user_answer}</small><br>
                <small>Correct answer: ${res.correct_answer}</small>
            `;
            list.appendChild(div);
        });

        function saveToDB() {
    fetch('save_game_results.php', {
        method: 'POST',
        body: JSON.stringify(gameResults)
    }).then(() => {
        // BEAUTIFUL POPUP
        Swal.fire({
            title: 'Saved!',
            text: 'Your progress has been stored in your history.',
            icon: 'success',
            confirmButtonColor: '#27ae60'
        }).then(() => {
            window.location.href = 'welcome.php';
        });
    });
}
    </script>
    <script src="bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
