const admins = JSON.parse(localStorage.getItem('admins')) || [];
const ADMIN_SECRET_CODE = 'ADMIN123'; 

const loginForm = document.getElementById('loginForm');
const signupForm = document.getElementById('signupForm');
const formContainer = document.getElementById('formContainer');

document.getElementById('switchToSignup').addEventListener('click', () => {
    loginForm.classList.add('hidden');
    signupForm.classList.remove('hidden');
});

document.getElementById('switchToLogin').addEventListener('click', () => {
    signupForm.classList.add('hidden');
    loginForm.classList.remove('hidden');
});

document.getElementById('signupButton').addEventListener('click', () => {
    const email = document.getElementById('signupEmail').value;
    const password = document.getElementById('signupPassword').value;
    const adminCode = document.getElementById('adminCode').value;

    if (!email || !password || !adminCode) {
        alert('Please fill in all fields');
        return;
    }

    if (adminCode !== ADMIN_SECRET_CODE) {
        alert('Invalid administrator code');
        return;
    }

    if (admins.find(admin => admin.email === email)) {
        alert('Email is already registered');
        return;
    }

    admins.push({ email, password, role: 'admin' });
    localStorage.setItem('admins', JSON.stringify(admins));
    alert('Administrator account created successfully');
    signupForm.classList.add('hidden');
    loginForm.classList.remove('hidden');
});

document.getElementById('loginButton').addEventListener('click', () => {
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;

    if (!email || !password) {
        alert('Please fill in all fields');
        return;
    }

    const admin = admins.find(admin => admin.email === email && admin.password === password);

   
if (!admin) {
const customAlert = document.getElementById('customAlert');
customAlert.classList.remove('hidden');
customAlert.classList.add('animate-fade-in');

document.getElementById('closeAlert').addEventListener('click', () => {
    customAlert.classList.add('hidden');
});
return;
}
const successAlert = document.getElementById('successAlert');
successAlert.classList.remove('hidden');
successAlert.classList.add('animate-fade-in');

localStorage.setItem('currentAdmin', JSON.stringify(admin));

setTimeout(() => {
window.location.href = '../index.html';
}, 2000);
});



document.addEventListener('DOMContentLoaded', function() {
const video = document.getElementById('myVideo');
video.playbackRate = 0.5; 
});
