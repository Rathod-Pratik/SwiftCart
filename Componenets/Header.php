<link rel="icon" href="/SwiftCart/Image/favicon.png" type="image/x-icon">
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
    body {
        font-family: 'Inter', sans-serif;
    }

    .bgcolor {
        background-color: #4fd1c5;
    }

    .textcolor {
        color: #4fd1c5;
    }

    .inputcolor {
        background-color: #f8fafd;
    }

    #toastContainer {
        position: fixed;
        bottom: 40px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        flex-direction: column;
        gap: 10px;
        z-index: 9999;
    }

    .toast-msg {
        opacity: 0;
        transform: translateX(-50%) translateY(30px);
        transition: opacity 0.4s ease, transform 0.4s ease;
        pointer-events: none;
        position: relative;
        left: 50%;
        padding: 15px 20px;
        border-radius: 8px;
        color: white;
        font-weight: bold;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        min-width: 200px;
        text-align: center;
    }

    .toast-msg.show {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
        pointer-events: auto;
    }

    .toast-msg.hide {
        opacity: 0;
        transform: translateX(-50%) translateY(40px);
        pointer-events: none;
    }

    .toast-msg.success {
        background-color: #28a745;
    }

    .toast-msg.danger {
        background-color: #dc3545;
    }

    .toast-msg.warning {
        background-color: #ffc107;
        color: black;
    }

    .toast-msg.info {
        background-color: #17a2b8;
    }
</style>


<!-- Toast container -->
<div id="toastContainer"></div>

<script>
    function showToast(message, type = 'success') {
        const toastContainer = document.getElementById('toastContainer');

        const toast = document.createElement('div');
        toast.className = `toast-msg ${type}`;
        toast.textContent = message;

        toastContainer.appendChild(toast);

        void toast.offsetHeight; 
        toast.classList.add('show');

        // Hide after 3 seconds
        setTimeout(() => {
            toast.classList.remove('show');
            toast.classList.add('hide');
            setTimeout(() => toast.remove(), 400);
        }, 3000);
    }
</script>