CREATE TABLE IF NOT EXISTS recipes (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  title TEXT NOT NULL,
  ingredients TEXT NOT NULL,
  steps TEXT NOT NULL,
  cooking_time TEXT
);

INSERT INTO recipes (title, ingredients, steps, cooking_time) VALUES (
  '豪華な卵かけご飯',
  'ごはん,卵,しょうゆ,味の素,ごま油',
  '1. ご飯を茶碗に盛り付ける。\n2. 卵を卵黄と黄身で分ける。\n3. ご飯に窪みを作って卵黄を入れる。\n4. 白身を周りにかける。\n5. 味の素を多めに入れる。ごま油を少し入れる。\n6. 混ぜる。',
  '5分'
);