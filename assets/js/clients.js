const url = 'https://randomuser.me/api/';

// Get references to the card elements
let avatar = document.querySelector('#avatar');
let fullname = document.querySelector('#fullname');
let username = document.querySelector('#username');
let email = document.querySelector('#email');
let city = document.querySelector('#city');
let btn = document.querySelector('#btn');

// Add event listener for the button
btn.addEventListener("click", function() {
  fetch(url)
    .then(handleErrors)
    .then(parseJSON)
    .then(updateProfile)
    .catch(printError);
});

// Handle HTTP errors
function handleErrors(res) {
  if (!res.ok) {
    throw new Error(`HTTP Error: ${res.status}`);
  }
  return res;
}

// Parse the JSON response
function parseJSON(res) {
  return res.json();
}

// Update the card with new profile details
function updateProfile(profile) {
  const user = profile.results[0];
  
  // Update card elements
  avatar.src = user.picture.medium;
  fullname.textContent = `${user.name.first} ${user.name.last}`;
  username.textContent = `Username: ${user.login.username}`;
  email.textContent = `Email: ${user.email}`;
  city.textContent = `City: ${user.location.city}`;
}

// Print errors to the console
function printError(error) {
  console.error('Error:', error);
}
