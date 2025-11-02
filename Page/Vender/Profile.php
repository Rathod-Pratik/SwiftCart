<?php   require __DIR__ . '/../../Componenets/Vender/VenderAuth.php'  ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SwiftCart | Profile Section</title>
   <?php include __DIR__ . '/../../Componenets/Other/Header.php'; ?>
</head>

<body>
<?php require __DIR__ . '/../../Componenets/Vender/VenderNavbar.php' ?>
  <?php require __DIR__ . '/../../Componenets/Vender/VenderSideBar.php' ?>
  <div class="lg:ml-64 pt-14 bg-gray-100 min-h-[100vh]">
    <div class="h-40 bg-gradient-to-r from-[#c9e4f9] via-[#f0e7e0] to-[#fef6e4]"></div>
    <div class="bg-white p-8 rounded-b-2xl -mt-8">
      <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
          <div class="relative w-20 h-20">
            <img alt="Profile Image"
              class="w-20 h-20  rounded-full object-cover border-4 border-white"
              id="profileImagePreview" />

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
        <button id="changeBtn" onclick="document.getElementById('profileImageInput').click()" class="bg-[#4fd1c5] cursor-pointer text-white px-4 py-2 rounded-md flex items-center gap-2">
          <span id="btnText">Change</span>
          <div id="spinner" class="hidden w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
        </button>
      </div>

      <form class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700">UserType</label>
          <input type="text" id="usertype" name="usertype"
            disabled
            class="mt-1 block w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-[#4fd1c5] focus:border-[#4fd1c5]">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Created At</label>
          <input type="text" id="createat" name="createat"
            disabled
            class="mt-1 block w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-[#4fd1c5] focus:border-[#4fd1c5]">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Address</label>
          <input type="text" id="address" name="address"
            disabled
            class="mt-1 block w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-[#4fd1c5] focus:border-[#4fd1c5]">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Mobile</label>
          <input type="text" name="mobile" id="mobile"
            disabled
            class="mt-1 block w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-[#4fd1c5] focus:border-[#4fd1c5]">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">IFSC Code</label>
          <input type="text" name="ifsc_code" id="ifsc_code"
            disabled
            class="mt-1 block w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-[#4fd1c5] focus:border-[#4fd1c5]">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Account No</label>
          <input type="text" id="account_no" name="account_no"
            disabled
            class="mt-1 block w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-[#4fd1c5] focus:border-[#4fd1c5]">
        </div>
        <!-- Company Name -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Company Name</label>
          <input type="text" name="companyName" id="companyName"
            disabled
            class="mt-1 block w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-[#4fd1c5] focus:border-[#4fd1c5]">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Status</label>
          <input type="text" name="status" id="status"
            disabled
            class="mt-1 block w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-[#4fd1c5] focus:border-[#4fd1c5]">
        </div>
      </form>
    </div>
  </div>

  <script>
    async function previewImage(event) {
      const file = event.target.files[0];
      if (file) {
        document.getElementById('spinner').classList.remove('hidden');
        document.getElementById('btnText').textContent = "Uploading...";
        const preview = document.getElementById('profileImagePreview');

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
          document.getElementById('spinner').classList.add('hidden');
          document.getElementById('btnText').textContent = "Change";
          preview.src = res.photo
          document.getElementById('venderPhoto').src = res.photo
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
        document.getElementById('usertype').value = res.data.usertype;

        // Step 1: Remove microseconds (browser-safe)
        const cleanedTimestamp = res.data.created_at.split('.')[0]; // "2025-07-21 03:57:11"

        // Step 2: Create Date object
        const date = new Date(cleanedTimestamp);

        // Step 3: Format parts
        const day = date.getDate().toString().padStart(2, '0');
        const month = date.toLocaleString('default', {
          month: 'short'
        }); // "Jul"
        const hour12 = (date.getHours() % 12 || 12).toString().padStart(2, '0'); // 12-hour format
        const minutes = date.getMinutes().toString().padStart(2, '0');
        const ampm = date.getHours() >= 12 ? 'PM' : 'AM';

        // Step 4: Final string
        const formatted = `${day} ${month} ${hour12}:${minutes} ${ampm}`;

        document.getElementById('createat').value = formatted;
        document.getElementById('status').value = res.data.status;


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