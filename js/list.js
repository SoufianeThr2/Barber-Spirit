document.addEventListener("DOMContentLoaded", function() {
    // Example of form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        if (username === '' || password === '') {
            event.preventDefault();
            alert('Both fields are required!');
        }
    });

    // Smooth scrolling for navigation links
    const smoothScrollLinks = document.querySelectorAll('.smoothscroll');
    smoothScrollLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            document.getElementById(targetId).scrollIntoView({ behavior: 'smooth' });
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        // AJAX example: Delete user without page reload
        const deleteButtons = document.querySelectorAll('.delete-user');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                if (confirm('Are you sure you want to delete this user?')) {
                    const userId = this.getAttribute('data-id');
                    fetch(`includes/delete_user.php?id=${userId}`, { method: 'POST' }) // Change method to POST
                        .then(response => response.text())
                        .then(data => {
                            if (data === 'success') {
                                this.closest('tr').remove();
                            } else {
                                alert('Failed to delete user');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while deleting the user. Please try again later.');
                        });
                }
            });
        });
    });
    
        });

