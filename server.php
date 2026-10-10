<?php
session_start();
if(!isset($_SESSION["user_id"])){ header("Location: login.php"); exit(); }
require "connection.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Game Room - Jungle Professor</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/theme.css">
    <script src="js/sweetalert2.all.min.js"></script>

    <style>
        :root { --board-size: 85vh; }

        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            overscroll-behavior: none;
            touch-action: none;
            color: white;
            font-family: sans-serif;
        }

        /* =====================================================
           DESKTOP NAVBAR
           Hide the mobile/tablet menu toggle on larger screens.
           Bootstrap's navbar-toggler remains visible below 992px.
           ===================================================== */
        @media (min-width: 992px) {
            .navbar-toggler {
                display: none !important;
            }
        }

        /* Slideshow sits behind everything */
        .jp-bg,
        .jp-bg-overlay {
            position: fixed;
            z-index: 0;
        }

        /* All game views sit above slideshow */
        .view-section {
            display: none;
            position: relative;
            z-index: 1;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 85vh;
            padding: 20px;
        }

        .active-view {
            display: flex !important;
        }

        /* Game selection cards */
        .game-card {
            background: rgba(0, 20, 0, 0.75);
            color: white;
            padding: 15px;
            border-radius: 15px;
            width: 280px;
            text-align: center;
            cursor: pointer;
            transition: 0.3s;
            margin: 15px;
            border: 2px solid rgba(46, 204, 113, 0.4);
            backdrop-filter: blur(8px);
        }

        .game-card:hover {
            transform: translateY(-10px);
            border-color: #27ae60;
            box-shadow: 0 0 20px rgba(46, 204, 113, 0.5);
        }

        .game-card img {
            height: 160px;
            width: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        .game-card h4 {
            color: white;
            text-shadow:
                -1px -1px 0 #000,
                 1px -1px 0 #000,
                -1px  1px 0 #000,
                 1px  1px 0 #000;
            margin-top: 10px;
        }

        /* Board */
        .game-wrapper {
            position: relative;
            z-index: 1;
            width: var(--board-size);
            max-width: 95vw;
            aspect-ratio: 1 / 1;
            background-size: 100% 100%;
            border: 3px solid rgba(46, 204, 113, 0.5);
            box-shadow: 0 0 30px rgba(0,0,0,0.7);
        }

        /* Player tokens */
        .player {
            display: none;
            position: absolute;
            width: 10%;
            height: 10%;
            border-radius: 50%;
            border: 2px solid black;
            transition: all 0.6s cubic-bezier(0.18, 0.89, 0.32, 1.28);
            z-index: 10;
            transform: translate(-50%, -50%);
        }

        #p1 { background: #ff4757; }
        #p2 { background: #ffa502; }
        #p3 { background: #2ed573; }
        #p4 { background: #1e90ff; }

        /* UI bar above board */
        #ui-bar {
            position: relative;
            z-index: 1;
            background: rgba(0, 20, 0, 0.80);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(46, 204, 113, 0.4);
            padding: 15px;
            border-radius: 10px;
            width: var(--board-size);
            max-width: 95vw;
            margin-bottom: 10px;
        }

        #diceBtn:disabled,
        #quitBtn:disabled {
            opacity: 0.5;
        }

        .active-turn {
            background-color: #27ae60 !important;
            color: white !important;
            box-shadow: 0 0 15px #27ae60;
            border: 2px solid white !important;
        }

        /* Waiting screen pulse */
        .pulse {
            animation: pulse-green 2s infinite;
            font-size: 3rem;
            font-weight: bold;
        }

        @keyframes pulse-green {
            0%   { color: #a9dfbf; }
            50%  { color: #27ae60; }
            100% { color: #a9dfbf; }
        }

        /* All headings — white with black outline */
        h1, h2, h3, h4, h5, #turn-txt {
            color: white;
            text-shadow:
                -1px -1px 0 rgba(0,0,0,0.9),
                 1px -1px 0 rgba(0,0,0,0.9),
                -1px  1px 0 rgba(0,0,0,0.9),
                 1px  1px 0 rgba(0,0,0,0.9);
        }
    </style>
</head>

<body data-jp-bg="general">

<?php
// Pass current page to navbar so it can hide the Game Center link
$currentPage = 'server';
include "navbar.php";
?>

<!-- START VIEW -->
<div id="view-start" class="view-section active-view">
    <div class="text-center">
        <h1 class="display-3 fw-bold mb-4">🎮 Game Room</h1>

        <button id="mainStartBtn"
                class="btn btn-lg btn-jp-primary px-5 shadow"
                onclick="showView('view-select')"
                disabled>
            CONNECTING...
        </button>
    </div>
</div>


<!-- GAME SELECT VIEW -->
<div id="view-select" class="view-section">

    <h2 class="mb-4 text-center">Select Your Destination</h2>

    <div class="d-flex flex-wrap justify-content-center">

        <div class="game-card shadow"
             onclick="enterMatchmaking('jungle')">

            <img src="images/IMG-20240301-WA0006.jpg"
                 alt="Jungle Professor">

            <h4>🌿 Jungle Professor</h4>
        </div>


        <div class="game-card shadow"
             onclick="enterMatchmaking('city')">

            <img src="images/IMG-20240305-WA0002.jpg"
                 alt="City Tour">

            <h4>🏙️ City Tour</h4>
        </div>

    </div>
</div>


<!-- WAITING VIEW -->
<div id="view-waiting" class="view-section">

    <div class="text-center">

        <h2 id="waiting-title">Finding a Match...</h2>

        <div class="pulse my-4"
             id="wait-count">
            1 / 2
        </div>

        <div class="spinner-border text-success"
             role="status">
        </div>

        <p class="mt-3"
           style="color:#a9dfbf;">
            Waiting for another player to join...
        </p>

    </div>
</div>


<!-- GAME BOARD VIEW -->
<div id="view-board" class="view-section">

    <div id="ui-bar"
         class="d-flex justify-content-between align-items-center">

        <span id="turn-txt"
              class="fw-bold text-uppercase small">
            ...
        </span>

        <div class="d-flex align-items-center gap-2">

            <span>
                🎲
                <b id="dice-val"
                   class="text-warning fs-4">
                    0
                </b>
            </span>

            <button class="btn btn-jp-primary btn-sm"
                    id="diceBtn">
                ROLL
            </button>

            <button class="btn btn-jp-danger btn-sm"
                    id="quitBtn"
                    onclick="quitGame()">
                QUIT
            </button>

        </div>
    </div>


    <div class="game-wrapper"
         id="board-container">

        <div id="p1"
             class="player">
        </div>

        <div id="p2"
             class="player">
        </div>

        <div id="p3"
             class="player">
        </div>

        <div id="p4"
             class="player">
        </div>

    </div>
</div>


<script>

    let clientId,
        gameId,
        myPlayerId = null;

    let selectedMap = 'jungle';

    let currentSum = 0;

    let sessionResults = [];

    let questionResolver = null;

    let answerResolver = null;


    // =========================================================
    // CONNECT TO NODE GAME SERVER
    // =========================================================

    let ws = new WebSocket(
        "wss://jungle-professor.alwaysdata.net/wss"
    );


    // =========================================================
    // BOARD COORDINATES
    // =========================================================

    const tileCoords = {

        // =====================================================
        // JUNGLE PROFESSOR
        // IMG-20240301-WA0006.jpg
        // =====================================================

        jungle: {

            0: { left: '10.08%', top: '89.90%' },
            1:  { left: '27.65%', top: '89.57%' },
            2:  { left: '38.35%', top: '89.57%' },
            3:  { left: '49.87%', top: '89.29%' },
            4:  { left: '61.23%', top: '89.57%' },
            5:  { left: '72.91%', top: '89.57%' },
            6:  { left: '89.70%', top: '89.43%' },
            7:  { left: '88.22%', top: '73.28%' },
            8:  { left: '88.22%', top: '61.77%' },
            9:  { left: '89.20%', top: '50.54%' },
            10: { left: '88.38%', top: '39.03%' },
            11: { left: '88.22%', top: '27.66%' },
            12: { left: '88.88%', top: '10.39%' },
            13: { left: '72.25%', top: '10.39%' },
            14: { left: '61.55%', top: '10.95%' },
            15: { left: '49.54%', top: '10.81%' },
            16: { left: '38.18%', top: '10.95%' },
            17: { left: '26.99%', top: '11.51%' },
            18: { left: '9.55%',  top: '10.67%' },
            19: { left: '9.88%',  top: '27.80%' },
            20: { left: '10.04%', top: '40.01%' },
            21: { left: '9.71%',  top: '49.84%' },
            22: { left: '10.53%', top: '62.05%' },
            23: { left: '9.88%',  top: '73.56%' },
            24: { left: '25.68%', top: '73.28%' },
            25: { left: '38.68%', top: '74.13%' },
            26: { left: '50.20%', top: '74.55%' },
            27: { left: '60.24%', top: '74.41%' },
            28: { left: '72.91%', top: '40.15%' },
            29: { left: '72.25%', top: '26.96%' },
            30: { left: '59.09%', top: '25.83%' },
            31: { left: '49.38%', top: '26.11%' },
            32: { left: '37.85%', top: '26.67%' },
            33: { left: '25.18%', top: '61.21%' }
        },


        // =====================================================
        // CITY TOUR
        // IMG-20240305-WA0002.jpg
        // =====================================================

        city: {

            0: { left: '10.08%', top: '89.90%' },
            1:  { left: '25.54%', top: '89.16%' },
            2:  { left: '37.53%', top: '89.01%' },
            3:  { left: '49.69%', top: '89.01%' },
            4:  { left: '62.20%', top: '89.16%' },
            5:  { left: '74.88%', top: '89.16%' },
            6:  { left: '90.69%', top: '88.27%' },
            7:  { left: '89.47%', top: '74.97%' },
            8:  { left: '89.30%', top: '62.40%' },
            9:  { left: '89.30%', top: '50.13%' },
            10: { left: '89.47%', top: '37.41%' },
            11: { left: '89.65%', top: '25.28%' },
            12: { left: '89.47%', top: '11.24%' },
            13: { left: '74.36%', top: '11.24%' },
            14: { left: '62.37%', top: '11.09%' },
            15: { left: '49.86%', top: '11.24%' },
            16: { left: '37.87%', top: '11.09%' },
            17: { left: '25.71%', top: '10.79%' },
            18: { left: '9.56%',  top: '10.50%' },
            19: { left: '10.42%', top: '25.14%' },
            20: { left: '10.60%', top: '38.00%' },
            21: { left: '10.25%', top: '49.53%' },
            22: { left: '10.08%', top: '62.10%' },
            23: { left: '10.25%', top: '75.41%' },
            24: { left: '25.37%', top: '75.56%' },
            25: { left: '37.87%', top: '75.26%' },
            26: { left: '50.21%', top: '75.56%' },
            27: { left: '62.89%', top: '75.11%' },
            28: { left: '74.01%', top: '37.85%' },
            29: { left: '74.18%', top: '25.58%' },
            30: { left: '61.85%', top: '25.14%' },
            31: { left: '49.17%', top: '24.99%' },
            32: { left: '36.48%', top: '24.99%' },
            33: { left: '25.89%', top: '62.69%' }
        }
    };


    // =========================================================
    // VIEW SWITCHING
    // =========================================================

    function showView(id) {

        document.querySelectorAll('.view-section')
            .forEach(v =>
                v.classList.remove('active-view')
            );

        document.getElementById(id)
            .classList.add('active-view');
    }


    // =========================================================
    // MATCHMAKING
    // =========================================================

    function enterMatchmaking(type) {

        selectedMap = type;

        const boardImg = {
            'jungle': 'images/IMG-20240301-WA0006.jpg',
            'city':   'images/IMG-20240305-WA0002.jpg'
        }[type];

        document.getElementById('board-container')
            .style.backgroundImage = `url("${boardImg}")`;

        showView('view-waiting');

        startGameSlideshow(type);

        ws.send(JSON.stringify({
            method: "find_game",
            clientId: clientId,
            gameType: type
        }));
    }


    // =========================================================
    // WEBSOCKET MESSAGE HANDLER
    // =========================================================

    ws.onmessage = message => {

        const response = JSON.parse(message.data);

        console.log(
            '[WS IN]',
            response.method,
            response
        );


        if (response.method === "connect") {

            clientId = response.clientId;

            document.getElementById("mainStartBtn")
                .disabled = false;

            document.getElementById("mainStartBtn")
                .innerText = "FIND A MATCH";
        }


        if (response.method === "waiting") {

            document.getElementById('wait-count')
                .innerText =
                    response.count + " / " + response.max;
        }


        if (response.method === "join") {

            gameId = response.game.id;

            response.game.clients.forEach(c => {

                if (c.clientId === clientId) {
                    myPlayerId = c.player;
                }

                document.getElementById(c.player)
                    .style.display = "block";
            });

            showView('view-board');

            updatePositions(null);
        }


        if (response.method === "update" &&
            response.game.state) {

            updatePositions(response.game.state);

            const game = response.game;

            const currentPId =
                game.clients[game.turnIndex].player;

            const diceBtn =
                document.getElementById("diceBtn");


            if (currentPId === myPlayerId) {

                document.getElementById("turn-txt")
                    .innerHTML =
                        `<span style="color:#2ecc71;">
                            ★ YOUR TURN ★
                         </span>`;

                diceBtn.disabled = false;

                diceBtn.classList.add("active-turn");

            } else {

                document.getElementById("turn-txt")
                    .innerText =
                        `Waiting for ${currentPId.toUpperCase()}...`;

                diceBtn.disabled = true;

                diceBtn.classList.remove("active-turn");
            }
        }


        // Another player quit — show alert and redirect
        if (response.method === "player_quit") {

            Swal.fire({

                title: '😔 Player Left',

                text: response.message ||
                     'Another player has left the game.',

                icon: 'warning',

                confirmButtonColor: '#27ae60',

                confirmButtonText: 'Go to Results',

                allowOutsideClick: false

            }).then(() => {

                goToResults();
            });
        }


        if (response.method === "provide_questions" &&
            questionResolver) {

            questionResolver(response.questions);
        }


        if (response.method === "verification_result" &&
            answerResolver) {

            answerResolver(
                response.isCorrect,
                response.correctAnswer
            );
        }
    };


    ws.onclose = () => {

        console.log(
            '[WS] Connection closed'
        );
    };


    ws.onerror = (err) => {

        console.error(
            '[WS ERROR]',
            err
        );
    };


    // =========================================================
    // PLAYER POSITIONS
    // =========================================================

    function updatePositions(state) {

        // Get the coordinate map for the currently selected board.
        const coords = tileCoords[selectedMap];


        // -----------------------------------------------------
        // Initial player position
        // -----------------------------------------------------

        if (!state) {

            const active =
                Array.from(
                    document.querySelectorAll('.player')
                ).filter(
                    el => el.style.display === "block"
                );


            active.forEach((p, i) => {

                movePlayer(
                    p.id,
                    coords[0].left,
                    coords[0].top,
                    false,
                    i
                );

            });

            return;
        }


        // -----------------------------------------------------
        // Find players occupying the same position
        // -----------------------------------------------------

        const occupancy = {};


        for (const pId in state) {

            if (!state[pId].positionleft) {
                continue;
            }


            const key =
                state[pId].positionleft +
                "|" +
                state[pId].positiontop;


            if (!occupancy[key]) {
                occupancy[key] = [];
            }


            occupancy[key].push(pId);
        }


        // -----------------------------------------------------
        // Move players
        // -----------------------------------------------------

        for (const key in occupancy) {

            const players = occupancy[key];

            const [L, T] = key.split("|");


            players.forEach((pId, idx) => {

                movePlayer(
                    pId,
                    L,
                    T,
                    false,
                    idx
                );

            });
        }
    }


    // =========================================================
    // MOVE PLAYER
    // =========================================================

    function movePlayer(
        pId,
        L,
        T,
        crowded,
        index
    ) {

        const el =
            document.getElementById(pId);

        if (!el) {
            return;
        }

        /*
         * No crowding offset is applied.
         *
         * Every player occupying the same square
         * uses exactly the same coordinate.
         */

        el.style.left = parseFloat(L) + "%";

        el.style.top = parseFloat(T) + "%";
    }


    // =========================================================
    // DICE ROLL
    // =========================================================

    document.getElementById("diceBtn").onclick = () => {

        document.getElementById("diceBtn")
            .disabled = true;


        let roll =
            Math.floor(Math.random() * 6) + 1;


        document.getElementById("dice-val")
            .innerText = roll;


        currentSum += roll;


        if (currentSum > 33) {
            currentSum = 33;
        }


        // IMPORTANT:
        // Select the coordinates for the currently
        // selected board.

        const coords =
            tileCoords[selectedMap];

        const c =
            coords[currentSum];


        console.log(
            '[WS OUT] play',
            {
                map: selectedMap,
                currentSum: currentSum,
                roll: roll,
                position: c
            }
        );


        ws.send(JSON.stringify({

            method: "play",

            clientId: clientId,

            gameId: gameId,

            player: myPlayerId,

            positionleft: c.left,

            positiontop: c.top

        }));


        handleTripleQuestion(currentSum);
    };


    // =========================================================
    // QUESTION HANDLING
    // =========================================================

    async function handleTripleQuestion(tileIndex) {

        if (tileIndex === 0) {

            endTurn();

            return;
        }


        console.log(
            '[WS OUT] request_questions tile=' +
            tileIndex
        );


        ws.send(JSON.stringify({

            method: "request_questions",

            clientId: clientId,

            tile: tileIndex,

            mapType: selectedMap

        }));


        const questions =
            await new Promise(
                res => {
                    questionResolver = res;
                }
            );


        for (
            let i = 0;
            i < questions.length;
            i++
        ) {

            const q = questions[i];

            let answeredCorrectly = false;

            let correctText = "";


            while (!answeredCorrectly) {

                const {
                    value: userInput
                } = await Swal.fire({

                    title:
                        `Question ${i + 1} of ${questions.length}`,

                    text: q.text,

                    input: 'text',

                    inputPlaceholder:
                        'Type your answer...',

                    confirmButtonColor:
                        '#27ae60',

                    allowOutsideClick:
                        false
                });


                console.log(
                    '[WS OUT] verify_answer',
                    {
                        questionId: q.id,
                        answer: userInput
                    }
                );


                ws.send(JSON.stringify({

                    method: "verify_answer",

                    clientId: clientId,

                    questionId: q.id,

                    answer: userInput || ""

                }));


                const result =
                    await new Promise(res => {

                        answerResolver =
                            (isCor, corTxt) =>
                                res({
                                    isCor,
                                    corTxt
                                });
                    });


                answeredCorrectly =
                    result.isCor;

                correctText =
                    result.corTxt;


                sessionResults.push({

                    question: q.text,

                    user_answer:
                        userInput || "No answer",

                    correct_answer:
                        correctText,

                    is_correct:
                        answeredCorrectly
                });


                if (!answeredCorrectly) {

                    await Swal.fire({

                        title: '❌ Incorrect',

                        text: `Try again!`,

                        icon: 'error',

                        confirmButtonColor:
                            '#27ae60'
                    });
                }
            }
        }


        if (currentSum >= 33) {

            goToResults();

        } else {

            endTurn();
        }
    }


    // =========================================================
    // END TURN
    // =========================================================

    function endTurn() {

        console.log(
            '[WS OUT] finish_turn',
            { gameId }
        );


        ws.send(JSON.stringify({

            method: "finish_turn",

            gameId: gameId

        }));
    }


    // =========================================================
    // QUIT GAME
    // =========================================================

    function quitGame() {

        Swal.fire({

            title: '🚪 Quit Game?',

            text:
                "This will end the game for all players.",

            icon: 'warning',

            showCancelButton: true,

            confirmButtonColor:
                '#e74c3c',

            cancelButtonColor:
                '#27ae60',

            confirmButtonText:
                'Yes, Quit',

            cancelButtonText:
                'Stay'

        }).then((result) => {

            if (result.isConfirmed) {

                console.log(
                    '[WS OUT] quit',
                    { gameId }
                );


                ws.send(JSON.stringify({

                    method: "quit",

                    clientId: clientId,

                    gameId: gameId

                }));


                goToResults();
            }
        });
    }


    // =========================================================
    // RESULTS
    // =========================================================

    function goToResults() {

        sessionStorage.setItem(
            'lastGameResults',
            JSON.stringify(sessionResults)
        );

        window.location.href =
            'results.php';
    }


    // =========================================================
    // SLIDESHOW SWITCHER
    // =========================================================

    let currentSlideshow = null;


    function startGameSlideshow(gameType) {

        document.querySelector('.jp-bg')?.remove();

        document.querySelector('.jp-bg-overlay')?.remove();

        document.body.dataset.jpBg =
            gameType;

        currentSlideshow =
            new JPSlideshow(gameType);

        currentSlideshow.init();
    }

</script>


<script src="bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>

<script src="js/slideshow.js"></script>

</body>
</html>
