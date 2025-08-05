<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SwiftCart | About</title>
  <?php include __DIR__ . '/../../Componenets/Header.php'; ?>
</head>

<body class="bg-gray-50 font-sans leading-relaxed">
  <?php require __DIR__ . '/../../Componenets/Navbar.php'; ?>

  <!-- Hero Section -->
  <section class="max-w-6xl mx-auto px-6 py-16">
    <div class="flex flex-col md:flex-row items-center gap-10">
      <!-- Content -->
      <div data-aos="fade-right" class="flex-1 space-y-6">
        <h1 class="text-4xl md:text-5xl font-bold text-[#234445]">About <span class="text-[#d09523]">SwiftCart</span></h1>
        <div class="flex md:hidden justify-center">
          <img src="/SwiftCart/Image/About1.jpg" alt="SwiftCart Logo"
            class="w-[250px] rounded-2xl shadow-xl bg-white p-4 object-contain" />
        </div>
        <p class="text-gray-700 text-lg">
          <span class="font-semibold text-[#d09523]">SwiftCart</span> is a modern, user-friendly e-commerce marketplace designed to connect buyers and vendors across India. Our mission is to make online shopping fast, secure, and enjoyable—whether you're looking for the latest electronics, trendy fashion, home essentials, or unique local finds.
        </p>
        <ul class="list-disc list-inside text-gray-700 text-base space-y-2">
          <li>
            <span class="font-semibold">For Customers:</span> Discover a wide range of products, enjoy exclusive deals, and experience seamless delivery and support.
          </li>
          <li>
            <span class="font-semibold">For Vendors:</span> Effortlessly list your products, manage orders, and grow your business with our powerful vendor dashboard.
          </li>
        </ul>
      </div>

      <!-- Image -->
      <div data-aos="fade-left" class="flex-1 flex justify-center">
        <img src="/SwiftCart/Image/About1.jpg" alt="SwiftCart Logo"
          class="hidden md:block  rounded-2xl shadow-xl bg-white p-4 object-contain" />
      </div>
    </div>
  </section>

  <!-- Why Choose SwiftCart -->
  <section class="bg-white py-14" data-aos="zoom-in">
    <div class="max-w-5xl mx-auto px-6">
      <h2 class="text-3xl font-bold text-[#234445] mb-6 text-center">Why Choose SwiftCart?</h2>
      <ul class="list-disc list-inside text-gray-700 text-base space-y-3 px-4 md:px-10">
        <li>Wide variety of products from trusted vendors</li>
        <li>Secure payments and fast delivery</li>
        <li>Easy-to-use interface for both buyers and sellers</li>
        <li>Responsive customer support</li>
        <li>Exclusive offers, flash sales, and cashback rewards</li>
      </ul>
    </div>
  </section>

  <!-- Team Section -->
  <section class="py-20  bg-[#e7f7f6]  relative overflow-hidden">
    <!-- Decorative Element Top Right -->
    <div class="absolute right-0 top-0 w-48 h-48 bg-[#bde6e4] opacity-30 rounded-full -z-10 blur-2xl"></div>
    <div class="max-w-6xl mx-auto px-6">
      <h2 class="text-4xl font-extrabold text-[#234445] text-center mb-14 " data-aos="fade-down">Meet Our Team</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10 justify-center">
        <!-- Member Card -->
        <div data-aos="fade-down" class="bg-white rounded-3xl shadow-2xl p-8 flex flex-col items-center text-center transition-transform hover:scale-105 hover:shadow-[#28afa2]/20 hover:border-[#28afa2] border border-transparent duration-300">
          <div class="relative w-32 h-32 mb-5">
            <div class="absolute -inset-1 bg-gradient-to-tr from-[#99ece1] to-[#fff] rounded-full blur-lg z-0"></div>
            <div class="relative w-full h-full rounded-full overflow-hidden shadow-lg border-4 border-white">
              <!-- <img src="/SwiftCart/Image/your-image1.jpg" class="object-cover w-full h-full" alt="Pratik Rathod" /> -->
              <span class="text-gray-400 flex items-center justify-center w-full h-full text-base bg-gray-100">Image</span>
            </div>
          </div>
          <h4 class="text-xl font-semibold text-[#234445] mb-1">Pratik Rathod</h4>
          <p class="text-sm text-[#28afa2] mb-2 font-medium uppercase tracking-wide">Founder & Full Stack Developer</p>
          <p class="text-xs text-gray-500 max-w-xs">Passionate about building seamless e-commerce experiences and empowering vendors to grow online.</p>
        </div>
        <!-- Member Card 2 -->
        <div data-aos="fade-down" class="bg-white rounded-3xl shadow-2xl p-8 flex flex-col items-center text-center transition-transform hover:scale-105 hover:shadow-[#28afa2]/20 hover:border-[#28afa2] border border-transparent duration-300">
          <div class="relative w-32 h-32 mb-5">
            <div class="absolute -inset-1 bg-gradient-to-tr from-[#99ece1] to-[#fff] rounded-full blur-lg z-0"></div>
            <div class="relative w-full h-full rounded-full overflow-hidden shadow-lg border-4 border-white">
              <!-- <img src="/SwiftCart/Image/your-image2.jpg" class="object-cover w-full h-full" alt="Your Name" /> -->
              <span class="text-gray-400 flex items-center justify-center w-full h-full text-base bg-gray-100">Image</span>
            </div>
          </div>
          <h4 class="text-xl font-semibold text-[#234445] mb-1">Your Name</h4>
          <p class="text-sm text-[#28afa2] mb-2 font-medium uppercase tracking-wide">Co-Founder / Designer</p>
          <p class="text-xs text-gray-500 max-w-xs">Add your team bio here. Share your vision and role in making SwiftCart a success.</p>
        </div>
      </div>
    </div>
    <!-- Decorative Element Bottom Left -->
    <div class="absolute left-0 bottom-0 w-40 h-40 bg-[#99ece1] opacity-40 rounded-full -z-10 blur-2xl"></div>
  </section>


  <!-- FAQ -->
  <?php require __DIR__ . '/../../Componenets/Home/FAQ.php'; ?>

  <!-- CTA Section -->
  <section class="py-16 text-center px-6" data-aos="zoom-in">
    <h3 class="text-2xl font-semibold text-[#234445] mb-3">Join the SwiftCart Community</h3>
    <p class="text-gray-700 text-base max-w-2xl mx-auto">
      Whether you’re a shopper searching for the best deals or a vendor ready to grow your business, SwiftCart is your trusted partner in online commerce. Start exploring today and experience the future of shopping!
    </p>
  </section>

  <?php include __DIR__ . '/../../Componenets/Footer.php'; ?>
</body>

</html>