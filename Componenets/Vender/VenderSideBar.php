<?php
$current = basename($_SERVER['SCRIPT_NAME']);
function isActive($file)
{
   global $current;
   return $current === $file ? 'bg-white text-black font-semibold' : 'text-[#a0aec0] hover:text-white hover:bg-[#4fd1c5]';
}
echo '
<aside id="sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 bg-[#f8fafd] border-r border-gray-200 lg:translate-x-0 transition-transform -translate-x-full" aria-label="Sidebar">
   <div class="h-full px-3 pb-4 overflow-y-auto">
      <ul class="space-y-2 font-medium text-sm">
         <li>
            <a href="/vender/dashboard" class="flex items-center p-2 rounded-xl transition-all ' . isActive('DashBoard.php') . '">
               <div class="w-6 h-6 flex items-center justify-center rounded-md bg-[#4fd1c5]">
                  <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                     <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"/>
                  </svg>
               </div>
               <span class="ml-3">Dashboard</span>
            </a>
         </li>
         <li>
            <a href="/vender/product" class="flex items-center p-2 rounded-xl transition-all ' . isActive('Product.php') . '">
               <div class="w-6 h-6 flex items-center justify-center rounded-md bg-[#4fd1c5]">
                  <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                     <rect x="3" y="3" width="7" height="7"/>
                     <rect x="14" y="3" width="7" height="7"/>
                     <rect x="14" y="14" width="7" height="7"/>
                     <rect x="3" y="14" width="7" height="7"/>
                  </svg>
               </div>
               <span class="ml-3">Product</span>
            </a>
         </li>
         <li>
            <a href="/vender/order" class="flex items-center p-2 rounded-xl transition-all ' . isActive('Order.php') . '">
               <div class="w-6 h-6 flex items-center justify-center rounded-md bg-[#4fd1c5]">
                  <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                     <path d="M12 2l9 4.5v11L12 22l-9-4.5v-11L12 2z"/>
                  </svg>
               </div>
               <span class="ml-3">Order</span>
            </a>
         </li>
         <li>
            <a href="/vender/rating" class="flex items-center p-2 rounded-xl transition-all ' . isActive('Rating.php') . '">
               <div class="w-6 h-6 flex items-center justify-center rounded-md bg-[#4fd1c5]">
                  <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                     <path d="M3 7h18M3 12h18M3 17h18"/>
                  </svg>
               </div>
               <span class="ml-3">Rating</span>
            </a>
         </li>
         <li>
            <a href="/vender/Query" class="flex items-center p-2 rounded-xl transition-all ' . isActive('Queries.php') . '">
               <div class="w-6 h-6 flex items-center justify-center rounded-md bg-[#4fd1c5]">
                  <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                     <path d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 3.13a4 4 0 010 7.75M8 3.13a4 4 0 000 7.75"/>
                  </svg>
               </div>
               <span class="ml-3">Queries</span>
            </a>
         </li>
         <li>
            <a href="/vender/profile" class="flex items-center p-2 rounded-xl transition-all ' . isActive('Profile.php') . '">
               <div class="w-6 h-6 flex items-center justify-center rounded-md bg-[#4fd1c5]">
                  <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                     <circle cx="12" cy="12" r="10"/>
                     <path d="M12 6v6l4 2"/>
                  </svg>
               </div>
               <span class="ml-3">Profile</span>
            </a>
         </li>
         <li>
            <a href="javascript:void(0)" onclick="Logout()" class="flex items-center p-2 rounded-xl transition-all cursor-pointer text-[#a0aec0] hover:text-white hover:bg-[#4fd1c5]">
               <div class="w-6 h-6 flex items-center justify-center rounded-md bg-[#4fd1c5]">
                  <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                     <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1"/>
                  </svg>
               </div>
               <span class="ml-3">Logout</span>
            </a>
         </li>
      </ul>
   </div>
</aside>
<script>
  function Logout(){

  fetch("../AJAX/Logout.php", {
  method: "POST"
})
.then(res => res.json())
.then(data => {
  window.location.href = "/login"; // Redirect to login page
});
  }
</script>
';
