<?php
require __DIR__ . '/../../Database/db.php';

$stmt = $pdo->prepare('SELECT * FROM category');
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo '
<section class="py-8">
  <div class="flex justify-between lg:w-[90vw] m-auto mt-3 px-4">
    <h1 data-aos="fade-right" class="text-2xl font-semibold">Featured Categories</h1>
    <div data-aos="fade-left" class="flex gap-3">
      <button id="prevBtn" class="px-6 py-2 rounded-md font-semibold shadow text-white bg-[#d09523] hover:bg-[#f4b942] cursor-pointer text-sm transition">
        ←
      </button>
      <button id="nextBtn" class="px-6 py-2 rounded-md font-semibold shadow text-white bg-[#d09523] hover:bg-[#f4b942] cursor-pointer text-sm transition">
        → 
      </button>
    </div>
  </div>

  <!-- Scrollable Slider Container -->
  <div data-aos="fade-down" id="scrollWrapper" class="overflow-x-auto overflow-y-hidden mt-6 px-4 max-w-[90vw] m-auto scrollbar-hide scroll-smooth">
    <div class="flex gap-6 w-max">
';

foreach ($categories as $category) {
    $catName = htmlspecialchars($category['name']);
    $catImage = htmlspecialchars($category['image']);
    
    // Properly encode the category name for use in URL
    $encodedCatName = urlencode($category['name']);

    echo '
      <a href="product?category=' . $encodedCatName . '" class="min-w-[250px] rounded-lg shadow p-4 text-center hover:shadow-lg transition">
        <img src="' . $catImage . '" alt="' . $catName . '" class="m-auto w-32 h-32 object-contain mb-2 bg-gray-100 rounded-full" />
        <h2 class="font-semibold text-lg">' . $catName . '</h2>
      </a>
    ';
}


echo '
    </div>
  </div>
</section>
';
?>
