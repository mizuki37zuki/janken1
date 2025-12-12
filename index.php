<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ゲーム一覧</title>
    <style>
        body { 
            font-family: 'Arial', sans-serif; 
            text-align: center; 
            margin-top: 50px; 
            background-color: #e8f5e9; 
            color: #333;
        }
        .container { 
            background-color: #ffffff; 
            padding: 40px; 
            border-radius: 12px; 
            box-shadow: 0 8px 16px rgba(0,0,0,0.2); 
            max-width: 500px; 
            margin: auto; 
        }
        h1 { 
            color: #2e7d32; 
            border-bottom: 3px solid #66bb6a; 
            padding-bottom: 15px; 
            margin-bottom: 30px;
        }
        .game-list { 
            list-style: none; 
            padding: 0;
        }
        .game-list li {
            margin-bottom: 20px;
        }
        .game-list a {
            display: block;
            background-color: #66bb6a;
            color: white;
            padding: 15px;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1.2em;
            transition: background-color 0.3s, transform 0.2s;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .game-list a:hover {
            background-color: #43a047;
            transform: translateY(-2px);
        }
        .emoji {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎮 ゲーム一覧 🎮</h1>
        
        <ul class="game-list">
            <li>
                <a href="./janken.php">
                    <span class="emoji">✊✌️✋</span> じゃんけんゲーム
                </a>
            </li>
        </ul>
    </div>
</body>
</html>