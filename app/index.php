<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$dbFile = __DIR__ . '/db/recipes.db';

// ディレクトリがなければ作成（初回用）
if (!file_exists(__DIR__ . '/db')) {
    mkdir(__DIR__ . '/db', 0777, true);
}

// SQLiteに接続
$db = new PDO('sqlite:' . $dbFile);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// テーブルがなければ作成（初回用）
$db->exec("
    CREATE TABLE IF NOT EXISTS recipes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        ingredients TEXT NOT NULL,
        steps TEXT NOT NULL,
        cooking_time TEXT
    );
");

// サンプルレシピがなければ1件追加
$count = $db->query("SELECT COUNT(*) FROM recipes")->fetchColumn();
if ($count == 0) {
    $stmt = $db->prepare("INSERT INTO recipes (title, ingredients, steps, cooking_time) VALUES (?, ?, ?, ?)");
    $stmt->execute([
      '豪華な卵かけご飯',
      'ごはん,卵,しょうゆ,味の素,ごま油',
      '1. ご飯を茶碗に盛り付ける。\n2. 卵を卵黄と黄身で分ける。\n3. ご飯に窪みを作って卵黄を入れる。\n4. 白身を周りにかける。\n5. 味の素を多めに入れる。ごま油を少し入れる。\n6. 混ぜる。',
      '5分'
    ]);
}

// 検索処理
$recipes = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = $_POST['ingredients'] ?? '';
    $inputIngredients = array_map('trim', explode(',', $input));

    $stmt = $db->query('SELECT * FROM recipes');
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        foreach ($inputIngredients as $ingredient) {
            if (stripos($row['ingredients'], $ingredient) !== false) {
                $recipes[] = $row;
                break;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ソロメシ提案くん</title>
  <style>
    body { font-family: sans-serif; margin: 2em; }
    input, textarea { width: 100%; padding: 0.5em; margin: 1em 0; }
    .recipe { border: 1px solid #ccc; padding: 1em; margin-bottom: 1em; }
  </style>
</head>
<body>
  <h1>ソロメシ提案くん</h1>
  <form method="POST">
    <label>食材をカンマ区切りで入力してください（例: 卵, ごはん, ネギ）</label>
    <input type="text" name="ingredients" required>
    <button type="submit">提案を表示</button>
  </form>

  <?php if (!empty($recipes)): ?>
    <h2>見つかったレシピ</h2>
    <?php foreach ($recipes as $recipe): ?>
      <div class="recipe">
        <h3><?= htmlspecialchars($recipe['title']) ?></h3>
        <p><strong>材料:</strong> <?= htmlspecialchars($recipe['ingredients']) ?></p>
        <p><strong>手順:</strong><br><?= nl2br(htmlspecialchars($recipe['steps'])) ?></p>
        <p><strong>調理時間:</strong> <?= htmlspecialchars($recipe['cooking_time']) ?></p>
      </div>
    <?php endforeach; ?>
  <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <p>該当するレシピが見つかりませんでした。</p>
  <?php endif; ?>
  <p><a href="logout.php">ログアウト</a></p>
</body>
</html>
