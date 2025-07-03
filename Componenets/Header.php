<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="/SwiftCart/Image/favicon.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .toast-msg {
            display: none;
            position: fixed;
            left: 50%;
            bottom: 40px;
            transform: translateX(-50%);
            padding: 15px 20px;
            border-radius: 8px;
            color: white;
            z-index: 9999;
            font-weight: bold;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .toast-msg.success {
            background-color: #28a745;
        }

        /* Green */
        .toast-msg.danger {
            background-color: #dc3545;
        }

        /* Red */
        .toast-msg.warning {
            background-color: #ffc107;
        }

        /* Yellow */
        .toast-msg.info {
            background-color: #17a2b8;
        }

        /* Blue */
    </style>
</head>

<body>
    <?php if (isset($_SESSION['message'])): ?>
        <div id="toast" class="toast-msg <?php echo $_SESSION['msg_type']; ?>">
            <?php
            echo $_SESSION['message'];
            unset($_SESSION['message']);
            unset($_SESSION['msg_type']);
            ?>
        </div>

        <script>
            const toast = document.getElementById('toast');
            toast.style.display = 'block';
            setTimeout(() => toast.style.display = 'none', 3000); // Auto hide
        </script>
    <?php endif; ?>
</body>

</html>