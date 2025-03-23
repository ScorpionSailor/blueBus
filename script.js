let buttons = document.querySelectorAll("button");
let popup = document.getElementById("popup");
let title = document.getElementById("availableBusesTitle");

// Don't prevent the default form submission for Book Now buttons
buttons.forEach((btn) => {
    if (btn.classList.contains('booknow')) {
        btn.addEventListener("click", function() {
            // Just change the text, don't manipulate the popup here
            this.textContent = "Processing...";
            // Allow the form to submit naturally
        });
    }
});

// Check if popup should be displayed
document.addEventListener('DOMContentLoaded', () => {
    // Check if popup exists and if URL has showPopup parameter
    if (popup) {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('showPopup') === 'true') {
            popup.style.display = 'flex';
            if (title) title.style.display = "none";
        }
    }
    
    // Check login status
    checkLoginStatus();
});

// Function to check login status
function checkLoginStatus() {
    fetch('check_login.php')
        .then(response => response.json())
        .then(data => {
            const loginItem = document.getElementById('login-item');
            const profileDropdown = document.getElementById('profile-dropdown');
            
            if (loginItem && profileDropdown) {
                const userNameDisplay = document.getElementById('user-name-display');
                const profileName = document.getElementById('profile-name');
                const profileEmail = document.getElementById('profile-email');

                if (data.loggedIn) {
                    loginItem.style.display = 'none';
                    profileDropdown.style.display = 'flex';
                    if (userNameDisplay) userNameDisplay.textContent = data.name.split(' ')[0];
                    if (profileName) profileName.textContent = data.name;
                    if (profileEmail) profileEmail.textContent = data.email;
                } else {
                    loginItem.style.display = 'flex';
                    profileDropdown.style.display = 'none';
                }
            }
        })
        .catch(error => console.error('Error:', error));
}

function logout() {
    fetch('logout.php')
        .then(response => response.text())
        .then(() => {
            window.location.href = 'index.html';
        })
        .catch(error => console.error('Error:', error));
}