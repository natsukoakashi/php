<?php
// ===========================================
// 投稿一覧表示（index.php）
// ===========================================
// ログイン済みユーザーのみアクセス可能
// 全ての投稿を新しい順に表示
// 自分の投稿のみ編集・削除が可能
// ===========================================


// -----------------------------------------
// 1. セッション開始（最初に記述必須！）
// -----------------------------------------
session_start();


// -----------------------------------------
// 2. 共通関数ファイルを読み込み
// -----------------------------------------
include("funcs.php");


// -----------------------------------------
// 3. ログイン状態をチェック
// -----------------------------------------
// ★ヒント: もしこの処理がなかったらどうなる？
// 未ログインでもこのページにアクセスできてしまいます。
// なぜこの1行が必要なのか、説明できるようになろう！
// -----------------------------------------
sschk();

// ===========================================
// ★★★ 疎通確認用（Step 1, 2 の確認が終わったら削除！）★★★
// ===========================================
// ここまで来れたらログイン成功！
// Step 3 に進む前にこの行を削除してね
// ===========================================


// -----------------------------------------
// 4. DB接続
// -----------------------------------------
$pdo = db_conn();


// -----------------------------------------
// TODO: 5. SELECT文を作成・実行
// -----------------------------------------
// posts テーブルと users テーブルを JOIN して、
// 投稿データと一緒に投稿者名も取得しよう
// 
// 【JOINの書き方例 1行で書きましょう】
// SELECT posts.*, users.name as user_name 
// FROM posts 
// JOIN users ON posts.user_id = users.id 
// ORDER BY posts.created_at DESC
// -----------------------------------------

/* ↓↓↓ ここにコードを追加 ↓↓↓ */
$sql = "SELECT posts.*, users.name as user_name 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        ORDER BY posts.created_at DESC";
/* ↑↑↑ ここまで ↑↑↑ */
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();


// -----------------------------------------
// 6. データ表示用のHTML文字列を生成
// -----------------------------------------
$view = "";

if ($status == false) {
    sql_error($stmt);
} else {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $view .= '<div class="post-card">';
        $view .= '  <div class="post-header">';
        $view .= '    <span class="post-author">' . h($row["user_name"]) . '</span>';
        $view .= '    <span class="post-date">' . h($row["created_at"]) . '</span>';
        $view .= '  </div>';
        $view .= '  <div class="post-content">' . h($row["content"]) . '</div>';
        
        // -----------------------------------------
        // TODO: 7. 自分の投稿のみ編集・削除ボタンを表示
        // -----------------------------------------
        // 投稿の user_id と、ログイン中のユーザーIDを比較しよう
        // ヒント: ログイン時に $_SESSION["user_id"] にログイン中のユーザーIDを保存しましたね？
        // （login_act.php の手順7を見てみよう）
        // -----------------------------------------
        /* ↓↓↓ ここにコードを追加（if文の条件を書く）↓↓↓ */
        if ("ここに条件を書く") {
        /* ↑↑↑ ここまで ↑↑↑ */
            $view .= '  <div class="post-actions">';
            $view .= '    <a href="edit.php?id=' . h($row["id"]) . '" class="btn btn-secondary btn-sm">編集</a>';
            $view .= '    <a href="delete.php?id=' . h($row["id"]) . '" class="btn btn-danger btn-sm" onclick="return confirm(\'本当に削除しますか？\')">削除</a>';
            $view .= '  </div>';
        }
        
        $view .= '</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投稿一覧 | 掲示板アプリ</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- ===========================================
     ナビゲーション
     =========================================== -->
<nav class="navbar">
    <a class="navbar-brand" href="index.php">掲示板アプリ</a>
    <div class="navbar-links">
        <a href="index.php">投稿一覧</a>
        <a href="create.php">新規投稿</a>
    </div>
    <div class="navbar-user">
        <span class="navbar-user-info">
            <span class="navbar-user-name"><?= h($_SESSION["user_name"]) ?></span>
        </span>
        <a href="logout.php" class="logout-link">ログアウト</a>
    </div>
</nav>

<!-- ===========================================
     メインコンテンツ
     =========================================== -->
<div class="container">
    <h1 class="page-title">投稿一覧</h1>

    <!-- -----------------------------------------
         投稿一覧表示エリア
         ----------------------------------------- -->
    <?php if ($view): ?>
        <?= $view ?>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-state-icon">📝</div>
            <div class="empty-state-text">まだ投稿がありません</div>
            <a href="create.php" class="btn btn-primary">最初の投稿をする</a>
        </div>
    <?php endif; ?>

    <!-- -----------------------------------------
         新規投稿へのリンク
         ----------------------------------------- -->
    <?php if ($view): ?>
    <div class="text-center mt-4">
        <a href="create.php" class="btn btn-primary">新規投稿する</a>
    </div>
    <?php endif; ?>
</div>

<!-- ===========================================
     フッター
     =========================================== -->
<footer class="footer">
    <p>PHP課題 掲示板アプリ - CRUD + ログイン機能の学習用</p>
</footer>

</body>
</html>

