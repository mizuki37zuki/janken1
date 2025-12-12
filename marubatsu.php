<?php
session_start();

/**
 * 人間対コンピュータの三目並べ（〇×ゲーム）
 * ファイル名: marubatsu.php
 */

// 定数
const PLAYER_X = 'X'; // 人間（先手）
const PLAYER_O = 'O'; // コンピュータ（後手）
const CELL_EMPTY = '';

// --- ゲーム状態の初期化と管理 ---
function initialize_game() {
    $_SESSION['board'] = array_fill(0, 9, EMPTY); // 盤面 (0から8の配列)
    $_SESSION['current_player'] = PLAYER_X; // 常に人間(X)からスタート
    $_SESSION['game_status'] = 'playing'; // playing, win_X, win_O, draw
    $_SESSION['message'] = 'あなたの番です (X)。マスをクリックして打ちましょう。';
}

// セッションに状態がなければ初期化
if (!isset($_SESSION['board'])) {
    initialize_game();
}

$board = $_SESSION['board'];
$current_player = $_SESSION['current_player'];
$game_status = $_SESSION['game_status'];
$message = $_SESSION['message'];

// --- 勝利判定関数 ---
function check_win($board, $player) {
    // 勝利パターン（インデックス）
    $win_patterns = [
        [0, 1, 2], [3, 4, 5], [6, 7, 8], // 横
        [0, 3, 6], [1, 4, 7], [2, 5, 8], // 縦
        [0, 4, 8], [2, 4, 6]             // 斜め
    ];

    foreach ($win_patterns as $pattern) {
        if ($board[$pattern[0]] === $player && 
            $board[$pattern[1]] === $player && 
            $board[$pattern[2]] === $player) {
            return true;
        }
    }
    return false;
}

// --- 引き分け判定関数 ---
function check_draw($board) {
    return !in_array(CELL_EMPTY, $board);
}

// --- コンピュータ（O）の行動ロジック ---
function computer_move($board) {
    $available_moves = [];
    foreach ($board as $index => $cell) {
        if ($cell === CELL_EMPTY) {
            $available_moves[] = $index;
        }
    }

    if (empty($available_moves)) {
        return false; // 打てるマスがない
    }

    // 1. 勝利できるマスがあれば、そこに打つ
    foreach ($available_moves as $move) {
        $temp_board = $board;
        $temp_board[$move] = PLAYER_O;
        if (check_win($temp_board, PLAYER_O)) {
            return $move;
        }
    }

    // 2. 人間(X)が勝利するのをブロックできるマスがあれば、そこに打つ
    foreach ($available_moves as $move) {
        $temp_board = $board;
        $temp_board[$move] = PLAYER_X; // 相手の動きをシミュレート
        if (check_win($temp_board, PLAYER_X)) {
            return $move;
        }
    }

    // 3. 中央 (4) が空いていればそこに打つ（強力な戦略）
    if (in_array(4, $available_moves)) {
        return 4;
    }

    // 4. ランダムなマスに打つ
    return $available_moves[array_rand($available_moves)];
}

