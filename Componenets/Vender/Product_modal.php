<?php

echo '
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
                  <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category[\'name\']) ?>"><?= htmlspecialchars($category[\'name\']) ?></option>
                  <?php endforeach; ?>
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
              <span id="btnText">Yes, I\'m sure</span>
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
'

?>