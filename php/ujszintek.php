<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require "config.php";

// Lekérjük a mentett szintet
$stmt = $pdo->prepare("SELECT max_level FROM user_levels WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$maxLevel = $row ? intval($row["max_level"]) : 1;
?>
<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Szintek</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=Inter:wght@400;600;700&display=swap" />

    <style>
        /* -------- TELJES EREDETI STÍLUS ---------------- */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            width: 100vw;
            height: 100vh;
            background: black;
            color: white;
            font-family: "Inter", sans-serif;
            overflow-x: hidden;
        }

        .background {
            background-image: url("public/nvtelen126-bg-800w.png");
            background-size: cover;
            background-position: center;
            filter: blur(8px);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
        }

        .character {
            position: fixed;
            bottom: 0;
            right: 0;
            width: 45%;
            z-index: -1;
            pointer-events: none;
            opacity: 0.1;
        }

        .overlay {
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.8), transparent);
            position: fixed;
            top: 0;
            width: 100%;
            height: 200px;
            z-index: -1;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 22px 45px;
            background: rgba(0, 0, 0, 0.66);
            border-bottom: 2px solid rgba(255, 255, 255, 0.15);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 50;
        }

        .navbar-title {
            font-family: "Press Start 2P";
            font-size: 26px;
            color: #fff;
            letter-spacing: 1px;
            text-shadow: 3px 3px 0 #00ffe5;
        }

        .navbar-links a {
            margin-left: 25px;
            font-size: 19px;
            text-decoration: none;
            color: #fff;
            transition: 0.2s;
        }

        .navbar-links a:hover {
            color: #00ffe5;
        }

        /* ------- SZINTEK LISTÁJA -------- */

        .levels-container {
            width: 100%;
            max-width: 1100px;
            margin: 130px auto;
            padding: 10px 20px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 20px;
        }

        .level-card {
            background: rgba(0, 0, 0, 0.65);
            border: 2px solid #2cf8ff60;
            border-radius: 14px;
            padding: 25px;
            backdrop-filter: blur(8px);
            transition: 0.3s;
            position: relative;
        }

        .level-card:hover {
            transform: translateY(-4px);
            border-color: #00eaff;
        }

        .level-card.locked {
            opacity: 0.45;
            filter: grayscale(1);
        }

        .lock-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
        }

        .lock-icon {
            font-size: 44px;
        }

        .star {
            font-size: 35px;
            color: #ffc800;
            text-shadow: 0 0 10px #ffea00;
        }

        h4 {
            font-size: 22px;
            margin-top: 10px;
            font-weight: 700;
        }

        h3 {
            font-size: 18px;
            margin: 7px 0;
            font-weight: 600;
        }

        p {
            margin-top: 5px;
            margin-bottom: 14px;
            font-size: 14px;
            color: #ddd;
        }

        .level-difficulty {
            padding: 6px 12px;
            border-radius: 8px;
            display: inline-block;
            font-size: 12px;
            margin-bottom: 16px;
            font-weight: 600;
        }

        .easy {
            background: #00ff6a25;
            color: #00ff6a;
        }

        .medium {
            background: #ffbe0025;
            color: #ffbe00;
        }

        .hard {
            background: #ff3c3c25;
            color: #ff3c3c;
        }

        .level-button {
            width: 100%;
            background: none;
            border: 2px solid #00ffe5;
            border-radius: 10px;
            padding: 10px 0;
            font-size: 16px;
            color: #00ffe5;
            cursor: pointer;
            transition: 0.25s;
        }

        .level-button:hover:not([disabled]) {
            background: #00ffe5;
            color: black;
        }

        .level-button:disabled {
            border-color: #888;
            color: #888;
            cursor: not-allowed;
        }

        .unlocking {
            animation: unlock 0.42s ease-out forwards;
        }

        @keyframes unlock {
            0% {
                transform: scale(0.8);
                opacity: 0.2;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
</head>

<body>

    <div class="background"></div>
    <img src="public/nvtelen126-bg69-800w.png" class="character" />
    <div class="overlay"></div>

    <div class="navbar">
        <div class="navbar-title">FLIP GAME</div>
        <div class="navbar-links">
            <a href="kezdes.php">Kezdés</a>
            <a href="szintek.php" style="color: #00ffe5">Szintek</a>
            <a href="beallitasok.php">Beállítások</a>
            <a href="logout.php">Kijelentkezés</a>
        </div>
    </div>

    <main class="levels-container">

        <?php for ($i = 1; $i <= 9; $i++):
            $locked = $maxLevel < $i;
        ?>
            <section class="level-card <?= $locked ? 'locked' : '' ?>" data-level="<?= $i ?>">

                <?php if ($locked): ?>
                    <div class="lock-overlay"><div class="lock-icon">🔒</div></div>
                <?php endif; ?>

                <span class="star">★</span>
                <h4>Level <?= $i ?></h4>
                <h3>Valami <?= $i ?></h3>
                <p>Valami szöveg sor <?= $i ?>.</p>

                <div class="level-difficulty <?= $i <= 3 ? 'easy' : ($i <= 6 ? 'medium' : 'hard') ?>">
                    <?= $i <= 3 ? 'Easy' : ($i <= 6 ? 'Medium' : 'Hard') ?>
                </div>

                <button class="level-button" data-level="<?= $i ?>" <?= $locked ? "disabled" : "" ?>>
                    Start
                </button>
            </section>
        <?php endfor; ?>

    </main>

    <script>
        document.querySelectorAll(".level-button").forEach(btn => {
            btn.addEventListener("click", () => {
                if (btn.disabled) return;

                const lvl = parseInt(btn.dataset.level);

                // Szint mentése backendre
                fetch("update_level.php", {
                    method: "POST",
                    body: new URLSearchParams({ level: lvl })
                });

                // Következő szint feloldása
                const next = document.querySelector(`.level-card[data-level="${lvl + 1}"]`);
                if (!next) return;

                if (next.classList.contains("locked")) {
                    next.classList.add("unlocking");

                    const nb = next.querySelector(".level-button");
                    if (nb) nb.disabled = false;

                    setTimeout(() => {
                        next.classList.remove("locked", "unlocking");
                        const overlay = next.querySelector(".lock-overlay");
                        if (overlay) overlay.remove();
                    }, 420);
                }
            });
        });
    </script>

</body>

</html>