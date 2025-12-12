<?php

/**
 * 人間対コンピュータのじゃんけんゲーム
 * ファイル名: janken.php
 */

// 定数定義
const STONE = 'グー'; // 0
const SCISSORS = 'チョキ'; // 1
const PAPER = 'パー'; // 2

// 結果メッセージ
$result_message = '';
$user_hand = '';
$computer_hand = '';

// フォームが送信された場合の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_hand'])) {
    // ユーザーの手を取得 (数値に変換)
    $user_choice = (int)$_POST['user_hand'];
    
    // コンピュータの手をランダムに決定 (0: グー, 1: チョキ, 2: パー)
    $computer_choice = mt_rand(0, 2);

    // ユーザーとコンピュータのじゃんけんの手を文字列に変換
    $hands = [STONE, SCISSORS, PAPER];
    $user_hand = $hands[$user_choice];
    $computer_hand = $hands[$computer_choice];

    // 勝敗判定ロジック
    // (ユーザーの手 - コンピュータの手 + 3) % 3 を利用
    // 0: あいこ
    // 1: ユーザーの負け
    // 2: ユーザーの勝ち
    $result_value = ($user_choice - $computer_choice + 3) % 3;

    switch ($result_value) {
        case 0:
            $result_message = '引き分け（あいこ）です！';
            break;
        case 1:
            // 修正箇所: ユーザーの負け
            $result_message = 'コンピュータの勝ちです。残念！';
            break;
        case 2:
            // 修正箇所: ユーザーの勝ち
            $result_message = 'あなたの勝ちです！おめでとうございます！🎉';
            break;
    }
} else {
    $result_message = '下に手を選んで勝負！';
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>じゃんけんゲーム</title>
    <style>
        body { font-family: 'Arial', sans-serif; text-align: center; margin-top: 50px; background-color: #f4f4f4; }
        .container { background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); display: inline-block; }
        h1 { color: #333; }
        .result { margin: 20px 0; padding: 10px; border-radius: 5px; font-size: 1.2em; font-weight: bold; background-color: #e9e9e9; }
        .hands-display p { font-size: 1.1em; margin: 5px 0; }
        .form-container { margin-top: 30px; }
        .hand-button { padding: 10px 20px; margin: 5px; font-size: 16px; cursor: pointer; border: none; border-radius: 5px; background-color: #007bff; color: white; transition: background-color 0.3s; }
        .hand-button:hover { background-color: #0056b3; }
        .hand-button:focus { outline: none; box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.5); }
    </style>
</head>
<body>
    <div class="container">
        <h1>✊ チョキ ✋ じゃんけんゲーム 💻</h1>
        
        <div class="result" id="result-message">
            <?= htmlspecialchars($result_message) ?>
        </div>

        <?php if ($user_hand && $computer_hand): ?>
        <div class="hands-display">
            <p><strong>あなたの手:</strong> <?= htmlspecialchars($user_hand) ?></p>
            <p><strong>コンピュータの手:</strong> <?= htmlspecialchars($computer_hand) ?></p>
        </div>
        <?php endif; ?>

        <div class="form-container">
            <form method="POST" action="janken.php">
                <p>あなたの手を選んでください:</p>
                <button type="submit" name="user_hand" value="0" class="hand-button">グー (✊)</button>
                <button type="submit" name="user_hand" value="1" class="hand-button">チョキ (✌️)</button>
                <button type="submit" name="user_hand" value="2" class="hand-button">パー (✋)</button>
            </form>
        </div>
    </div>
</body>
</html>
