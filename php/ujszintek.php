<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

require "config.php";

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

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            html,body { height: 100%; }

            body {
                width: 100vw;
                height: 100vh;
                background: black;
                color: white;
                font-family: "Inter", sans-serif;            
                overflow-x: hidden;
            }

            .background {
                position: fixed;
                inset: 0;
                background: url("../public/webpage123-5sy-1800w.png") center/cover no-repeat;
                filter: blur(15px) brightness(0.6);
                transform: scale(1.05);
                z-index: 0;
            }

            .character {
                position: absolute;
                bottom: 0;
                right: 58%;
                height: 60%;
                opacity: 0.6;
                filter: blur(6px) saturate(1.1);
                z-index: 1;
                pointer-events: none;
            }

            .overlay {
                position:absolute ;
                inset: 0;
                background: radial-gradient(
                    circle at center,
                    rgba(0, 0, 0, 0.25),
                    rgba(0, 0, 0, 0.85)
                );
                z-index: 2;
            }

            .navbar {
                font-family: "Press Start 2P", cursive;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 90px;
                background: rgba(60, 60, 60, 0.8);
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0 80px;
                backdrop-filter: blur(10px);
                border-bottom: 2px solid rgba(255, 255, 255, 0.1);
                z-index: 5;
            }

            .navbar-title {
                font-family: "Press Start 2P";
                font-size: 32px;
                color: #7afcff;
                letter-spacing: 4px;
            }

            .navbar-links { display: flex; gap: 80px; }

            .navbar-links a {
                color: #e0e0e0;
                text-decoration: none;
                font-size: 14px;
                font-weight: 550;
                transition: all 0.25s ease;
            }

            .navbar-links a:hover { color: #7afcff; text-shadow: 0 0 8px #7afcff; }


            .levels-container {
                font-family: "Press Start 2P", cursive;
                position: relative;
                z-index: 3;
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 35px;
                padding: 120px 60px 60px;
                align-items: start;
            }

            .level-card {
                background: rgba(90, 90, 90, 0.75);
                border-radius: 10px;
                padding: 22px;
                position: relative;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
                transition: transform 0.25s ease, box-shadow 0.25s ease, filter 0.45s ease;
                overflow: hidden;
            }

            .level-card:hover { transform: translateY(-5px); box-shadow: 0 6px 18px rgba(0,0,0,0.7); }

            .level-card.locked {
                filter: blur(6px) brightness(0.6);
            }
            
            .lock-overlay {
                position: absolute;
                inset: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 12;
                pointer-events: none;
            }

            .lock-icon {
                background: rgba(0,0,0,0.45);
                padding: 18px 22px;
                border-radius: 999px;
                font-size: 44px;
                color: rgba(255,255,255,0.95);
                box-shadow: 0 6px 20px rgba(0,0,0,0.6), 0 0 30px rgba(0,0,0,0.2) inset;
            }

            .star { position: absolute; top: 10px; right: 12px; color: #ffcc00; font-size: 20px; }


            h4 {
                font-size: 12px;
                color: #ccc;
                margin-bottom: 14px;
            }

            h3 {
                font-size: 20px;
                margin-bottom: 18px;
                color: #fff;
                font-family: "Press Start 2P", cursive;
                line-height: 1.2;
            }

            p {font-size: 13px; color: #bcbcbc; margin-bottom: 12px; line-height: 1.4;}

            .level-difficulty { font-size: 11px; font-weight: bold; margin-bottom: 12px; }
   

            .easy {color: #65ff65;}

            .medium { color: #ffae42; }

            .hard { color: #ff6363; }

            .level-button {
                font-family: "Press Start 2P", cursive;
                display: inline-block;
                background: linear-gradient(135deg, #7afcff, #4ef3c3);
                border: none;
                color: #ffffff;
                font-size: 13px;
                font-weight: bold;
                padding: 8px 18px;
                border-radius: 8px;
                cursor: pointer;
                transition: all 0.2s ease;
                box-shadow: 0 3px 10px rgba(122, 252, 255, 0.3);
            }

            .level-button:hover { transform: scale(1.03); box-shadow: 0 5px 15px rgba(122,252,255,0.5); }
            .level-button:active { transform: scale(0.99); }

            .level-button[disabled] {
                opacity: 0.5;
                cursor: not-allowed;
                transform: none;
                box-shadow: none;
            }

            .unlocking {
                animation: unlockPulse 420ms ease forwards;
            }   

            @keyframes unlockPulse {
                0% { transform: scale(0.98); filter: blur(6px) brightness(0.6); }
                60% { transform: scale(1.02); filter: blur(2px) brightness(0.9); }
                100% { transform: scale(1); filter: none; }
            }
            @media (max-width: 768px) {
                .navbar { flex-direction: column; height: auto; padding: 20px; }
                .navbar-links { gap: 30px; margin-top: 10px; }
                .levels-container { padding: 140px 20px 40px; gap: 20px; grid-template-columns: 1fr; }
            }

            @media (max-width:1024px){
                .leves-container{
                grid-template-columns: repeat(2, minmax(0, 1fr));
                }
                .body
                {
                overflow: visible;
                }
            }
            @media(max-width: 600px){
                .levels-container{
                grid-template-columns: 1fr;
                padding: 140px 20px 40px;
                gap: 20px;
                }
            }
        </style>
    </head>

    <body>

        <div class="background"></div>
        <img
        src="../public/nvtelen126-bg69-800w.png"
        alt="character"
        class="character"
        />

        <div class="overlay"></div>

        <div class="navbar">
            <div class="navbar-title">FLIP GAME</div>
            <div class="navbar-links">
                <a href="../kezdes.html">Kezdés</a>
                <a href="ujszintek.php" style="color: #00ffe5">Szintek</a>
                <a href="../beallitasok.html">Beállítások</a>
                <a href="../index.php">Bejelentkezés</a>
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

                    fetch("update_level.php", {
                        method: "POST",
                        body: new URLSearchParams({ level: lvl + 1 })
                    });

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