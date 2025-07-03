<?php
$current = basename($_SERVER['SCRIPT_NAME']);
function isActive($file) {
    global $current;
    // Active tab: teal background, white text, bold
    return $current === $file ? ' bg-teal-700 text-white font-bold shadow-md' : '';
}
echo '
<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-[rgb(45_83_86)] border-r border-gray-200 lg:translate-x-0" aria-label="Sidebar">
   <div class="h-full px-3 pb-4 overflow-y-auto bg-[rgb(45_83_86)]">
      <ul class="space-y-2 font-medium">
         <li>
            <a href="/SwiftCart/Page/Admin/DashBoard.php" class="flex items-center p-2 rounded-lg transition-colors duration-200 text-white hover:bg-teal-600 hover:text-white group'.isActive('DashBoard.php').'">
               <svg class="w-5 h-5 text-white transition duration-75 " fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"/></svg>
               <span class="ms-3">Dashboard</span>
            </a>
         </li>
         <li>
            <a href="/SwiftCart/Page/Admin/Categories.php" class="flex items-center p-2 rounded-lg transition-colors duration-200 text-white hover:bg-teal-600 hover:text-white group'.isActive('Categories.php').'">
               <svg class="w-5 h-5 text-white transition duration-75 " fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
               <span class="ms-3">Categories</span>
            </a>
         </li>
         <li>
            <a href="/SwiftCart/Page/Admin/Products.php" class="flex items-center p-2 rounded-lg transition-colors duration-200 text-white hover:bg-teal-600 hover:text-white group'.isActive('Products.php').'">
               <svg class="w-5 h-5 text-white transition duration-75 " fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2l9 4.5v11L12 22l-9-4.5v-11L12 2z"/></svg>
               <span class="ms-3">Products</span>
            </a>
         </li>
         <li>
            <a href="/SwiftCart/Page/Admin/Order.php" class="flex items-center p-2 rounded-lg transition-colors duration-200 text-white hover:bg-teal-600 hover:text-white group'.isActive('Order.php').'">
               <svg class="w-5 h-5 text-white transition duration-75 " fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 7h18M3 12h18M3 17h18"/></svg>
               <span class="ms-3">Orders</span>
            </a>
         </li>
         <li>
            <a href="/SwiftCart/Page/Admin/User.php" class="flex items-center p-2 rounded-lg transition-colors duration-200 text-white hover:bg-teal-600 hover:text-white group'.isActive('User.php').'">
               <svg class="w-5 h-5 text-white transition duration-75 " fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 3.13a4 4 0 010 7.75M8 3.13a4 4 0 000 7.75"/></svg>
               <span class="ms-3">Users</span>
            </a>
         </li>
         <li>
            <a href="/SwiftCart/Page/Admin/Vender.php" class="flex items-center p-2 rounded-lg transition-colors duration-200 text-white hover:bg-teal-600 hover:text-white group'.isActive('Vender.php').'">
               <svg class="w-5 h-5 text-white transition duration-75 " fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
               <span class="ms-3">Vender</span>
            </a>
         </li>
         <li>
            <a href="/SwiftCart/Page/Admin/Rating.php" class="flex items-center p-2 rounded-lg transition-colors duration-200 text-white hover:bg-teal-600 hover:text-white group'.isActive('Rating.php').'">
               <svg class="w-5 h-5 text-white transition duration-75 " fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="12 2 15 8.5 22 9.3 17 14.1 18.2 21 12 17.8 5.8 21 7 14.1 2 9.3 9 8.5 12 2"/></svg>
               <span class="ms-3">Rating</span>
            </a>
         </li>
         <li>
            <a href="/SwiftCart/Page/Admin/ContactUs.php" class="flex items-center p-2 rounded-lg transition-colors duration-200 text-white hover:bg-teal-600 hover:text-white group'.isActive('ContactUs.php').'">
               <svg class="w-5 h-5 text-white transition duration-75 " fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10a9 9 0 11-18 0 9 9 0 0118 0z"/><path d="M9 9h6v6H9z"/></svg>
               <span class="ms-3">ContactUs</span>
            </a>
         </li>
         <li>
            <a href="/SwiftCart/Page/Admin/logout.php" class="flex items-center p-2 rounded-lg transition-colors duration-200 text-white hover:bg-red-600 hover:text-white group'.isActive('logout.php').'">
               <svg class="w-5 h-5 text-white transition duration-75 " fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1"/></svg>
               <span class="ms-3">Logout</span>
            </a>
         </li>
      </ul>
   </div>
</aside>
'
?>