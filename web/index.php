<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
        height: 70px;
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
        font-size: 32px;
        letter-spacing: 4px;
        color: #7afcff;
      }

      .navbar-links {
        display: flex;
        gap: 80px;
      }

      .navbar-links a {
        color: #e0e0e0;
        text-decoration: none;
        font-size: 14px;
        font-weight: 550;
        transition: all 0.25s ease;
      }

      .navbar-links a:hover {
        color: #7afcff;
        text-shadow: 0 0 8px #7afcff;
      }

      .levels-container {
        font-family: "Press Start 2P", cursive;
        position: relative;
        z-index: 3;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(420px, 1fr));
        gap: 50px;
        padding: 100px 100px 50px;
      }

      .level-card {
        background: rgba(90, 90, 90, 0.75);
        border-radius: 10px;
        padding: 25px 25px ;
        position: relative;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
      }

      .level-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.7);
      }

      .level-card h4 {
        font-size: 12px;
        color: #ccc;
        margin-bottom: 20px;
      }

      .level-card h3 {
        font-size: 22px;
        margin-bottom: 25px;
        color: #fff;
        font-family: "Press Start 2P", cursive;
        line-height: 1.4;
      }

      .level-card p {
        font-size: 15px;
        color: #bcbcbc;
        margin-bottom: 15px;
        line-height: 1.4;
      }

      .level-difficulty {
        font-size: 12px;
        font-weight: bold;
        margin-bottom: 12px;
      }

      .easy {
        color: #65ff65;
      }
      .medium {
        color: #ffae42;
      }
      .hard {
        color: #ff6363;
      }

      .level-button {
        display: inline-block;
        background: linear-gradient(135deg, #7afcff, #4ef3c3);
        border: none;
        color: #ffffff;
        font-size: 13px;
        font-weight: bold;
        padding: 8px 18px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 3px 10px rgba(122, 252, 255, 0.3);
        text-decoration: none;
      }

      .level-button:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(122, 252, 255, 0.5);
      }

      .star {
        position: absolute;
        top: 10px;
        right: 12px;
        color: #ffcc00;
        font-size: 20px;
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

        .levels-container {
          padding: 130px 30px 40px;
          gap: 20px;
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
        <a href="kezdes.html">Kezdés</a>
        <a href="szintek.html" style="color: #7afcff">Szintek</a>
        <a href="beallitasok.html">Beállítások</a>
      </div>
    </div>
</body>
</html>