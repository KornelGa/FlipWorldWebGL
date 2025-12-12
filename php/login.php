<?php
session_start();
require '../php/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $identifier = trim($_POST['identifier']); // username or email
    $password = $_POST['password'];

    // validálás
    if (empty($identifier) || empty($password)) {
        $error = "Minden mező kötelező!.";
    } else {
        // megnézi létezik-e a user
        $stmt = $pdo->prepare("SELECT id, username, email, password, profile_pic FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$identifier, $identifier]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['profile_pic'] = $user['profile_pic'];
            header("Location: ../index.html");
            exit;
        } else {
            $error = "Hibás felhasználónév/email vagy jelszó.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés - FLIP GAME</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet"/>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            overflow: hidden;
            width: 100vw;
            height: 100vh;
            overflow-x: hidden;
            font-family: "Inter", sans-serif;
            color: #fff;
            background: #000;
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

        .form-container {
            position: relative;
            z-index: 3;
            max-width: 400px;
            margin: 120px auto 0;
            background: rgba(90, 90, 90, 0.75);
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #7afcff;
            font-family: "Press Start 2P", cursive;
            font-size: 18px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #fff;
            font-size: 12px;
            font-family: "Inter", sans-serif;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background: rgba(50, 50, 50, 0.8);
            color: #fff;
            font-size: 14px;
        }
        .form-group input:focus {
            outline: none;
            box-shadow: 0 0 5px #7afcff;
        }
        .btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #7afcff, #4ef3c3);
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: scale(1.05);
        }
        .error {
            color: #ff6363;
            text-align: center;
            margin-bottom: 15px;
        }
        .success {
            color: #65ff65;
            text-align: center;
            margin-bottom: 15px;
        }
        .link {
            text-align: center;
            margin-top: 15px;
        }
        .link a {
            color: #7afcff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="background"></div>
    <img src="../public/nvtelen126-bg69-800w.png" alt="character" class="character" />
    <div class="overlay"></div>
    <div class="navbar">
      <div class="navbar-title">FLIP GAME</div>
      <div class="navbar-links">
        <a href="kezdes.html">Kezdés</a>
        <a href="szintek.html">Szintek</a>
        <a href="beallitasok.html">Beállítások</a>
        <a href="index.php" style="color: #7afcff">Bejelentkezés</a>
      </div>
    </div>
    <div class="form-container">
        <h2>Bejelentkezés</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <?php if (isset($_GET['success'])) echo "<p class='success'>Regisztráció sikeres! Jelentkezz be!</p>"; ?>
        <form method="POST">
            <div class="form-group">
                <label for="identifier">Felhasználónév vagy Email</label>
                <input type="text" id="identifier" name="identifier" required>
            </div>
            <div class="form-group">
                <label for="password">Jelszó</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Bejelentkezés</button>
        </form>
        <div class="link">
            <a href="../php/register.php">Nincs fiókod? Regisztráció</a>
        </div>
    </div>
</body>
</html>
