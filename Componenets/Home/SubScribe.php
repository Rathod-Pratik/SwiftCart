<?php
echo '
<section class="bg-[#f6f6f6] rounded-2xl max-w-7xl mx-auto p-6 md:p-12 flex flex-col md:flex-row items-center justify-between gap-10">
  <!-- Left Content -->
  <div class="md:w-1/2">
    <h2 class="text-3xl md:text-4xl font-semibold mb-4 leading-snug text-gray-900">
      Subscribe To Our<br />Newsletter
    </h2>
    <p class="text-gray-600 mb-6">
      Subscribe to our email newsletter today to receive update on the latest news
    </p>
    <form class="flex items-center bg-white rounded-full shadow-sm p-2 w-full max-w-md">
      <span class="px-3 text-gray-400 text-xl">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z" />
</svg>
      </span>
      <input type="email" placeholder="Enter your Email" class="flex-grow outline-none bg-transparent text-sm px-2" />
      <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium px-5 py-2 rounded-full transition-shadow shadow-md ml-2">
        Subscribe
      </button>
    </form>
  </div>

  <!-- Right Image -->
  <div class="md:w-1/2 hidden md:block">
    <div class="rounded-2xl overflow-hidden">
      <img src=\'/SwiftCart/Image/Email.webp\' alt="Subscribe" class="bg-cover w-[572px] h-[243px] object-cover" />
    </div>
  </div>
</section>



'
?>