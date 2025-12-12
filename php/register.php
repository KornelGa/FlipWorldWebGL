<?php
session_start();
require '../php/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $profile_pic = $_POST['profile_pic'];

    if (empty($username) || empty($email) || empty($password)) {
        $error = "Minden mező kötelező!.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Hibás email formátum!.";
    } elseif (strlen($password) < 6) {
        $error = "A jelszónak legalább 6 karakter hosszúnak kell lennie!.";
    } else {

        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->rowCount() > 0) {
            $error = "A felhasználó vagy email már létezik!.";
        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, profile_pic) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$username, $email, $hashed_password, $profile_pic])) {
                header("Location: login.php?success=1");
                exit;
            } else {
                $error = "Regisztráció sikertelen.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció - FLIP GAME</title>
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
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background: rgba(50, 50, 50, 0.8);
            color: #fff;
            font-size: 14px;
        }
        .form-group input:focus, .form-group select:focus {
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
        .link {
            text-align: center;
            margin-top: 15px;
        }
        .link a {
            color: #7afcff;
            text-decoration: none;
        }
        .profile-pics {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        .profile-pic-option {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid transparent;
            transition: border 0.3s;
        }
        .profile-pic-option.selected {
            border-color: #7afcff;
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
        <a href="index.php">Bejelentkezés</a>
      </div>
    </div>
    <div class="form-container">
        <h2>Regisztráció</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <div class="form-group">
                <label for="username">Felhasználónév</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Jelszó</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Válassz egy avatárt</label>
                <div class="profile-pics">
                    <?php
                    $avatars = ['default', 'alex', 'bella', 'charlie', 'diana', 'ethan'];
                    foreach ($avatars as $avatar) {
                        $url = "https://api.dicebear.com/7.x/avataaars/svg?seed=$avatar";
                        echo "<img src='$url' alt='$avatar' class='profile-pic-option' data-url='$url'>";
                    }
                    ?>
                </div>
                <input type="hidden" id="profile_pic" name="profile_pic" value="https://api.dicebear.com/7.x/avataaars/svg?seed=default">
            </div>
            <button type="submit" class="btn">Regisztráció</button>
        </form>
        <div class="link">
            <a href="../php/login.php">Van már fiókod? Bejelentkezés</a>
        </div>
    </div>
    <script>
        const pics = document.querySelectorAll('.profile-pic-option');
        const hiddenInput = document.getElementById('profile_pic');
        pics.forEach(pic => {
            pic.addEventListener('click', () => {
                pics.forEach(p => p.classList.remove('selected'));
                pic.classList.add('selected');
                hiddenInput.value = pic.dataset.url;
            });
        });
    </script>
</body>
</html>
