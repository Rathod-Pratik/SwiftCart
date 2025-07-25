<?php

require __DIR__ . '/../../Database/db.php';
require __DIR__ . '/../../Database/Function.php';

$table = 'product';

$createSQL = " CREATE TABLE IF NOT EXISTS product (
    id SERIAL PRIMARY KEY,
    image TEXT,
    venderid INTEGER REFERENCES users(id),
    product_name VARCHAR(50),
    price NUMERIC(10, 2),
    highlight VARCHAR(150),
    stock FLOAT,
    discount NUMERIC(5,2),
    category TEXT,
    information JSONB,
    description JSONB,
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
  <?php include __DIR__ . '/../../Componenets/Header.php'; ?>
</head>

<body>
  <?php require __DIR__ . '/../../Componenets/VenderNavbar.php' ?>
  <?php require __DIR__ . '/../../Componenets/VenderSideBar.php' ?>

  <div class="p-4 lg:ml-64 pt-20 bg-gray-100 min-h-[100vh]">
    <div class="flex justify-evenly gap-3 mb-6">
      <input
        class="border-[#4fd1c5] bg-white border-2 outline-none rounded-md px-4 py-2 w-[90%]"
        type="text"
        oninput="handleSearch(this.value)"
        placeholder="Search Product" />
      <button class="text-white bg-[#4fd1c5] px-5 cursor-pointer py-2 rounded-md" onclick="openModal()">
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
              Status
            </th>
            <th scope="col" class="px-6 py-3">
            </th>
          </tr>
        </thead>
        <tbody id="Product-table-body">
        </tbody>
      </table>
    </div>

    <div
      id="myModal"
      tabindex="-1"
      aria-hidden="true"
      class="backdrop-blur-sm hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full transform scale-95 opacity-0 translate-y-[-20px] transition-all duration-300 ease-out">
      <div class="relative p-4 w-full max-w-4xl max-h-full ">
        <div class="relative bg-white rounded-2xl shadow-lg">
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
            <input type="hidden" name="id" id="id" />
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="flex flex-col items-center gap-4">
                <label for="imageInput" class="w-full h-64 border-2 border-dashed border-[#4fd1c5] flex justify-center items-center overflow-hidden rounded-lg cursor-pointer">
                  <img id="imagePreview" src="" alt="Preview" class="max-h-full object-contain hidden" />
                  <span id="imagePlaceholder" class="text-gray-400">Select image</span>
                </label>
                <input type="file" name="image" id="imageInput" accept="image/*" class="hidden" />
                <button type="button"
                  id="deleteImageBtn"
                  class="bg-red-500 text-white px-4 py-1 text-sm rounded hidden cursor-pointer">
                  Delete Image
                </button>
              </div>


              <div class="grid gap-4">
                <div>
                  <label class="textcolor text-sm font-medium block mb-1">Product Name</label>
                  <input required type="text" name="product_name" id="product_name" class="inputcolor border border-[#4fd1c5] text-sm rounded-lg block w-full p-2.5" placeholder="Product Name" />
                </div>

                <div class="flex gap-4">
                  <div class="w-1/2">
                    <label class="textcolor text-sm font-medium block mb-1">Price</label>
                    <input required type="number" name="price" id="price" step="0.01" class="inputcolor border border-[#4fd1c5] text-sm rounded-lg block w-full p-2.5" />
                  </div>
                  <div class="w-1/2">
                    <label class="textcolor text-sm font-medium block mb-1">Stock</label>
                    <input required type="number" name="stock" id="stock" step="0.01" class="inputcolor border border-[#4fd1c5] text-sm rounded-lg block w-full p-2.5" />
                  </div>
                </div>

                <div class="flex gap-4">
                  <div class="w-1/2">
                    <label class="textcolor text-sm font-medium block mb-1">Discount (%)</label>
                    <input required type="number" name="discount" id="discount" step="0.01" class="inputcolor border border-[#4fd1c5] text-sm rounded-lg block w-full p-2.5" />
                  </div>
                  <div class="w-1/2">
                    <label class="textcolor text-sm font-medium block mb-1">Category</label>
                    <select required name="category" id="category" class="inputcolor border border-[#4fd1c5] text-sm rounded-lg block w-full p-2.5">
                      <option value="" disabled selected>Select category</option>
                      <?php
                      $categories = $pdo->query("SELECT * FROM category ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
                      foreach ($categories as $category) {
                        echo '<option value="' . htmlspecialchars($category['name']) . '">' . htmlspecialchars($category['name']) . '</option>';
                      }
                      ?>
                    </select>
                  </div>
                </div>

                <div>
                  <label class="textcolor text-sm font-medium block mb-1">Highlight</label>
                  <input required type="text" name="highlight" id="highlight" class="inputcolor border border-[#4fd1c5] text-sm rounded-lg block w-full p-2.5" />
                </div>

                <div id="infoFields" class="space-y-4">
                  <div class="grid grid-cols-2 gap-4 items-end group">
                    <div>
                      <label class="textcolor text-sm font-medium block mb-1">Information</label>
                      <input type="text" name="info_key" class="inputcolor border border-[#4fd1c5] text-sm rounded-lg block w-full p-2.5" />
                    </div>
                    <div class="flex items-center gap-2">
                      <div class="w-full">
                        <label class="textcolor text-sm font-medium block mb-1">Value</label>
                        <input type="text" name="info_value" class="inputcolor border border-[#4fd1c5] text-sm rounded-lg block w-full p-2.5" />
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
                  <textarea name="description" rows="3" id="description" class="inputcolor border border-[#4fd1c5] text-sm rounded-lg block w-full p-2.5"></textarea>
                </div>
              </div>
            </div>

            <div class="flex justify-end mt-6">
              <button type="submit" id="modalSubmitBtn" class="text-white flex items-center gap-2 bg-[#4fd1c5] hover:border hover:border-[#4fd1c5] hover:text-[#4fd1c5] hover:bg-white font-medium rounded-lg text-sm px-5 py-2.5 transition-colors duration-200 cursor-pointer">
                <div id="spinner" class="hidden w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
                <span id="modalBtnText">Create</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div
      id="DeleteProductModal"
      tabindex="-1"
      class="backdrop-blur-sm hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full transform scale-95 opacity-0 translate-y-[-20px] transition-all duration-300 ease-out">
      <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm">
          <button
            onclick="CloseDeleteModal()"
            type="button"
            class="absolute top-3 end-2.5 text-gray-400 cursor-pointer bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
            data-modal-hide="popup-modal">
            <svg
              class="w-3 h-3"
              aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 14 14">
              <path
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
          <div class="p-4 md:p-5 text-center">
            <svg
              class="mx-auto mb-4 text-gray-400 w-12 h-12"
              aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 20 20">
              <path
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <h3
              id="ConfirmText"
              class="mb-5 text-lg font-normal text-gray-500"></h3>
            <button
              id="deleteBtn"
              data-modal-hide="popup-modal"
              type="button"
              class="text-white cursor-pointer bg-red-600 hover:bg-red-800 outline-none gap-2 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
              <div id="modalSpinner" class="hidden w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
              <span id="btnText">Yes, I'm sure</span>
            </button>

            <button
              onclick="CloseDeleteModal()"
              data-modal-hide="popup-modal"
              type="button"
              class="py-2.5 px-5 ms-3 cursor-pointer text-sm font-medium text-gray-900 outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10">
              No, cancel
            </button>
          </div>
        </div>
      </div>
    </div>

    <script>
      AllProduct = [];

      function handleSearch(searchText) {
        const query = searchText.toLowerCase().trim();

        const filtered = AllProduct.filter(cat =>
          cat.product_name.toLowerCase().includes(query)
        );

        UpdateProductTableUI(filtered);
      }

      function OpenDeleteModal(id, venderid, product_name) {
        const modal = document.getElementById('DeleteProductModal')
        modal.classList.remove('hidden')
        modal.classList.add('flex')

        document.getElementById('ConfirmText').innerHTML = `Are You sure You Want to Delete ${product_name} Product`

        const deletebutton = document.getElementById('deleteBtn')
        deletebutton.onclick = () => DeleteProduct(id, venderid);

        setTimeout(() => {
          modal.classList.remove("scale-95", "opacity-0", "translate-y-[-20px]");
          modal.classList.add("scale-100", "opacity-100", "translate-y-0");
        }, 10);
      }

      function CloseDeleteModal() {
        const modal = document.getElementById('DeleteProductModal')
        modal.classList.remove("scale-100", "opacity-100", "translate-y-0");
        modal.classList.add("scale-95", "opacity-0", "translate-y-[-20px]");
        const deletebutton = document.getElementById('deleteBtn')
        deletebutton.onclick = DeleteProduct()
        setTimeout(() => {
          modal.classList.remove("flex");
          modal.classList.add("hidden");
        }, 300);
      }

      function FetchProduct() {
        fetch('/SwiftCart/AJAX/Vender_Product_ajax.php', {
          method: "POST",
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: 'action=fetch'
        }).then(res => res.json()).then(data => {
          if (data.success) {
            AllProduct=data.product
            UpdateProductTableUI(data.product)
          }
        })
      }

      function UpdateProductTableUI(products) {
        const tbody = document.getElementById('Product-table-body');
        tbody.innerHTML = '';

        if (!Array.isArray(products) || products.length < 1) {
          const row = document.createElement('tr');
          row.id = 'EmptyProductTable'
          row.classList.add('bg-white', 'border-b', 'border-gray-200', 'hover:bg-gray-50');
          row.innerHTML = `
                    <td colspan="8" class="text-center py-4 text-gray-500">
                        No Products found.
                    </td>
                `;
          tbody.appendChild(row);
          return;
        }

        products.forEach(product => {
          const tr = document.createElement('tr');
          tr.classList.add('bg-white', 'border-b', 'border-gray-200', 'hover:bg-gray-50');
          const productJSON = encodeURIComponent(JSON.stringify(product));
          tr.id = `product-${product.id}`
          tr.innerHTML = `
                    <td class="px-6 py-4">${product.id}</td>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">${product.product_name}</th>
                    <td class="px-6 py-4">${product.category}</td>
                    <td class="px-6 py-4">${product.price}</td>
                    <td class="px-6 py-4">${product.stock ==0 ? 'out of stock':product.stock}</td>
                    <td class="px-6 py-4">${product.discount}%</td>
                    <td class="px-6 py-4">${product.product_state}</td>
                     <td class="flex gap-2 py-2">
                                    <button onclick="OpenEditModal(this)" data-product="${productJSON}" class="cursor-pointer text-white border-transparent bg-[#4fd1c5] border hover:border-[#4fd1c5] hover:text-[#4fd1c5] font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors duration-200 hover:bg-white flex items-center justify-center" title="Add">
                                        Update
                                    </button>
                                    <button onclick="OpenDeleteModal(${product.id},${product.venderid},'${product.product_name}')" class="cursor-pointer text-white flex items-center justify-center bg-[#4fd1c5] border border-transparent hover:border-[#4fd1c5] hover:text-[#4fd1c5] hover:bg-white font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors duration-200" title="Delete">
                                        Delete
                                    </button>
                                </td>
                `;
          tbody.appendChild(tr);
        });
      }
      document.addEventListener('DOMContentLoaded', function() {
        FetchProduct()
      });

      function openModal() {
        document.getElementById('myModal').classList.remove('hidden')
        document.getElementById('myModal').classList.add('flex')
        setTimeout(() => {
          document.getElementById('myModal').classList.remove("scale-95", "opacity-0", "translate-y-[-20px]");
          document.getElementById('myModal').classList.add("scale-100", "opacity-100", "translate-y-0");
        }, 10);
      }

      function closeModal() {
        document.getElementById('productForm').reset();
        const imagePreview = document.getElementById('imagePreview').src = null
        const modal = document.getElementById('myModal');

        modal.classList.remove("scale-100", "opacity-100", "translate-y-0");
        modal.classList.add("scale-95", "opacity-0", "translate-y-[-20px]");
        setTimeout(() => {
          modal.classList.remove("flex");
          modal.classList.add("hidden");
        }, 300);
      }


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

      document.getElementById('productForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const action = document.getElementById('action').value;
        if (action == 'create') {
          document.getElementById('spinner').classList.remove('hidden');
          document.getElementById('modalBtnText').textContent = "Sending..."
          document.getElementById('modalSubmitBtn').disabled = true
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
                formData.set('image', res.data.secure_url)

                const description = formData.get('description');

                const paragraphs = description
                  .split(/\r?\n/) // split on newline
                  .map(p => p.trim())
                  .filter(p => p.length > 0);

                formData.set('description', JSON.stringify(paragraphs));

                for (const [key, value] of formData.entries()) {
                  console.log(`value of  ${key} is : ${value}`);
                }
                fetch('/SwiftCart/AJAX/Vender_Product_ajax.php', {
                  method: "POST",
                  body: formData,
                  credentials: 'include'
                }).then(res => res.json()).then(res => {
                  if (res.success) {
                    showToast("Product Request Send successfully", "success")
                    document.getElementById('spinner').classList.add('hidden');
                    document.getElementById('modalBtnText').textContent = "Create"
                    document.getElementById('modalSubmitBtn').disabled = false

                    const tbody = document.getElementById('Product-table-body');
                    const tr = document.createElement('tr')
                    tr.classList.add('bg-white', 'border-b', 'border-gray-200', 'hover:bg-gray-50');
                    const productJSON = encodeURIComponent(JSON.stringify(res.data));
                    tr.id = `product-${res.data.id}`
                    tr.innerHTML = `
                    <td class="px-6 py-4">${res.data.id}</td>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">${res.data.product_name}</th>
                    <td class="px-6 py-4">${res.data.category}</td>
                    <td class="px-6 py-4">${res.data.price}</td>
                    <td class="px-6 py-4">${res.data.stock ==0 ? 'out of stock':res.data.stock}</td>
                    <td class="px-6 py-4">${res.data.discount}%</td>
                    <td class="px-6 py-4">${res.data.product_state}</td>
                     <td class="flex gap-2 py-2">
                                    <button onclick="OpenEditModal(this)" data-product="${productJSON}" class="cursor-pointer text-white border-transparent bg-[#4fd1c5] border hover:border-[#4fd1c5] hover:text-[#4fd1c5] font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors duration-200 hover:bg-white flex items-center justify-center" title="Add">
                                        Update
                                    </button>
                                    <button onclick="OpenDeleteModal(${res.data.id},${res.data.venderid},'${res.data.product_name}')" class="cursor-pointer text-white flex items-center justify-center bg-[#4fd1c5] border border-transparent hover:border-[#4fd1c5] hover:text-[#4fd1c5] hover:bg-white font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors duration-200" title="Delete">
                                        Delete
                                    </button>
                                </td>
                      `;
                    tbody.appendChild(tr);
                    closeModal();
                    AllProduct.push(res.data)

                  }
                })
              })
              .catch(error => {
                document.getElementById('spinner').classList.add('hidden');
                document.getElementById('modalBtnText').textContent = "Create"
                document.getElementById('modalSubmitBtn').disabled = false
                console.error("Upload Failed:", error);
              });
          } else {
            showToast("Please select Product Image", "denger")
            return;
          }

        } else if (action == 'update') {
          document.getElementById('spinner').classList.remove('hidden');
          document.getElementById('modalBtnText').textContent = "Sending..."
          document.getElementById('modalSubmitBtn').disabled = true
          const form = e.target;
          const formData = new FormData(form);
          if (file) {
            const uploadFormData = new FormData();
            uploadFormData.append('action', 'upload');
            uploadFormData.append('image', file);

            await fetch('/SwiftCart/AJAX/Vender_Product_ajax.php', {
                method: 'POST',
                body: uploadFormData
              })
              .then(res => res.json())
              .then(res => {
                formData.set('image', res.data.secure_url)
              })
          } else {
            formData.set('image', imagePreview.src)
          }
          const description = formData.get('description');

          const paragraphs = description
            .split(/\r?\n/) // split on newline
            .map(p => p.trim())
            .filter(p => p.length > 0);

          formData.set('description', JSON.stringify(paragraphs));

          // for (const [key, value] of formData.entries()) {
          //   console.log(`value of  ${key} is : ${value}`);
          // }
          fetch('/SwiftCart/AJAX/Vender_Product_ajax.php', {
              method: "POST",
              body: formData,
              credentials: 'include'
            }).then(res => res.json()).then(res => {
              if (res.success) {
                showToast("Product Request Send successfully", "success")
                document.getElementById('spinner').classList.add('hidden');
                document.getElementById('modalBtnText').textContent = "Update"
                document.getElementById('modalSubmitBtn').disabled = false

                const tbody = document.getElementById('Product-table-body');
                const tr = document.getElementById(`product-${res.data.id}`)

                const productJSON = encodeURIComponent(JSON.stringify(res.data));
                tr.id = `product-${res.data.id}`
                tr.innerHTML = `
                    <td class="px-6 py-4">${res.data.id}</td>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">${res.data.product_name}</th>
                    <td class="px-6 py-4">${res.data.category}</td>
                    <td class="px-6 py-4">${res.data.price}</td>
                    <td class="px-6 py-4">${res.data.stock ==0 ? 'out of stock':res.data.stock}</td>
                    <td class="px-6 py-4">${res.data.discount}%</td>
                    <td class="px-6 py-4">${res.data.product_state}</td>
                     <td class="flex gap-2 py-2">
                                    <button onclick="OpenEditModal(this)" data-product="${productJSON}" class="cursor-pointer text-white border-transparent bg-[#4fd1c5] border hover:border-[#4fd1c5] hover:text-[#4fd1c5] font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors duration-200 hover:bg-white flex items-center justify-center" title="Add">
                                        Update
                                    </button>
                                    <button onclick="OpenDeleteModal(${res.data.id},${res.data.venderid},'${res.data.product_name}')" class="cursor-pointer text-white flex items-center justify-center bg-[#4fd1c5] border border-transparent hover:border-[#4fd1c5] hover:text-[#4fd1c5] hover:bg-white font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors duration-200" title="Delete">
                                        Delete
                                    </button>
                                </td>
                      `;
                closeModal();
                const index = AllProduct.findIndex(p => p.id === res.data.id);

                if (index !== -1) {
                  AllProduct[index] = res.data;
                }

              }
            })
            .catch(error => {
              document.getElementById('spinner').classList.add('hidden');
              document.getElementById('modalBtnText').textContent = "Update"
              document.getElementById('modalSubmitBtn').disabled = false
              console.error("Upload Failed:", error);
            });


        }
      })

      function OpenEditModal(button) {
        setTimeout(() => {
          document.getElementById('myModal').classList.remove("scale-95", "opacity-0", "translate-y-[-20px]");
          document.getElementById('myModal').classList.add("scale-100", "opacity-100", "translate-y-0");
        }, 10);
        const productData = decodeURIComponent(button.getAttribute('data-product'));
        const products = JSON.parse(productData);
        document.getElementById('action').value = 'update'
        document.getElementById('modalBtnText').textContent = 'Update'
        document.getElementById('product_name').value = products.product_name
        document.getElementById('price').value = products.price
        document.getElementById('stock').value = products.stock
        document.getElementById('discount').value = products.discount
        document.getElementById('id').value = products.id
        document.getElementById('category').value = products.category
        document.getElementById('highlight').value = products.highlight
        imagePreview.src = products.image 
        imagePreview.classList.remove('hidden');
        imagePlaceholder.classList.add('hidden');
        deleteImageBtn.classList.remove('hidden');

        const infoContainer = document.getElementById('infoFields');
        infoContainer.innerHTML = '';

        let infoObject;
        try {
          infoObject = JSON.parse(products.information);
        } catch (e) {
          console.error("Invalid info JSON", e);
          return;
        }

        for (const [key, value] of Object.entries(infoObject)) {
          const fieldHTML = `
              <div class="grid grid-cols-2 gap-4 items-end group">
                <div>
                  <label class="textcolor text-sm font-medium block mb-1">Information</label>
                  <input type="text" name="info_key" value="${key}" class="inputcolor border border-[#4fd1c5] text-sm rounded-lg block w-full p-2.5" />
                </div>
                <div class="flex items-center gap-2">
                  <div class="w-full">
                    <label class="textcolor text-sm font-medium block mb-1">Value</label>
                    <input type="text" name="info_value" value="${value}" class="inputcolor border border-[#4fd1c5] text-sm rounded-lg block w-full p-2.5" />
                  </div>
                  <button type="button" class="removeRow bg-red-500 text-white px-3 py-1 rounded-lg text-sm h-10 mt-6">✕</button>
                </div>
              </div>
            `;
          infoContainer.insertAdjacentHTML('beforeend', fieldHTML);
        }
        const parsedDesc = JSON.parse(products.description);
        const paragraphs = Object.values(parsedDesc);

        const combinedText = paragraphs.join("\n\n");

        const textarea = document.getElementById("description");
        if (textarea) {
          textarea.value += (textarea.value ? "\n\n" : "") + combinedText;
        }
        document.getElementById('myModal').classList.remove('hidden')
        document.getElementById('myModal').classList.add('flex')

      }

      function DeleteProduct(id, venderid) {
        document.getElementById('modalSpinner').classList.remove('hidden')
        const formData = new FormData();
        formData.append('id', id)
        formData.append('venderid', venderid);
        formData.append('action', 'delete');

        fetch('/SwiftCart/AJAX/Vender_Product_ajax.php', {
          method: 'POST',
          body: formData
        }).then(res => res.json()).then((res) => {
          if (res.success) {
            showToast("Product Deleted successfully", "success")
            const tr=document.getElementById(`product-${id}`);
            tr.remove();
            AllProduct = AllProduct.filter(p => p.id !== id);


          } else {
            showToast("Failed to Deleted Product", "denger")
          }
        }).then(() => {
          CloseDeleteModal();
          document.getElementById('modalSpinner').classList.add('hidden')
        })
      }
    </script>

</body>

</html>