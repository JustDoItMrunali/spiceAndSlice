// Save user data in local storage (e.g., after successful login)
function setUserSession(user) {
    localStorage.setItem("user", JSON.stringify(user));
}

// Get user data from local storage
function getUserSession() {
    const user = JSON.parse(localStorage.getItem("user"));
    return user || null;
}

// Clear user session (e.g., after logout)
function clearUserSession() {
    localStorage.removeItem("user");
}
