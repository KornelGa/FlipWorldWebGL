
const levels = document.querySelectorAll(".level");
let unlockedLevel = parseInt(localStorage.getItem("unlockedLevel")) || 1;

function updateLevels() {
    levels.forEach((level) => {
        const num = parseInt(level.dataset.level);
        if (num <= unlockedLevel) {
            level.classList.remove("locked");
            level.style.pointerEvents = "auto";
        } else {
            level.classList.add("locked");
        }
        });
    }

    updateLevels();

    levels.forEach((level) => {
        const btn = level.querySelector("button");
        btn.addEventListener("click", () => {
          const num = parseInt(level.dataset.level);

          alert(`Level ${num} elindítva!`);

          if (num === unlockedLevel) {
            unlockedLevel++;
            localStorage.setItem("unlockedLevel", unlockedLevel);
            updateLevels();
          }
        });
});