// --- フォーム処理 ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // リセット処理
    if (isset($_POST['reset'])) {
        initialize_game();
        header("Location: marubatsu.php"); // リロードしてPOSTリクエストをクリア
        exit;
    }

    // 人間の手番処理
    if ($game_status === 'playing' && $current_player === PLAYER_X && isset($_POST['move'])) {
        $index = (int)$_POST['move'];

        // マスが空いているか確認
        if ($index >= 0 && $index < 9 && $board[$index] === CELL_EMPTY) {
            
            // 1. 人間の手を反映
            $board[$index] = PLAYER_X;
            $_SESSION['board'] = $board;

            // 2. 勝利判定
            if (check_win($board, PLAYER_X)) {
                $_SESSION['game_status'] = 'win_X';
                $_SESSION['message'] = 'あなたの勝利です！おめでとうございます！🎉';
            } elseif (check_draw($board)) {
                $_SESSION['game_status'] = 'draw';
                $_SESSION['message'] = '引き分けです。';
            } else {
                // 3. コンピュータの手番へ移行
                $_SESSION['current_player'] = PLAYER_O;
                $_SESSION['message'] = 'コンピュータの番です (O)...';
                
                // コンピュータのターンを即時実行
                $computer_move_index = computer_move($board);

                if ($computer_move_index !== false) {
                    $board[$computer_move_index] = PLAYER_O;
                    $_SESSION['board'] = $board;
                    
                    // コンピュータ側の判定
                    if (check_win($board, PLAYER_O)) {
                        $_SESSION['game_status'] = 'win_O';
                        $_SESSION['message'] = 'コンピュータの勝利です。残念！';
                    } elseif (check_draw($board)) {
                        $_SESSION['game_status'] = 'draw';
                        $_SESSION['message'] = '引き分けです。';
                    } else {
                        // 4. 人間の手番に戻す
                        $_SESSION['current_player'] = PLAYER_X;
                        $_SESSION['message'] = 'あなたの番です (X)。';
                    }
                }
            }
        } else {
            $_SESSION['message'] = 'そこには打てません。空いているマスを選んでください。';
        }
    }
    
    // 状態が更新されたので、リダイレクトして二重送信を防ぐ
    header("Location: marubatsu.php");
    exit;
}

// セッションから最新の状態を取得し直す
$board = $_SESSION['board'];
$game_status = $_SESSION['game_status'];
$message = $_SESSION['message'];
$is_game_over = $game_status !== 'playing';

// --- HTML出力開始 ---
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>三目並べ（〇×ゲーム）</title>
    <style>
        body { font-family: 'Arial', sans-serif; text-align: center; margin-top: 50px; background-color: #f4f4f4; }
        .container { background-color: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); display: inline-block; }
        h1 { color: #4CAF50; }
        .message { margin: 20px 0; padding: 15px; border-radius: 5px; font-size: 1.2em; font-weight: bold; background-color: #e8f5e9; border: 1px solid #c8e6c9; }
        .board {
            display: grid;
            grid-template-columns: repeat(3, 100px);
            grid-template-rows: repeat(3, 100px);
            gap: 5px;
            margin: 20px auto;
            border: 3px solid #333;
            background-color: #333;
        }
        .cell-button {
            width: 100%;
            height: 100%;
            border: none;
            background-color: #fff;
            font-size: 3em;
            cursor: pointer;
            transition: background-color 0.15s;
            line-height: 100px;
            text-align: center;
            padding: 0;
        }
        .cell-button:hover:not(:disabled) {
            background-color: #e0e0e0;
        }
        .cell-button:disabled {
            cursor: default;
        }
        .X { color: #FF9800; }
        .O { color: #2196F3; }
        .reset-form button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        .reset-form button:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>⭕️ 三目並べ ❌</h1>
        
        <div class="message">
            <?= htmlspecialchars($message) ?>
        </div>

        <div class="board">
            <form method="POST" action="marubatsu.php">
                <?php for ($i = 0; $i < 9; $i++): ?>
                    <?php 
                    $value = $board[$i];
                    $is_empty = $value === CELL_EMPTY;
                    $disabled = $is_game_over || !$is_empty;
                    $class = 'cell-button ' . $value;
                    ?>
                    <button 
                        type="submit" 
                        name="move" 
                        value="<?= $i ?>" 
                        class="<?= $class ?>" 
                        <?= $disabled ? 'disabled' : '' ?>
                    >
                        <?= htmlspecialchars($value) ?>
                    </button>
                <?php endfor; ?>
            </form>
        </div>

        <div class="reset-form">
            <form method="POST" action="marubatsu.php">
                <button type="submit" name="reset" value="1">新しいゲームを始める</button>
            </form>
        </div>
    </div>
</body>
</html>