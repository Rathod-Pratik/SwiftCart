<?php
 
 echo '<section class="bg-white py-12 px-4 md:px-12 m-auto w-[80vw]">
  <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-10 items-start">
    <!-- Left Contact Info -->
    <div>
      <h2 class="text-3xl font-bold text-gray-900 mb-4">Get in touch</h2>
      <p class="text-gray-700 mb-8">
        We\'re here for you every step of the way. Whether you have questions, need order assistance, or want to share feedback, our friendly customer support team is ready to assist. Reach out to us via:
      </p>
      <div class="space-y-5">
        <!-- Mail -->
        <div class="flex items-center gap-4">
          <div class="bg-orange-100 p-3 rounded-lg text-orange-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z" />
</svg>
          </div>
          <div>
            <p class="text-sm text-gray-500">Mail</p>
            <p class="text-base font-medium text-gray-900">mdaminur.oc@gmail.com</p>
          </div>
        </div>
        <!-- Phone -->
        <div class="flex items-center gap-4">
          <div class="bg-purple-100 p-3 rounded-lg text-purple-500">
          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor" class="w-5 h-5 text-purple-500">
            <g id="SVGRepo_iconCarrier">
                <path d="M14.3308 15.9402L15.6608 14.6101C15.8655 14.403 16.1092 14.2384 16.3778 14.1262C16.6465 14.014 16.9347 13.9563 17.2258 13.9563C17.517 13.9563 17.8052 14.014 18.0739 14.1262C18.3425 14.2384 18.5862 14.403 18.7908 14.6101L20.3508 16.1702C20.5579 16.3748 20.7224 16.6183 20.8346 16.887C20.9468 17.1556 21.0046 17.444 21.0046 17.7351C21.0046 18.0263 20.9468 18.3146 20.8346 18.5833C20.7224 18.8519 20.5579 19.0954 20.3508 19.3L19.6408 20.02C19.1516 20.514 18.5189 20.841 17.8329 20.9541C17.1469 21.0672 16.4427 20.9609 15.8208 20.6501C10.4691 17.8952 6.11008 13.5396 3.35083 8.19019C3.03976 7.56761 2.93414 6.86242 3.04914 6.17603C3.16414 5.48963 3.49384 4.85731 3.99085 4.37012L4.70081 3.65015C5.11674 3.23673 5.67937 3.00464 6.26581 3.00464C6.85225 3.00464 7.41488 3.23673 7.83081 3.65015L9.40082 5.22021C9.81424 5.63615 10.0463 6.19871 10.0463 6.78516C10.0463 7.3716 9.81424 7.93416 9.40082 8.3501L8.0708 9.68018C8.95021 10.8697 9.91617 11.9926 10.9608 13.04C11.9994 14.0804 13.116 15.04 14.3008 15.9102L14.3308 15.9402Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M15.3398 8.66L20.9998 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M16.7598 3H20.9998V7.24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </g>
            </svg>
          </div>
          <div>
            <p class="text-sm text-gray-500">Phone</p>
            <p class="text-base font-medium text-gray-900">+88 01405074838</p>
           </div>
          </div>
          <p class="text-gray-700 mb-8">Want to Sell Your Product on SwiftCart? <br/>
Email us at [your-email@example.com] to get started!

</p>
      </div>
    </div>

    <!-- Right Contact Form -->
    <div class="bg-[#2e5e5c] text-white p-8 rounded-2xl shadow-md w-full">
      <h3 class="text-2xl font-semibold mb-2">Send us a message</h3>
      <p class="text-sm text-gray-200 mb-6">Your email address will not be published. Required fields are marked</p>
      <form class="space-y-5" id="ContactForm" method="POST">
        <div>
          <label class="block mb-1 text-sm">Name</label>
          <input type="text" name="name" class="w-full px-4 py-2 rounded-md bg-[#3d6f6d] text-white focus:outline-none" />
        </div>
        <div>
          <label class="block mb-1 text-sm">Email Address</label>
          <input type="email" name="email" class="w-full px-4 py-2 rounded-md bg-[#3d6f6d] text-white focus:outline-none" />
        </div>
        <div>
          <label class="block mb-1 text-sm">Phone Number</label>
          <input type="text" name="mobile" class="w-full px-4 py-2 rounded-md bg-[#3d6f6d] text-white focus:outline-none" />
        </div>
        <div>
          <label class="block mb-1 text-sm">Messages</label>
          <textarea rows="4" name="message" class="w-full px-4 py-2 rounded-md bg-[#3d6f6d] text-white focus:outline-none"></textarea>
        </div>
        <button type="submit" class="bg-yellow-500 cursor-pointer hover:bg-yellow-600 text-white font-medium px-6 py-2 rounded-md">Submit</button>
      </form>
    </div>
  </div>
</section>
'

?>