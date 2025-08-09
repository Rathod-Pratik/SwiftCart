<?php

require __DIR__ . "/../../Database/db.php";

$venderToken = $_COOKIE['AdminToken'] ?? null;

if ($venderToken) {
  $vender = json_decode($venderToken, true);
  $vendor_id = $vender['id'] ?? null;

  if ($vendor_id) {
    $stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->execute([$vendor_id]);
    $vendor = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($vendor) {
      $name = $vendor['name'];
    }
  }
}

echo ' <nav class="fixed top-0 z-50 w-full bg-[#f8fafd]">
  <div class="px-3 py-3 lg:px-5 lg:pl-3">
    <div class="flex items-center justify-between">
      <div class="flex items-center justify-start rtl:justify-end">
        <button onclick="openSideBar()" data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none cursor-pointer  ">
            <span class="sr-only">Open sidebar</span>
            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
               <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
            </svg>
         </button>
        <a href="/SwiftCart" class="flex ms-2 md:me-24">
          <span class="self-center text-xl  font-semibold sm:text-2xl whitespace-nowrap ">SwiftCart</span>
        </a>
      </div>
      <div class="flex items-center">
      <div class="flex items-center ms-3 gap-3">
      <p class="text-[#a0aec0]">Welcome ' . htmlspecialchars($name) . '</p>
          </div>
        </div>
    </div>
  </div>
</nav>
<script>
function openSideBar(){
let element = document.getElementById("sidebar");
  element.classList.toggle("-translate-x-full");
}
  
</script>
'
?>