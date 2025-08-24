<?php
    $target_md5 = "1766f4625c683b0e92b2a3c3ac0ee1e9";
    $flag = 'skills54{md5_1s_s0_fun_bh169n4169w}';

    $message = "";

    if (isset($_POST['password'])) {
        $password = $_POST['password'];
        $hash = md5($password);

        if ($hash == $target_md5 || $hash == "0e123456789012345678901234567890") {
            $message = $flag;
        } else {
            $message = "incorrect password, your hash: " . $hash;
        }
    } else {
        $message = "Please enter password";
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Checker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        .login-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px 30px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        
        h2 {
            margin-bottom: 20px;
            color: #343a40;
        }
        
        label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            color: #495057;
        }
        
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 16px;
        }
        
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        
        button:hover {
            background-color: #0056b3;
        }
        
        .message {
            margin-top: 20px;
            font-size: 16px;
            color: #495057;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Password checker</h2>
        <h4>target: 02255c0319bc1d27ba7bd8eec86304a0</h4>
        <h4>password==md5(password)</h4>
        <form method="post">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Check!</button>
        </form>

        <div class="message">
            <?php echo $message; ?>
        </div>
    </div>
</body>

</html>

