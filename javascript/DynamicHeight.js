document.addEventListener('DOMContentLoaded', function() {
    const userContainer = document.getElementById('user-container');
    
    
    if (!userContainer) {
        console.error('userContainer not found');
        return;
    }

    const oldPassword = document.getElementById('oldPassword');
    const confirm_margin = document.getElementById('margin-change');
    if (oldPassword) {
        console.log("old password is here");
        userContainer.classList.add('height-270');
        userContainer.classList.remove('height-230');
        confirm_margin.style.marginBottom = "0";
    } else {
        console.log("old password is not here");
        userContainer.classList.add('height-230');
        userContainer.classList.remove('height-270');
    }
});