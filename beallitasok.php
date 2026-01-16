<?php
session_start();
require 'php/config.php';

$logged_in = isset($_SESSION['user_id']);
$user = null;
if ($logged_in) {
    $stmt = $pdo->prepare("SELECT username, profile_pic FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="hu">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FLIP GAME - Beállítások</title>

    <link
      href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=Inter:wght@400;600;700&display=swap"
      rel="stylesheet"
    />

    <style>
      * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
      }

      body {
        width: 100vw;
        height: 100vh;
        overflow: hidden;
        font-family: "Inter", sans-serif;
        color: #fff;
        background: #000;
      }

      .background {
        position: fixed;
        inset: 0;
        background: url("public/webpage123-5sy-1800w.png") center/cover no-repeat;
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
        position: absolute;
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

      .navbar-title { font-size: 32px; letter-spacing: 4px; color: #7afcff; }
      .navbar-links { display: flex; gap: 80px; }
      .navbar-links a {
        margin-top: 13px;
        color: #e0e0e0;
        text-decoration: none;
        font-size: 14px;
        font-weight: 550;
        transition: all 0.25s ease;
      }
      .navbar-links a:hover { color: #7afcff; text-shadow: 0 0 8px #7afcff; }


      .navbar-links a:hover {
        color: #7afcff;
        text-shadow: 0 0 8px #7afcff;
      }

      .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
      }

      .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid #7afcff;
      }

      .settings-box {
        font-family: "Press Start 2P", cursive;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(90, 90, 90, 0.8);
        border-radius: 16px;
        padding: 30px 40px;
        width: 650px;
        max-width: 90%;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
        z-index: 4;
      }

      .settings-box h2 {
        font-size: 14px;
        margin-bottom: 25px;
      }

      .setting {
        background: rgba(50, 50, 50, 0.8);
        border-radius: 12px;
        padding: 18px 20px;
        margin-bottom: 18px;
      }

      .setting label {
        font-size: 12px;
        font-weight: bold;
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .setting small {
        display: block;
        font-size: 10px;
        color: #bcbcbc;
        margin-top: 6px;
      }

      .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 26px;
      }

      .switch input {
        opacity: 0;
        width: 0;
        height: 0;
      }

      .slider {
        position: absolute;
        cursor: pointer;
        inset: 0;
        background-color: #888;
        border-radius: 34px;
        transition: 0.4s;
      }

      .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 4px;
        bottom: 4px;
        background-color: #fff;
        border-radius: 50%;
        transition: 0.4s;
      }

      input:checked + .slider {
        background: linear-gradient(135deg, #7afcff, #4ef3c3);
      }

      input:checked + .slider:before {
        transform: translateX(24px);
      }

      .time-setting {
        background: rgba(40, 40, 40, 0.9);
        border-radius: 12px;
        padding: 15px 20px;
        display: none;
        margin-top: 10px;
      }

      .time-setting label {
        display: block;
        font-size: 12px;
        font-weight: bold;
        margin-bottom: 8px;
      }

      .time-setting input {
        width: 80px;
        padding: 6px 8px;
        border-radius: 6px;
        border: none;
        background: #2d2d2d;
        color: #fff;
        text-align: center;
        font-size: 12px;
        font-family: "Press Start 2P", cursive;
      }


      @media (max-width: 1024px) {
        .navbar {
          padding: 0 40px;
        }

        .navbar-links {
          gap: 40px;
        }

        .character {
          right: 50%;
          height: 50%;
        }
      }

      @media (max-width: 650px) {
        body {
          overflow-y: auto;
          overflow-x: hidden;
        }

        .navbar {
          height: 70px;
          padding: 0 20px;
        }

        .navbar-title {
          font-size: 22px;
        }

        .navbar-links {
          gap: 20px;
        }

        .navbar-links a {
          font-size: 10px;
        }

        .character {
          display: none;
        }

        .settings-box {
          position: relative;
          top: 120px;
          left: 50%;
          transform: translateX(-50%);
          max-width: 92%;
          padding: 25px 20px;
          margin-bottom: 40px;
        }
      }
    </style>
  </head>

  <body>
    <div class="background"></div>
    <img src="public/nvtelen126-bg69-800w.png" alt="character" class="character" />
    <div class="overlay"></div>

    <div class="navbar">
      <div class="navbar-title">FLIP GAME</div>
      <div class="navbar-links">
        <a href="kezdes.php">Kezdés</a>
        <a href="./php/ujszintek.php">Szintek</a>
        <a href="beallitasok.php">Beállítások</a>
        <?php if ($logged_in): ?>
          <div class="user-info">
            <img src="<?php echo htmlspecialchars($user['profile_pic']); ?>" alt="Avatar" class="user-avatar">
            <a href="index.php"><?php echo htmlspecialchars($user['username']); ?></a>
          </div>
        <?php else: ?>
          <a href="index.php" style="color: #7afcff">Bejelentkezés</a>
        <?php endif; ?>
      </div>
    </div>

    <div class="settings-box">
      <h2>Beállítások</h2>

      <div class="setting">
        <label>
          Automatikus gravitáció váltás
          <div class="switch">
            <input type="checkbox" id="gravity-toggle" />
            <span class="slider"></span>
          </div>
        </label>
        <small>A szóköz lenyomásával változik a gravitáció</small>
      </div>

      <div class="time-setting" id="timeSetting">
        <label for="timeInput">Automatikus váltás ideje – mp</label>
        <input type="number" id="timeInput" min="1" max="10" value="3" />
      </div>
    </div>

    <script>
      const toggle = document.getElementById("gravity-toggle");
      const timeSetting = document.getElementById("timeSetting");

      toggle.addEventListener("change", () => {
        timeSetting.style.display = toggle.checked ? "block" : "none";
      });
    </script>
  </body>
</html>