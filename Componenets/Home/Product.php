<?php

require __DIR__ . '/../../Database/db.php';

$stmt = $pdo->prepare('SELECT * FROM product');
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo '
        <section class="py-8">
            <div class="flex justify-between lg:w-[90vw] m-auto mt-3 px-4">
            <h1 class="text-2xl font-semibold">Tranding Products<br/>for you!</h1>
                <div>
                <button id="nextBtn" class="px-6 py-4 rounded-full font-semibold shadow text-white bg-[#d09523] hover:bg-[#f4b942] cursor-pointer text-sm transition">
                View All Products → 
                </button>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 w-[90vw] mx-auto">
                <div class="rounded-2xl shadow-md bg-white relative overflow-hidden">
                    <div class="absolute top-3 left-3 bg-teal-600 text-white text-xs px-2 py-[2px] rounded-full           font-medium">-20%</div>
                    <button class="absolute top-3 right-3 text-gray-400 hover:text-red-500">
                        <span class="group cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors duration-200" viewBox="0 0 24 24" fill="white" stroke="black" stroke-width="2">
                                <path class="group-hover:fill-red-500 group-hover:stroke-red-500" stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/>
                            </svg>
                        </span>
                    </button>
                    <img src="/SwiftCart/Image/stationary.jpg" alt="Luxe Lounge Sofa" class="w-full h-44 object-contain mt-6 mb-4" />
                    <div class="bg-[#234445] text-white px-4 py-3 rounded-b-2xl flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium">Luxe Lounge Sofa</h3>
                            <p class="text-xs mt-1">$235.99</p>
                        </div>
                        <button class="bg-white text-[#234445] rounded-full p-2 hover:bg-gray-100 transition">
                            <span class="text-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.6 6M7 13l-1.3 5.2a1 1 0 001 .8h12a1 1 0 001-.8L17 13M10 21a1 1 0 100-2 1 1 0 000 2zm7 0a1 1 0 100-2 1 1 0 000 2z" />
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>
                <div class="rounded-2xl shadow-md bg-white relative overflow-hidden">
                    <div class="absolute top-3 left-3 bg-teal-600 text-white text-xs px-2 py-[2px] rounded-full           font-medium">-20%</div>
                    <button class="absolute top-3 right-3 text-gray-400 hover:text-red-500">
                        <span class="group cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors duration-200" viewBox="0 0 24 24" fill="white" stroke="black" stroke-width="2">
                                <path class="group-hover:fill-red-500 group-hover:stroke-red-500" stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/>
                            </svg>
                        </span>
                    </button>
                    <img src="/SwiftCart/Image/stationary.jpg" alt="Luxe Lounge Sofa" class="w-full h-44 object-contain mt-6 mb-4" />
                    <div class="bg-[#234445] text-white px-4 py-3 rounded-b-2xl flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium">Luxe Lounge Sofa</h3>
                            <p class="text-xs mt-1">$235.99</p>
                        </div>
                        <button class="bg-white text-[#234445] rounded-full p-2 hover:bg-gray-100 transition">
                            <span class="text-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.6 6M7 13l-1.3 5.2a1 1 0 001 .8h12a1 1 0 001-.8L17 13M10 21a1 1 0 100-2 1 1 0 000 2zm7 0a1 1 0 100-2 1 1 0 000 2z" />
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>
                <div class="rounded-2xl shadow-md bg-white relative overflow-hidden">
                    <div class="absolute top-3 left-3 bg-teal-600 text-white text-xs px-2 py-[2px] rounded-full           font-medium">-20%</div>
                    <button class="absolute top-3 right-3 text-gray-400 hover:text-red-500">
                        <span class="group cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors duration-200" viewBox="0 0 24 24" fill="white" stroke="black" stroke-width="2">
                                <path class="group-hover:fill-red-500 group-hover:stroke-red-500" stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/>
                            </svg>
                        </span>
                    </button>
                    <img src="/SwiftCart/Image/stationary.jpg" alt="Luxe Lounge Sofa" class="w-full h-44 object-contain mt-6 mb-4" />
                    <div class="bg-[#234445] text-white px-4 py-3 rounded-b-2xl flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium">Luxe Lounge Sofa</h3>
                            <p class="text-xs mt-1">$235.99</p>
                        </div>
                        <button class="bg-white text-[#234445] rounded-full p-2 hover:bg-gray-100 transition">
                            <span class="text-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.6 6M7 13l-1.3 5.2a1 1 0 001 .8h12a1 1 0 001-.8L17 13M10 21a1 1 0 100-2 1 1 0 000 2zm7 0a1 1 0 100-2 1 1 0 000 2z" />
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>
                <div class="rounded-2xl shadow-md bg-white relative overflow-hidden">
                    <div class="absolute top-3 left-3 bg-teal-600 text-white text-xs px-2 py-[2px] rounded-full           font-medium">-20%</div>
                    <button class="absolute top-3 right-3 text-gray-400 hover:text-red-500">
                        <span class="group cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors duration-200" viewBox="0 0 24 24" fill="white" stroke="black" stroke-width="2">
                                <path class="group-hover:fill-red-500 group-hover:stroke-red-500" stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/>
                            </svg>
                        </span>
                    </button>
                    <img src="/SwiftCart/Image/stationary.jpg" alt="Luxe Lounge Sofa" class="w-full h-44 object-contain mt-6 mb-4" />
                    <div class="bg-[#234445] text-white px-4 py-3 rounded-b-2xl flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium">Luxe Lounge Sofa</h3>
                            <p class="text-xs mt-1">$235.99</p>
                        </div>
                        <button class="bg-white text-[#234445] rounded-full p-2 hover:bg-gray-100 transition">
                            <span class="text-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.6 6M7 13l-1.3 5.2a1 1 0 001 .8h12a1 1 0 001-.8L17 13M10 21a1 1 0 100-2 1 1 0 000 2zm7 0a1 1 0 100-2 1 1 0 000 2z" />
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>
                <div class="rounded-2xl shadow-md bg-white relative overflow-hidden">
                    <div class="absolute top-3 left-3 bg-teal-600 text-white text-xs px-2 py-[2px] rounded-full           font-medium">-20%</div>
                    <button class="absolute top-3 right-3 text-gray-400 hover:text-red-500">
                        <span class="group cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors duration-200" viewBox="0 0 24 24" fill="white" stroke="black" stroke-width="2">
                                <path class="group-hover:fill-red-500 group-hover:stroke-red-500" stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/>
                            </svg>
                        </span>
                    </button>
                    <img src="/SwiftCart/Image/stationary.jpg" alt="Luxe Lounge Sofa" class="w-full h-44 object-contain mt-6 mb-4" />
                    <div class="bg-[#234445] text-white px-4 py-3 rounded-b-2xl flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium">Luxe Lounge Sofa</h3>
                            <p class="text-xs mt-1">$235.99</p>
                        </div>
                        <button class="bg-white text-[#234445] rounded-full p-2 hover:bg-gray-100 transition">
                            <span class="text-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.6 6M7 13l-1.3 5.2a1 1 0 001 .8h12a1 1 0 001-.8L17 13M10 21a1 1 0 100-2 1 1 0 000 2zm7 0a1 1 0 100-2 1 1 0 000 2z" />
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>
                <div class="rounded-2xl shadow-md bg-white relative overflow-hidden">
                    <div class="absolute top-3 left-3 bg-teal-600 text-white text-xs px-2 py-[2px] rounded-full           font-medium">-20%</div>
                    <button class="absolute top-3 right-3 text-gray-400 hover:text-red-500">
                        <span class="group cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors duration-200" viewBox="0 0 24 24" fill="white" stroke="black" stroke-width="2">
                                <path class="group-hover:fill-red-500 group-hover:stroke-red-500" stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/>
                            </svg>
                        </span>
                    </button>
                    <img src="/SwiftCart/Image/stationary.jpg" alt="Luxe Lounge Sofa" class="w-full h-44 object-contain mt-6 mb-4" />
                    <div class="bg-[#234445] text-white px-4 py-3 rounded-b-2xl flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium">Luxe Lounge Sofa</h3>
                            <p class="text-xs mt-1">$235.99</p>
                        </div>
                        <button class="bg-white text-[#234445] rounded-full p-2 hover:bg-gray-100 transition">
                            <span class="text-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.6 6M7 13l-1.3 5.2a1 1 0 001 .8h12a1 1 0 001-.8L17 13M10 21a1 1 0 100-2 1 1 0 000 2zm7 0a1 1 0 100-2 1 1 0 000 2z" />
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>
                
            </div>


        </section>';
?>