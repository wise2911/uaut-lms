import './bootstrap';


document.getElementById('send-otp-form').addEventListener('submit', function (e) {
    e.preventDefault();
    
    fetch(this.action, {
        method: 'POST',
        body: new FormData(this),
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('send-otp-form').classList.add('hidden');
            document.getElementById('reset-password-form').classList.remove('hidden');
            document.getElementById('reset-email').value = document.getElementById('forgot-email').value;
        } else {
            alert(data.error || 'Error: Unable to send OTP. Please try again.');
            console.error('Error response:', data);
        }
    })
    .catch(error => {
        alert('Network error: Please check your internet connection.');
        console.error('Fetch error:', error);
    });
});

