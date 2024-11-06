// Function to validate the login form
document.getElementById("loginForm").onsubmit = function(event) {
    event.preventDefault(); // Prevents form from submitting automatically
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;

    // Check if both fields are filled out
    if (username === "" || password === "") {
        alert("Please fill out both Username and Password.");
    } else {
        alert("Login successful!");
        // Add form submission logic here
    }
};
