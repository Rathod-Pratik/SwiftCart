<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile</title>
  <?php include __DIR__ . '/../../Componenets/Header.php'; ?>
</head>

<body>
  <?php require __DIR__ . '/../../Componenets/VenderNavbar.php' ?>
  <?php require __DIR__ . '/../../Componenets/VenderSideBar.php' ?>
  <div class="lg:ml-64 pt-14 bg-gray-100 min-h-[100vh]">
    <div class="h-40  bg-gradient-to-r from-[#c9e4f9] via-[#f0e7e0] to-[#fef6e4]"></div>
    <div class="bg-white p-8 rounded-b-2xl shadow-xl -mt-8">
      <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
          <div class="relative w-20 h-20">
            <img src="invalid-url.jpg" alt="Profile Image"
              class="w-20 h-20  rounded-full object-cover border-4 border-white"
              id="profileImagePreview"
              onerror="showInitialFallback(this, 'R')" />

            <div id="profileFallback"
              class=" absolute inset-0 flex items-center justify-center bg-[#4fd1c5] text-white text-xl font-semibold rounded-full border-4 border-white">
              R
            </div>
          </div>

          <div>
            <h2 class="text-xl font-semibold" id="name"></h2>
            <p class="text-gray-500" id="email"></p>
          </div>
        </div>
        <input type="file" accept="image/*" class="hidden" id="profileImageInput" onchange="previewImage(event)">
        <button onclick="document.getElementById('profileImageInput').click()"
          class="bg-[#4fd1c5] text-white px-4 py-2 rounded-md">Change Photo</button>
      </div>

      <form class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- <div>
          <label class="block text-sm font-medium text-gray-700">Full Name</label>
          <input type="text" placeholder="Enter full name" name="name" id="name"
            disabled
            class="mt-1 block w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-[#4fd1c5] focus:border-[#4fd1c5]">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Email</label>
          <input type="email" placeholder="Enter email" id="email" name="email"
            disabled
            class="mt-1 block w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-[#4fd1c5] focus:border-[#4fd1c5]">
        </div> -->
        <!-- <div>
          <label class="block text-sm font-medium text-gray-700">Password</label>
          <input type="text" placeholder="Enter new password" id="password" name="password"
            disabled
            class="mt-1 block w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-[#4fd1c5] focus:border-[#4fd1c5]">
        </div> -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Address</label>
          <input type="text" placeholder="Enter address" id="address" name="address"
            disabled
            class="mt-1 block w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-[#4fd1c5] focus:border-[#4fd1c5]">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Mobile</label>
          <input type="text" placeholder="Enter address" name="mobile" id="mobile"
            disabled
            class="mt-1 block w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-[#4fd1c5] focus:border-[#4fd1c5]">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">IFSC Code</label>
          <input type="text" placeholder="Enter IFSC code" name="ifsc_code" id="ifsc_code"
            disabled
            class="mt-1 block w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-[#4fd1c5] focus:border-[#4fd1c5]">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Account No</label>
          <input type="text" placeholder="Enter IFSC code" id="account_no" name="account_no"
            disabled
            class="mt-1 block w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-[#4fd1c5] focus:border-[#4fd1c5]">
        </div>
        <!-- Company Name -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Company Name</label>
          <input type="text" placeholder="Enter company name" name="companyName" id="companyName"
            disabled
            class="mt-1 block w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-[#4fd1c5] focus:border-[#4fd1c5]">
        </div>
        <!-- Save Button -->
        <!-- <div class="col-span-1 md:col-span-2 flex justify-end mt-4">
          <button type="submit"
            class="bg-[#4fd1c5] text-white px-6 py-2 rounded-lg hover:bg-white hover:text-[#4fd1c5] hover:border-[#4fd1c5] border transition">
            Save Changes
          </button>
        </div> -->
      </form>
    </div>

    <script>
      function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
          const preview = document.getElementById('profileImagePreview');
          preview.src = URL.createObjectURL(file);
        }
      }
    </script>
  </div>

  <script>
    async function previewImage(event) {
      const file = event.target.files[0];
      if (file) {
        const preview = document.getElementById('profileImagePreview');
        preview.src = URL.createObjectURL(file);

        const formData = new FormData();

        const uploadFormData = new FormData();
        uploadFormData.append('action', 'upload');
        uploadFormData.append('image', file);

        await fetch('/SwiftCart/AJAX/Vender_Product_ajax.php', {
            method: 'POST',
            body: uploadFormData
          })
          .then(res => res.json())
          .then(res => {
            formData.set('photo', res.data.secure_url)
          })

        formData.append('action', 'updateProfile');

        await fetch('/SwiftCart/AJAX/Profile_ajax.php', {
          method: "POST",
          body: formData
        }).then(res => res.json()).then((res) => {
          preview.src = res.data.photo
          document.getElementById('venderPhoto').src=res.data.photo
        })
      }
    }

    function fetchDetail() {
      const formData = new FormData();
      formData.append('action', 'fetch');
      fetch('/SwiftCart/AJAX/Profile_ajax.php', {
        method: 'POST',
        body: formData
      }).then(res => res.json()).then((res) => {
        document.getElementById('name').textContent = res.data.name;
        document.getElementById('email').textContent = res.data.email;
        document.getElementById('address').value = res.data.address;
        document.getElementById('mobile').value = res.data.mobile;
        document.getElementById('ifsc_code').value = res.data.ifsc_code;
        document.getElementById('account_no').value = res.data.account_no;
        document.getElementById('companyName').value = res.data.company_name;

        const preview = document.getElementById('profileImagePreview');
        if (res.data.photo) {
          preview.src = res.data.photo;
          // 
          document.getElementById('profileFallback').classList.add('hidden')
        } else {
          preview.classList.add('hidden')
          document.getElementById('profileFallback').innerHTML = res.data.name.charAt(0).toUpperCase();
        }
      })

    }
    document.addEventListener('DOMContentLoaded', function() {
      fetchDetail()
    });
  </script>


</body>

</html>