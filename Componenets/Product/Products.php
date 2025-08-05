<?php
require __DIR__ . '/../../Database/db.php';
require __DIR__ . '/../../Database/Function.php';

$table = 'wishlist';

$createSQL = "CREATE TABLE IF NOT EXISTS wishlist (
    id SERIAL PRIMARY KEY,
    userid INTEGER REFERENCES users(id) ON DELETE CASCADE,
    productid INTEGER REFERENCES product(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";
checkAndCreateTable($pdo, $table, $createSQL);

$table1 = 'cart';

$createSQL1 = "CREATE TABLE IF NOT EXISTS cart (
    id SERIAL PRIMARY KEY,
    userid INTEGER REFERENCES users(id) ON DELETE CASCADE,
    productid INTEGER REFERENCES product(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";
checkAndCreateTable($pdo, $table1, $createSQL1);


echo '
<section class="py-8">
  <div class="flex justify-between lg:w-[90vw] m-auto mt-3 px-4">
    <h1 id="ProductResult" data-aos="rade-right" class="text-lg text-gray-500 font-semibold"></h1>
    <div data-aos="rade-left" class="flex gap-2 items-center">
      <label class="text-gray-500 text-sm font-medium block mb-1">Short By</label>
      <select required name="category" id="category" class="border border-gray-500 text-sm rounded-lg block  p-2.5 outline-none" onchange="filterProduct(this.value)">
        <option class="text-gray-500" value="All Product" selected>All Product</option>';
        
        $categories = $pdo->query("SELECT * FROM category ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($categories as $category) {
          echo '<option class="text-gray-500" value="' . htmlspecialchars($category['name']) . '">' . htmlspecialchars($category['name']) . '</option>';
        }

echo '
      </select>
    </div>
  </div>
  <div id="productItems" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 w-[90vw] mx-auto mt-5">
  </div>
</section>
';
?>
