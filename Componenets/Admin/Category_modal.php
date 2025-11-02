<?php

echo ' <div
        id="popup-modal"
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
                        id="categoryName"
                        class="mb-5 text-lg font-normal text-gray-500"></h3>
                    <button
                        id="deleteBtn"
                        data-modal-hide="popup-modal"
                        onclick="DeleteCategory()"
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

    <div
        id="myModal"
        tabindex="-1"
        aria-hidden="true"
        class="backdrop-blur-sm hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full   rounded-2xl shadow-lg transform scale-95 opacity-0 translate-y-[-20px] transition-all duration-300 ease-out">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-2xl shadow-lg">
                <div
                    class="flex items-center justify-between p-4 md:p-5 rounded-t bgcolor">
                    <h3 class="text-lg font-semibold text-white" id="modalTitle">
                        Create New Category
                    </h3>
                    <button
                        type="button"
                        onclick="closeModal()"
                        class="text-white hover:text-[#4fd1c5] cursor-pointer hover:bg-white bg-transparent rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center transition-colors duration-200"
                        data-modal-toggle="crud-modal">
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
                </div>
                <!-- Modal body -->
                <form class="p-4 md:p-5" method="post" id="categoryForm">
                    <input type="hidden" name="action" id="formAction" />
                    <input type="hidden" name="id" id="edit_id" />
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">

                            <div class="flex flex-col items-center gap-4">
                                <label for="imageInput" class="w-full h-64 border-2 border-dashed border-[#4fd1c5] flex justify-center items-center overflow-hidden rounded-lg cursor-pointer">
                                    <img id="imagePreview" src="" alt="Preview" class="max-h-full object-contain hidden" />
                                    <span id="imagePlaceholder" class="text-gray-400">Select image</span>
                                </label>
                                <input type="file" name="image" id="imageInput" accept="image/*" class="hidden" />
                            </div>
                        </div>
                        <div class="col-span-2">
                            <label
                                for="name"
                                class="block mb-2 text-sm font-medium textcolor">Name</label>
                            <input
                                required
                                type="text"
                                name="name"
                                id="modal_name"
                                class="inputcolor border border-[#4fd1c5] text-gray-900 text-sm rounded-lg focus:border-[#4fd1c5] block w-full p-2.5"
                                placeholder="Category name" />
                        </div>
                        <div class="col-span-2">
                            <label
                                for="description"
                                class="block mb-2 text-sm font-medium textcolor">Description</label>
                            <textarea
                                required
                                name="description"
                                id="modal_description"
                                rows="4"
                                class="block p-2.5 w-full text-sm text-gray-900 inputcolor rounded-lg border border-[#4fd1c5] focus:border-[#4fd1c5]"
                                placeholder="Category description"></textarea>
                        </div>
                    </div>
                    <button
                        type="submit"
                        id="modalSubmitBtn"
                        class="text-white cursor-pointer inline-flex items-center gap-2 bg-[#4fd1c5] hover:border hover:border-[#4fd1c5] hover:text-[#4fd1c5] hover:bg-white outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors duration-200">

                        <div id="spinner" class="hidden w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
                        <span id="modalBtnText">Create</span>
                    </button>



                </form>
            </div>
        </div>
    </div>'

?>