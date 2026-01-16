document.addEventListener('DOMContentLoaded', function() {
    var buttons = document.querySelectorAll('button');
    var baseAudio = document.createElement('audio');
    baseAudio.preload = 'auto';
    baseAudio.innerHTML = '<source src="audio/beep.mp3" type="audio/mpeg"><source src="audio/beep.ogg" type="audio/ogg">';
    buttons.forEach(function(button) {
        button.addEventListener('mouseenter', function() {
            var clonedAudio = baseAudio.cloneNode(true);
            clonedAudio.style.display = 'none';
            button.appendChild(clonedAudio);
            clonedAudio.play();
            clonedAudio.addEventListener('ended', function() {
                if (button.contains(clonedAudio)) {
                    button.removeChild(clonedAudio);
                }
            });
        });
    });
});
