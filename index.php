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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register - FLIP GAME</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet"/>
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
        position: absolute;
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

      .content {
        position: relative;
        z-index: 3;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        flex-direction: column;
      }

      .welcome-message {
        font-family: "Press Start 2P", cursive;
        font-size: 24px;
        color: #7afcff;
        text-align: center;
        margin-bottom: 20px;
      }

      .auth-buttons {
        font-family: "Press Start 2P", cursive;
        display: flex;
        gap: 20px;
      }

      .auth-btn {
        background: linear-gradient(135deg, #7afcff, #4ef3c3);
        border: none;
        color: #ffffff;
        font-size: 14px;
        font-weight: bold;
        padding: 12px 24px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 3px 10px rgba(122, 252, 255, 0.3);
        text-decoration: none;
        display: inline-block;
      }

      .auth-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(122, 252, 255, 0.5);
      }

      @media (max-width: 768px) {
        .navbar {
          flex-direction: column;
          height: auto;
          padding: 20px;
        }

        .navbar-links {
          gap: 30px;
          margin-top: 10px;
        }

        .auth-buttons {
          flex-direction: column;
          gap: 10px;
        }
      }
    </style>
</head>
<body>
    <div class="background"></div>
    <img
      src="public/nvtelen126-bg69-800w.png"
      alt="character"
      class="character"
    />

    <div class="overlay"></div>
    <div class="navbar">
      <div class="navbar-title">FLIP GAME</div>
      <div class="navbar-links">
        <a href="kezdes.html">Kezdés</a>
        <a href="szintek.html">Szintek</a>
        <a href="beallitasok.html">Beállítások</a>
        <?php if ($logged_in): ?>
          <div class="user-info">
            <img src="<?php echo htmlspecialchars($user['profile_pic']); ?>" alt="Avatar" class="user-avatar">
            <span><?php echo htmlspecialchars($user['username']); ?></span>
            <a href="php/logout.php" class="auth-btn" style="padding: 6px 12px; font-size: 12px;">Kijelentkezés</a>
          </div>
        <?php else: ?>
          <a href="index.php" style="color: #7afcff">Bejelentkezés</a>
        <?php endif; ?>
      </div>
    </div>
    <?php if (!$logged_in): ?>
    <div class="content">
      <h1 class="welcome-message">Welcome to FLIP GAME</h1>
      <div class="auth-buttons">
        <a href="php/login.php" class="auth-btn">Bejelentkezés</a>
        <a href="php/register.php" class="auth-btn">Regisztráció</a>
      </div>
    </div>
    <?php else: ?>
    <div class="content">
      <h1 class="welcome-message">Üdv, <?php echo htmlspecialchars($user['username']); ?>!</h1>
      <div class="auth-buttons">
        <a href="kezdes.html" class="auth-btn">Játék</a>
        <a href="szintek.html" class="auth-btn">Szintek</a>
      </div>
    </div>
    <?php endif; ?>
</body>
</html>
