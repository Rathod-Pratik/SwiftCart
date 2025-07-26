<?php
require __DIR__ . '/../../Database/db.php';

$stmt = $pdo->prepare('SELECT * FROM category');
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo '
<section class="py-8">
  <div class="flex justify-between lg:w-[90vw] m-auto mt-3 px-4">
    <h1 class="text-2xl font-semibold">Featured Categories</h1>
    <div class="flex gap-3">
      <button id="prevBtn" class="px-6 py-2 rounded-md font-semibold shadow text-white bg-[#d09523] hover:bg-[#f4b942] cursor-pointer text-sm transition">
        ←
      </button>
      <button id="nextBtn" class="px-6 py-2 rounded-md font-semibold shadow text-white bg-[#d09523] hover:bg-[#f4b942] cursor-pointer text-sm transition">
        → 
      </button>
    </div>
  </div>

  <!-- Scrollable Slider Container -->
  <div id="scrollWrapper" class="overflow-x-auto overflow-y-hidden mt-6 px-4 max-w-[90vw] m-auto scrollbar-hide scroll-smooth">
    <div class="flex gap-6 w-max">
';

foreach ($categories as $category) {
    $catName = htmlspecialchars($category['name']);
    $catImage = htmlspecialchars($category['image']); // Assuming 'image' is the column name
    $catId = urlencode($category['id']); // You can also use 'slug' or 'name' depending on routing

    echo '
      <a href="product.php?category=' . $catName . '" class="min-w-[250px]  rounded-lg shadow p-4 text-center hover:shadow-lg transition">
        <img src="' . $catImage . '" alt="' . $catName . '" class="m-auto w-32 h-32 object-cover mb-2 bg-gray-100 rounded-full" />
        <h2 class="font-semibold text-lg">' . $catName . '</h2>
      </a>
    ';
}

echo '
    </div>
  </div>
</section>

<style>
.scrollbar-hide::-webkit-scrollbar{display:none;}
.scrollbar-hide{-ms-overflow-style:none;scrollbar-width:none;}
</style>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const container = document.getElementById("scrollWrapper");
  const STEP = 260;
  const DURATION = 400;

  function smoothScrollBy(delta){
    return new Promise(res=>{
      const start = container.scrollLeft;
      const target = start + delta;
      container.scrollTo({left:target, behavior:"smooth"});
      setTimeout(res, DURATION);
    });
  }

  document.getElementById("nextBtn").addEventListener("click",()=>smoothScrollBy(STEP));
  document.getElementById("prevBtn").addEventListener("click",()=>smoothScrollBy(-STEP));
});
</script>
';
?>
