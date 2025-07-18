<?php

require '../../Database/db.php';
require '../../Database/Function.php';

$table = 'product';

$createSQL = " CREATE TABLE IF NOT EXISTS product (
    id SERIAL PRIMARY KEY,
    image TEXT,
    venderid INTEGER REFERENCES vender(id),
    product_name VARCHAR(50),
    price NUMERIC(10, 2),
    highlight VARCHAR(150),
    stock FLOAT,
    discount NUMERIC(5,2),
    category TEXT,
    information JSONB,
    description TEXT[],
    product_state TEXT DEFAULT 'requested' CHECK (product_state IN ('requested', 'approved', 'rejected')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
";
checkAndCreateTable($pdo, $table, $createSQL);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vender | Product</title>
  <?php include '../../Componenets/Header.php'; ?>
</head>

<body>
  <?php require '../../Componenets/VenderNavbar.php' ?>
  <?php require '../../Componenets/VenderSideBar.php' ?>

  <div class="p-4 lg:ml-64 pt-20 bg-gray-100 min-h-[100vh]">
    <div class="flex justify-evenly gap-3 mb-6">
      <input
        class="border-[#4fd1c5] bg-white border-2 outline-none rounded-md px-4 py-2 w-[90%]"
        type="text"
        placeholder="Search Product" />
      <button class="text-white bg-[#4fd1c5] px-5 cursor-pointer py-2 rounded-md" onclick="openCreateModal()">
        New
      </button>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
      <table class="w-full text-sm text-left rtl:text-right text-gray-500">
        <thead class="text-xs text-[#a0aec0] uppercase bg-gray-50 ">
          <tr>
            <th scope="col" class="px-6 py-3">
              Id
            </th>
            <th scope="col" class="px-6 py-3">
              Name
            </th>
            <th scope="col" class="px-6 py-3">
              Category
            </th>
            <th scope="col" class="px-6 py-3">
              Price
            </th>
            <th scope="col" class="px-6 py-3">
              stock
            </th>
            <th scope="col" class="px-6 py-3">
              Discount
            </th>
            <th scope="col" class="px-6 py-3">
            </th>
          </tr>
        </thead>
        <tbody>
          <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
            <td class="px-6 py-4">1</td>
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Apple MacBook Pro 17</th>
            <td class="px-6 py-4">Electronics</td>
            <td class="px-6 py-4">2000</td>
            <td class="px-6 py-4">50</td>
            <td class="px-6 py-4">10%</td>
            <td class="px-6 py-4 text-center">
              delete
            </td>
          </tr>

        </tbody>
      </table>
    </div>

    <div
      id="myModal"
      tabindex="-1"
      aria-hidden="true"
      class="backdrop-blur-sm flex overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
      <div class="relative p-4 w-full max-w-4xl max-h-full">
        <div class="relative bg-white rounded-2xl shadow-lg">
          <!-- Header -->
          <div class="flex items-center justify-between p-4 md:p-5 rounded-t bgcolor">
            <h3 class="text-lg font-semibold text-white" id="modalTitle">Add Product Request</h3>
            <button onclick="closeModal()" type="button" class="text-white hover:text-[#4fd1c5] cursor-pointer hover:bg-white bg-transparent rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center transition-colors duration-200">
              <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
              </svg>
            </button>
          </div>

          <form class="p-4 md:p-5" method="post" id="productForm" enctype="multipart/form-data">
            <input type="hidden" name="action" id="action" value="create" />
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="flex flex-col items-center gap-4">
                <label for="imageInput" class="w-full h-64 border-2 border-dashed border-[#4fd1c5] flex justify-center items-center overflow-hidden rounded-lg cursor-pointer">
                  <img id="imagePreview" src="" alt="Preview" class="max-h-full object-contain hidden" />
                  <span id="imagePlaceholder" class="text-gray-400">Select image</span>
                </label>
                <input type="file" name="image" id="imageInput" accept="image/*" class="hidden" required />
                <button type="button"
                  id="deleteImageBtn"
                  class="bg-red-500 text-white px-4 py-1 text-sm rounded hidden cursor-pointer">
                  Delete Image
                </button>
              </div>


              <div class="grid gap-4">
                <div>
                  <label class="textcolor text-sm font-medium block mb-1">Product Name</label>
                  <input required type="text" name="product_name" class="inputcolor border border-[#4fd1c5] text-sm rounded-lg block w-full p-2.5" placeholder="Product Name" />
                </div>

                <div class="flex gap-4">
                  <div class="w-1/2">
                    <label class="textcolor text-sm font-medium block mb-1">Price</label>
                    <input required type="number" name="price" step="0.01" class="inputcolor border border-[#4fd1c5] text-sm rounded-lg block w-full p-2.5" />
                  </div>
                  <div class="w-1/2">
                    <label class="textcolor text-sm font-medium block mb-1">Stock</label>
                    <input required type="number" name="stock" step="0.01" class="inputcolor border border-[#4fd1c5] text-sm rounded-lg block w-full p-2.5" />
                  </div>
                </div>

                <div class="flex gap-4">
                  <div class="w-1/2">
                    <label class="textcolor text-sm font-medium block mb-1">Discount (%)</label>
                    <input required type="number" name="discount" step="0.01" class="inputcolor border border-[#4fd1c5] text-sm rounded-lg block w-full p-2.5" />
                  </div>
                  <div class="w-1/2">
                    <label class="textcolor text-sm font-medium block mb-1">Category</label>
                    <select required name="category" class="inputcolor border border-[#4fd1c5] text-sm rounded-lg block w-full p-2.5">
                      <option value="" disabled selected>Select category</option>
                      <?php
                      $categories = $pdo->query("SELECT * FROM category ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
                      foreach ($categories as $category) {
                        echo '<option value="' . htmlspecialchars($category['id']) . '">' . htmlspecialchars($category['name']) . '</option>';
                      }
                      ?>
                    </select>
                  </div>
                </div>

                <div>
                  <label class="textcolor text-sm font-medium block mb-1">Highlight</label>
                  <input required type="text" name="highlight" class="inputcolor border border-[#4fd1c5] text-sm rounded-lg block w-full p-2.5" />
                </div>

                <div id="infoFields" class="space-y-4">
                  <div class="grid grid-cols-2 gap-4 items-end group">
                    <div>
                      <label class="textcolor text-sm font-medium block mb-1">Information</label>
                      <input type="text" name="info_key[]" class="inputcolor border border-[#4fd1c5] text-sm rounded-lg block w-full p-2.5" />
                    </div>
                    <div class="flex items-center gap-2">
                      <div class="w-full">
                        <label class="textcolor text-sm font-medium block mb-1">Value</label>
                        <input type="text" name="info_value[]" class="inputcolor border border-[#4fd1c5] text-sm rounded-lg block w-full p-2.5" />
                      </div>
                      <!-- Remove Button -->
                      <button type="button" class="removeRow bg-red-500 text-white px-3 py-1 rounded-lg text-sm h-10 mt-6">✕</button>
                    </div>
                  </div>
                </div>

                <!-- Add Button -->
                <button type="button" id="addInfoBtn" class="mt-3 bg-[#4fd1c5] text-white px-4 py-2 rounded-lg text-sm">
                  + Add Info
                </button>

                <div>
                  <label class="textcolor text-sm font-medium block mb-1">Description</label>
                  <textarea name="description" rows="3" class="inputcolor border border-[#4fd1c5] text-sm rounded-lg block w-full p-2.5"></textarea>
                </div>
              </div>
            </div>

            <div class="flex justify-end mt-6">
              <button type="submit" id="modalSubmitBtn" class="text-white flex items-center gap-2 bg-[#4fd1c5] hover:border hover:border-[#4fd1c5] hover:text-[#4fd1c5] hover:bg-white font-medium rounded-lg text-sm px-5 py-2.5 transition-colors duration-200">
                <div id="spinner" class="hidden w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
                <span id="modalBtnText">Create</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <script>
      let file;
      const imageInput = document.getElementById('imageInput');
      const imagePreview = document.getElementById('imagePreview');
      const imagePlaceholder = document.getElementById('imagePlaceholder');
      const deleteImageBtn = document.getElementById('deleteImageBtn');

      imageInput.addEventListener('change', function() {
        file = this.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
            imagePreview.src = e.target.result;
            imagePreview.classList.remove('hidden');
            imagePlaceholder.classList.add('hidden');
            deleteImageBtn.classList.remove('hidden');
          };
          reader.readAsDataURL(file);
        }
      });

      deleteImageBtn.addEventListener('click', function(e) {
        e.preventDefault();
        imageInput.value = '';
        imagePreview.src = '';
        imagePreview.classList.add('hidden');
        imagePlaceholder.classList.remove('hidden');
        deleteImageBtn.classList.add('hidden');
      });

      const infoFields = document.getElementById('infoFields');
      const addInfoBtn = document.getElementById('addInfoBtn');

      addInfoBtn.addEventListener('click', () => {
        const newRow = document.createElement('div');
        newRow.className = 'grid grid-cols-2 gap-4 items-end group';
        newRow.innerHTML = `
                           <div>
                             <input type="text" name="info_key[]" class="inputcolor border border-[#4fd1c5] text-sm rounded-lg block w-full p-2.5" />
                           </div>
                           <div class="flex items-center gap-2">
                             <div class="w-full">
                               <input type="text" name="info_value[]" class="inputcolor border border-[#4fd1c5] text-sm rounded-lg block w-full p-2.5" />
                             </div>
                             <button type="button" class="removeRow bg-red-500 text-white px-3 py-1 rounded-lg text-sm h-10">✕</button>
                           </div>
                          `;

        infoFields.appendChild(newRow);
      });
      infoFields.addEventListener('click', function(e) {
        if (e.target.classList.contains('removeRow')) {
          e.target.closest('.grid').remove();
        }
      });

      document.getElementById('productForm').addEventListener('submit', function(e) {
        e.preventDefault();
        // document.getElementById('spinner').classList.remove('hidden');
        // document.getElementById('modalBtnText').textContent = "Sending..."
        // document.getElementById('modalSubmitBtn').disabled = true
        const action = document.getElementById('action').value;
        if (action == 'create') {
          const form = e.target;
          const formData = new FormData(form);

          if (file) {
            const uploadFormData = new FormData();
            uploadFormData.append('action', 'upload');
            uploadFormData.append('image', file);

            fetch('/SwiftCart/AJAX/Vender_Product_ajax.php', {
                method: 'POST',
                body: uploadFormData
              })
              .then(res => res.json())
              .then(res => {
                console.log("Image upload successfilly")
                formData.set('image', res.data.secure_url)

                const description = formData.get('description');
                const paragraphs = description.split(/\n\s*\n/);

                formData.set('description', JSON.stringify(paragraphs));
                console.log(JSON.stringify(paragraphs))

                console.log("All work completed")
                for (const [key, value] of formData.entries()) {
                  console.log(`value of  ${key} is : ${value}`);
                }
                fetch('/SwiftCart/AJAX/Vender_Product_ajax.php', {
                    method: "POST",
                    body: formData
                  })
                  // .then(res => res.json())
                  .then(res => res.text())
                  .then((res) => {
                    console.log(res)
                    if (res.success) {
                      showToast("Add new Product Request send successfully", "success")
                    }
                  })
              })
              .catch(error => {
                console.error("Upload Failed:", error);
              });
          }

        } else if (action == 'update') {
          if (file) {
            const uploadFormData = new FormData();
            uploadFormData.append('action', 'upload');
            uploadFormData.append('image', file);

            fetch('/SwiftCart/AJAX/Vender_Product_ajax.php', {
                method: 'POST',
                body: uploadFormData
              })
              .then(res => res.text())
              .then(response => {
                console.log("Upload Response:", response);
                // You can parse JSON if your backend returns it
                // let data = JSON.parse(response);
                // console.log(data.secure_url);

                const keys = formData.getAll('info_key[]');
                const values = formData.getAll('info_value[]');

                const infoObj = {};
                keys.forEach((key, i) => {
                  infoObj[key] = values[i];
                });
                formData.append('information', JSON.stringify(infoObj));
                uploadFormData.append('action', 'create');
                fetch('/SwiftCart/AJAX/Vender_Product_ajax.php', {
                  method: "POST",
                  body: formData
                }).then(res => res.text()).then((text) => {
                  console.error("Invalid JSON Response:", text);
                  const res = JSON.parse(text);
                  console.log(res)
                  if (res.success) {
                    showToast("Product Update Request send successfully", "success")
                    document.getElementById('spinner').classList.add('hidden');
                    document.getElementById('modalBtnText').textContent = "create"
                    document.getElementById('modalSubmitBtn').disabled = false

                  }
                })
              })
              .catch(error => {
                console.error("Upload Failed:", error);
              });
          }
        }



      })
    </script>

</body>

</html>