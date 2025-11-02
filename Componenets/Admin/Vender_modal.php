<?php

echo ' <div
        id="myModal"
        tabindex="-1"
        aria-hidden="true"
        class="backdrop-blur-sm hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full transform scale-95 opacity-0 translate-y-[-20px] transition-all duration-300 ease-out">
        <div class="relative  w-full max-w-md max-h-full bg-white rounded-2xl shadow-lg">
            <div class="relative ">
                <div
                    class="flex items-center justify-between p-4 md:p-5 rounded-t bgcolor">
                    <h3 class="text-lg font-semibold text-white" id="modalTitle">
                        Create New Vender
                    </h3>
                    <button
                        type="button"
                        onclick="CloseModal()"
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
                <form class="p-4 md:p-5" method="post" id="VenderForm">
                    <input type="hidden" name="action" id="formAction" />
                    <input type="hidden" name="id" id="edit_id" />
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label
                                for="name"
                                class="block mb-2 text-sm font-medium textcolor">Name</label>
                            <input
                                required=""
                                type="text"
                                name="name"
                                id="name"
                                class="inputcolor outline-none border border-[#4fd1c5] text-gray-900 text-sm rounded-lg focus:border-[#4fd1c5] block w-full p-2.5"
                                placeholder="Name" />
                        </div>
                        <div class="col-span-1">
                            <label
                                for="account_no"
                                class="block mb-2 text-sm font-medium textcolor">Account no</label>
                            <input
                                required=""
                                type="text"
                                name="account_no"
                                id="account_no"
                                class="inputcolor border border-[#4fd1c5] text-gray-900 text-sm rounded-lg focus:border-[#4fd1c5] block w-full p-2.5"
                                placeholder="Account number" />
                        </div>
                        <div class="col-span-1">
                            <label
                                for="ifsc_code"
                                class="block mb-2 text-sm font-medium textcolor">IFSC</label>
                            <input
                                required=""
                                type="text"
                                name="ifsc_code"
                                id="ifsc_code"
                                class="inputcolor outline-none border border-[#4fd1c5] text-gray-900 text-sm rounded-lg focus:border-[#4fd1c5] block w-full p-2.5"
                                placeholder="IFSC code" />
                        </div>
                        <div class="col-span-1">
                            <label
                                for="mobile"
                                class="block mb-2 text-sm font-medium textcolor">Moblle no</label>
                            <input
                                required
                                type="tel"
                                name="mobile"
                                id="mobile"
                                pattern="^[0-9]{10}$"
                                maxlength="10"
                                inputmode="numeric"
                                class="inputcolor outline-none border border-[#4fd1c5] text-gray-900 text-sm rounded-lg focus:border-[#4fd1c5] block w-full p-2.5"
                                placeholder="Enter 10-digit mobile number" />
                        </div>
                        <div class="col-span-1">
                            <label
                                for="company_name"
                                class="block mb-2 text-sm font-medium textcolor">Company</label>
                            <input
                                required=""
                                type="text"
                                name="company_name"
                                id="company_name"
                                class="inputcolor outline-none border border-[#4fd1c5] text-gray-900 text-sm rounded-lg focus:border-[#4fd1c5] block w-full p-2.5"
                                placeholder="Company Name" />
                        </div>
                        <div class="col-span-2">
                            <label
                                for="email"
                                class="block mb-2 text-sm font-medium textcolor">Email</label>
                            <input
                                required=""
                                type="email"
                                name="email"
                                id="email"
                                class="inputcolor outline-none border border-[#4fd1c5] text-gray-900 text-sm rounded-lg focus:border-[#4fd1c5] block w-full p-2.5"
                                placeholder="Email" />
                        </div>
                        <div class="col-span-2">
                            <label
                                for="address"
                                class="block mb-2 text-sm font-medium textcolor">Address</label>
                            <input
                                required=""
                                type="text"
                                name="address"
                                id="address"
                                class="inputcolor outline-none border border-[#4fd1c5] text-gray-900 text-sm rounded-lg focus:border-[#4fd1c5] block w-full p-2.5"
                                placeholder="Address" />
                        </div>
                        <div class="col-span-2">
                            <label
                                for="password"
                                class="block mb-2 text-sm font-medium textcolor">Password</label>
                            <input
                                required=""
                                type="text"
                                name="password"
                                id="password"
                                class="inputcolor outline-none border border-[#4fd1c5] text-gray-900 text-sm rounded-lg focus:border-[#4fd1c5] block w-full p-2.5"
                                placeholder="Password" />
                        </div>
                    </div>
                    <button
                        type="submit"
                        id="modalSubmitBtn"
                        class="text-white p-4 cursor-pointer inline-flex items-center gap-2 bg-[#4fd1c5] border border-transparent hover:border-[#4fd1c5] hover:text-[#4fd1c5] hover:bg-white outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center duration-300 transition-colors">

                        <!-- Spinner inherits text color -->
                        <div id="spinner" class="hidden w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
                        <span id="modalBtnText">Create</span>
                    </button>
            </div>
            </form>
        </div>
    </div>'

?>