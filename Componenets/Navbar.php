<?php

require __DIR__ . '/../Database/db.php';

if (isset($_COOKIE['authToken']) || isset($_COOKIE['AdminToken']) || isset($_COOKIE['venderToken'])) {
  if (isset($_COOKIE['authToken'])) {
    $userData = json_decode($_COOKIE['authToken'], true);
  } elseif (isset($_COOKIE['AdminToken'])) {
    $userData = json_decode($_COOKIE['AdminToken'], true);
  } elseif (isset($_COOKIE['venderToken'])) {
    $userData = json_decode($_COOKIE['venderToken'], true);
  } else {
    echo json_encode(['status' => 'unauthorized', 'message' => 'User not logged in or cookie missing']);
    exit;
  }
  $userid = $userData['id'];
  $sql = "SELECT COUNT(*) AS total FROM wishlist WHERE userid = :userid";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(['userid' => $userid]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  $wishlistCount = $row['total'];

  $sql1 = "SELECT COUNT(*) AS total FROM cart WHERE userid = :userid";
  $stmt1 = $pdo->prepare($sql1);
  $stmt1->execute(['userid' => $userid]);
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
  $CartlistCount = $row1['total'];
}

echo '
<nav class="bg-[#204d4f] w-full z-20 top-0 start-0 sticky">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
    <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
      <span class="self-center text-white text-2xl font-semibold whitespace-nowrap">SwiftCart</span>
    </a>
    <div class="hidden md:flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse gap-4 relative">
';

if (isset($_COOKIE['authToken']) || isset($_COOKIE['AdminToken']) || isset($_COOKIE['venderToken'])) {
  echo '
      <!-- Wishlist Icon with Badge -->
     <div class="relative">
        <a href="/SwiftCart/wishlist" class="text-gray-400 cursor-pointer">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="#204d4f" stroke="white" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/>
          </svg> 
          ';
          if ($wishlistCount > 0) {
            echo '<span id="wishListLength" class="absolute -top-2 -right-2 bg-red-600 text-white text-xs px-1.5 py-0.5 rounded-full">' . $wishlistCount  . '</span>';
          } else {
            echo '<span id="wishListLength" class="hidden absolute -top-2 -right-2 bg-red-600 text-white text-xs px-1.5 py-0.5 rounded-full">0</span>';
          }
          echo ' 
        </a>
    </div>
    <div class="relative">
        <a href="/SwiftCart/cart" class="text-gray-400 cursor-pointer">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 512 512" fill="#204d4f" stroke="white" stroke-width="32" stroke-linecap="round" stroke-linejoin="round">
            <path d="M80 160v256c0 17.7 14.3 32 32 32h288c17.7 0 32-14.3 32-32V160"/>
            <path d="M432 160H80"/>
            <path d="M320 160v-32c0-35.3-28.7-64-64-64s-64 28.7-64 64v32"/>
            <path d="M112 160l144 112 144-112"/>
          </svg>';
          if ($CartlistCount > 0) {
            echo '<span id="CartLength" class="absolute -top-2 -right-2 bg-red-600 text-white text-xs px-1.5 py-0.5 rounded-full">'
              . $CartlistCount .
              '</span>';
          } else {
            echo '<span id="CartLength" class="absolute hidden -top-2 -right-2 bg-red-600 text-white text-xs px-1.5 py-0.5 rounded-full">0</span>';
          }
  echo '
        </a>
    </div>

      <div class="relative">
        <button id="userButton" class="text-gray-400 focus:outline-none cursor-pointer">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="#204d4f" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
            <circle cx="12" cy="7" r="4" />
          </svg>
        </button>

        <!-- Dropdown -->
        <div id="dropdownMenu" class="hidden absolute right-0 mt-2 z-10 bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700">
          <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
            <li><a href="/SwiftCart/profile" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Account</a></li>
            <li><a href="/SwiftCart/order" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Order</a></li>
            <li><p onclick="Logout()" class="block cursor-pointer px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Logout</p></li>
          </ul>
        </div>
      </div>

      
    ';
} else {
  echo '
      <a href="/SwiftCart/login" class="text-white bg-[#d09523] hover:bg-[#f4b942] font-medium rounded-lg text-sm px-4 py-2">Login</a>
      <a href="/SwiftCart/signup" class="text-white bg-[#d09523] hover:bg-[#f4b942] font-medium rounded-lg text-sm px-4 py-2">SignUp</a>
    ';
}

echo '
   </div>
          <button id="menu-toggle" class="md:hidden cursor-pointer text-white mr-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
           <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1">
              <ul class="flex mt-4 font-medium border border-gray-100 rounded-lg md:space-x-8 rtl:space-x-reverse flex-row md:mt-0 md:border-0">
                <li><a href="/SwiftCart" class="block py-2 px-3 text-white bg-blue-700 rounded-sm md:bg-transparent md:text-[#d09523] md:p-0" aria-current="page">Home</a></li>
                <li><a href="/SwiftCart/product" class="block py-2 px-3 text-white rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-[#d09523] md:p-0">Products</a></li>
                <li><a href="/SwiftCart/about" class="block py-2 px-3 text-white rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-[#d09523] md:p-0">About</a></li>
                <li><a href="/SwiftCart/contact" class="block py-2 px-3 text-white rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-[#d09523] md:p-0">Contact</a></li>
              </ul>
            </div>
             <div id="navbar-sticky"
              class="fixed h-full w-[80vw] max-w-xs top-0 right-0 z-40 md:hidden rounded-l-xl p-4 transition-transform duration-300 transform translate-x-full backdrop-blur bg-[#204d4f] shadow-xl border-l border-white">
                  <button id="close-navbar" class="md:hidden cursor-pointer text-white mr-4 flex justify-end w-[92%]">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                </button>
                <ul class="flex flex-col items-center justify-center gap-6 text-lg font-medium mt-10">
                  <li><a href="/SwiftCart/" class="text-white hover:text-[#d09523]">Home</a></li>
                  <li><a href="#" class="text-white hover:text-[#d09523]">About</a></li>
                  <li><a href="#" class="text-white hover:text-[#d09523]">Services</a></li>
                  <li><a href="/SwiftCart/contact" class="text-white hover:text-[#d09523]">Contact</a></li>
                  <li><a href="#" class="text-white hover:text-[#d09523]">Acount</a></li>
                  <li><a href="#" class="text-white hover:text-[#d09523]">Order</a></li>
                  <li class="text-white hover:text-[#d09523] w-full"><button onclick="Logout()" >Logout</button></li>
                </ul>
                <div class="py-3 flex justify-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse gap-4 relative">
                ';

              if (isset($_COOKIE['authToken']) || isset($_COOKIE['AdminToken']) || isset($_COOKIE['venderToken'])) {
                echo '
                    <!-- Wishlist Icon with Badge -->
                     <div class="relative">
                      <button class="text-gray-400 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="#204d4f" stroke="white" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/>
                        </svg> 
                        ';
                        if ($wishlistCount > 0) {
                          echo '<span id="wishListLength" class="absolute -top-2 -right-2 bg-red-600 text-white text-xs px-1.5 py-0.5 rounded-full">' . $wishlistCount  . '</span>';
                        } else {
                          echo '<span id="wishListLength" class="hidden absolute -top-2 -right-2 bg-red-600 text-white text-xs px-1.5 py-0.5 rounded-full">0</span>';
                        }
                        echo ' 
                      </button>
                     </div>

                    <!-- Cart Icon with Badge -->
                    <div class="relative">
                      <button class="text-gray-400 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 512 512" fill="#204d4f" stroke="white" stroke-width="32" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M80 160v256c0 17.7 14.3 32 32 32h288c17.7 0 32-14.3 32-32V160"/>
                          <path d="M432 160H80"/>
                          <path d="M320 160v-32c0-35.3-28.7-64-64-64s-64 28.7-64 64v32"/>
                          <path d="M112 160l144 112 144-112"/>
                        </svg>';
                  if ($CartlistCount > 0) {
                    echo '<span id="CartLength" class="absolute -top-2 -right-2 bg-red-600 text-white text-xs px-1.5 py-0.5 rounded-full">'
                      . $CartlistCount .
                      '</span>';
                  } else {
                    echo '<span id="CartLength" class="absolute hidden -top-2 -right-2 bg-red-600 text-white text-xs px-1.5 py-0.5 rounded-full">0</span>';
                  }
                  echo '
                      </button>
                    </div>

                    <!-- User Icon with Dropdown -->
                    <div class="relative">
                      <button  class="text-gray-400 focus:outline-none cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="#204d4f" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                          <circle cx="12" cy="7" r="4" />
                        </svg>
                      </button>                     
                    </div>

                    
                  ';
            } else {
              echo '
                    <a href="/SwiftCart/login" class="text-white bg-[#d09523] hover:bg-[#f4b942] font-medium rounded-lg text-sm px-4 py-2">Login</a>
                    <button type="button" class="text-white bg-[#d09523] hover:bg-[#f4b942] font-medium rounded-lg text-sm px-4 py-2">SignUp</button>
                    ';
            }
            echo '
                </div>
              </div>


  </div>
</nav>

<script>
  const userBtn = document.getElementById("userButton");
  const dropdown = document.getElementById("dropdownMenu");

  userBtn.addEventListener("click", () => {
    dropdown.classList.toggle("hidden");
  });

  document.addEventListener("click", (e) => {
    if (!userBtn.contains(e.target) && !dropdown.contains(e.target)) {
      dropdown.classList.add("hidden");
    }
  });

  const toggleBtn = document.getElementById("menu-toggle");
  const navbar = document.getElementById("navbar-sticky");
  const closeBtn = document.getElementById("close-navbar");

  toggleBtn.addEventListener("click", () => {
    navbar.classList.remove("hidden");
    setTimeout(() => {
      navbar.classList.remove("translate-x-full");
    }, 10);
    document.body.style.overflow = "hidden";
  });

  closeBtn.addEventListener("click", () => {
    navbar.classList.add("translate-x-full");
    document.body.style.overflow = "";
    setTimeout(() => {
      navbar.classList.add("hidden");
    }, 300);
  });

  // Optional: close navbar when clicking outside
  document.addEventListener("click", (e) => {
    if (!navbar.contains(e.target) && !toggleBtn.contains(e.target) && !navbar.classList.contains("hidden")) {
      navbar.classList.add("translate-x-full");
      document.body.style.overflow = "";
      setTimeout(() => {
        navbar.classList.add("hidden");
      }, 300);
    }
  });

  function Logout(){

  fetch("/SwiftCart/AJAX/Logout.php", {
  method: "POST"
})
.then(res => res.json())
.then(data => {
  window.location.href = "/SwiftCart/login"; // Redirect to login page
});
  }
</script>
';
