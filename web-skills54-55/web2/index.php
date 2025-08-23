<?php
require 'vendor/autoload.php';
use MongoDB\Client;

$mongo_username = "root";
$mongo_password = "root";
$flag = "skills54{n0_Sq1_1nj3c7i0n_1n6s1t6q1h02}";

$client = new Client("mongodb://$mongo_username:$mongo_password@mongo:27017");
$db = $client->nosqlinjection;
$users = $db->users;

function initialize_database($users, $flag) {
    $guest_user = ["username" => "guest", "password" => "guest"];
    $users->updateOne(["username" => "guest"], ['$set' => $guest_user], ["upsert" => true]);
    $admin_user = [
        "username" => "admin",
        "password" => base64_encode(random_bytes(24)),
        "secret"   => $flag
    ];
    $users->updateOne(["username" => "admin"], ['$set' => $admin_user], ["upsert" => true]);
}
initialize_database($users, $flag);

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

if ($path === "/login" && $_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $username = $data["username"] ?? null;
    $password = $data["password"] ?? null;
    $user = $users->findOne(["username" => $username, "password" => $password]);
    if ($user) {
        echo json_encode([
            "message" => "Login successful",
            "secret"  => $user["secret"] ?? "No secret found."
        ]);
    } else {
        http_response_code(401);
        echo json_encode(["error" => "Invalid credentials"]);
    }
    exit;
}

if ($path === "/") {
    if (isset($_GET["debug"])) {
        header("Content-Type: text/plain");
        readfile(__FILE__);
        exit;
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>NoSQL Injection Login</title>
        <style>
        :root{
          --bg1:#0f172a;
          --bg2:#0b1221;
          --accent1:#7c3aed;
          --accent2:#06b6d4;
          --muted:#94a3b8;
          --input-bg:rgba(255,255,255,0.03);
          --glass: rgba(255,255,255,0.04);
        }
        *{box-sizing:border-box;margin:0;padding:0}
        html,body{height:100%}
        body{
          font-family: Inter, system-ui, sans-serif;
          background: linear-gradient(135deg,var(--bg1) 0%, var(--bg2) 100%);
          display:flex;
          align-items:center;
          justify-content:center;
          padding:32px;
          color:#e6eef8;
        }
        .login-card{
          width:380px;
          max-width:95%;
          background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
          border-radius:14px;
          padding:28px;
          box-shadow: 0 10px 30px rgba(2,6,23,0.6), inset 0 1px 0 rgba(255,255,255,0.02);
          border: 1px solid rgba(255,255,255,0.03);
          backdrop-filter: blur(6px) saturate(120%);
        }
        .login-card h1{font-size:20px;margin-bottom:6px;}
        .login-card p.lead{color:var(--muted);font-size:13px;margin-bottom:18px;}
        .form-group{margin-bottom:14px}
        input[type="text"], input[type="password"]{
          width:100%;
          padding:12px 14px;
          border-radius:10px;
          border:1px solid rgba(255,255,255,0.04);
          background:var(--input-bg);
          color:inherit;
          outline:none;
          font-size:14px;
          transition:box-shadow .15s ease, border-color .15s ease, transform .06s ease;
          box-shadow: 0 2px 6px rgba(2,6,23,0.35);
        }
        input::placeholder{color:rgba(230,238,248,0.45)}
        input:focus{
          border-color: rgba(124,58,237,0.9);
          box-shadow: 0 6px 18px rgba(124,58,237,0.12);
          transform: translateY(-1px);
        }
        .btn{
          width:100%;
          padding:12px 14px;
          border-radius:10px;
          border:0;
          cursor:pointer;
          font-weight:600;
          font-size:15px;
          color:white;
          background-image: linear-gradient(90deg,var(--accent1), var(--accent2));
          box-shadow: 0 8px 20px rgba(11,17,40,0.45);
          transition:transform .12s ease, box-shadow .12s ease;
        }
        .btn:active{transform:translateY(1px) scale(.999)}
        .btn:hover{box-shadow: 0 12px 30px rgba(11,17,40,0.55)}
        #result{
          margin-top:14px;
          padding:12px;
          border-radius:8px;
          background:var(--glass);
          font-family: monospace;
          font-size:13px;
          color:#dbeafe;
          max-height:160px;
          overflow:auto;
          white-space:pre-wrap;
        }
        @media (max-width:420px){
          .login-card{padding:20px;border-radius:12px}
          input[type="text"], input[type="password"]{padding:10px}
          .btn{padding:10px;font-size:14px}
        }
        </style>
    </head>
    <body>
        <div class="login-card">
            <h1>Login</h1>
            <p class="lead">Enter your credentials</p>
            <form id="loginForm">
                <div class="form-group">
                    <input type="text" id="username" placeholder="Username">
                </div>
                <div class="form-group">
                    <input type="password" id="password" placeholder="Password">
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
            <pre id="result"></pre>
        </div>
        <script>
            document.getElementById("loginForm").addEventListener("submit", async (e) => {
                e.preventDefault();
                let res = await fetch("/login", {
                    method: "POST",
                    headers: {"Content-Type": "application/json"},
                    body: JSON.stringify({
                        username: document.getElementById("username").value,
                        password: document.getElementById("password").value
                    })
                });
                document.getElementById("result").textContent = await res.text();
            });
        </script>
    </body>
    </html>
    <?php
    exit;
}

http_response_code(404);
echo "Not Found";

