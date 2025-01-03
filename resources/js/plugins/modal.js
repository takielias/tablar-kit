const TablarModal = {
    init() {
        this.bindModalTriggers();
        this.bindFormSubmissions();
    },

    loadModal(url, containerId = 'modal-container') {
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then(html => {
                document.getElementById(containerId).innerHTML = html;
                // Initialize the new modal
                const modalElement = document.querySelector('.modal');
                const modal = new bootstrap.Modal(modalElement);
                modal.show();

                // Rebind form submissions for the new modal
                this.bindFormSubmissions();
            })
            .catch(error => {
                console.error('Error loading modal:', error);
                // Optionally show error message to user
                alert('Error loading modal content. Please try again.');
            });
    },

    bindModalTriggers() {
        // For buttons with data-modal-url
        document.querySelectorAll('[data-modal-url]').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const url = button.dataset.modalUrl;
                this.loadModal(url);
            });
        });

        // For links with data-modal-trigger
        document.querySelectorAll('[data-modal-trigger]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const url = link.href;
                this.loadModal(url);
            });
        });
    },

    bindFormSubmissions() {
        document.querySelectorAll('.modal form').forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                try {
                    const response = await fetch(form.action, {
                        method: form.method,
                        body: new FormData(form),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(form.closest('.modal'));
                        modal.hide();

                        // Clear modal container
                        document.getElementById('modal-container').innerHTML = '';

                        // Optional: Show success message
                        if (data.message) {
                            alert(data.message);
                        }

                        // Optional: Refresh page or update specific content
                        if (data.reload) {
                            window.location.reload();
                        }
                    }
                } catch (error) {
                    console.error('Error submitting form:', error);
                }
            });
        });
    }
};

// Initialize TablarModal when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    TablarModal.init();
});

// Global function to open any modal
window.openModal = (url) => {
    TablarModal.loadModal(url);
}
