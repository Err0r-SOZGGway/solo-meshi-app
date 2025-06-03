<link rel="stylesheet" href="../style/style.css">

<?php
$db = new PDO('sqlite:' . __DIR__ . '/db/recipes.db');
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
</body>
</html>