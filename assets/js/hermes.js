document.addEventListener('DOMContentLoaded', function() {
    const chevronDown = document.getElementById('chevron_down_div');
    const chevronUp = document.getElementById('chevron_up_div');

    chevronDown.addEventListener('click', function(event) {
        // event.preventDefault();
        chevronDown.style.display = 'none';
        chevronUp.style.display = 'block';
    });

    chevronUp.addEventListener('click', function(event) {
        // event.preventDefault();
        chevronUp.style.display = 'none';
        chevronDown.style.display = 'block';
    });
});
