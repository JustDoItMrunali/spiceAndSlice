<?php
echo "<html>
<head>
    <title>Payment Failed</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        .error { color: red; font-size: 20px; }
        a { display: inline-block; margin-top: 20px; padding: 10px 20px; background: gray; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <h2 class='error'>Payment Failed!</h2>
    <p>Please try again.</p>
    <a href='payscript.php'>Retry Payment</a>
</body>
</html>";
?>
