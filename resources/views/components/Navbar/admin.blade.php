<nav class="fixed top-0 z-50 w-full bg-white">
    <div class="px-10 py-3">
        <div class="flex items-center justify-between">

            <a href="/" class="text-2xl font-semibold text-black">
                SwiftCart
            </a>

            <div class="flex items-center gap-5">

                <div class="relative">
                    <x-lucide-search
                        class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />

                    <input
                        type="text"
                        placeholder="Search..."
                        class="w-72 h-10 pl-10 pr-4 py-2 bg-[#f5f5f5] rounded-lg focus:outline-none focus:ring-0 focus:border-transparent" />
                </div>

                <button class="relative p-2 rounded-full hover:bg-gray-100 transition cursor-pointer">

                    <x-lucide-bell class="w-6 h-6 text-gray-700" />

                    <span
                        class="absolute -top-1 -right-1 flex items-center justify-center w-5 h-5 text-[10px] font-semibold text-white bg-red-500 rounded-full">
                        8
                    </span>

                </button>

                <img
                    src="{{ asset('images/Review4.jpg') }}"
                    class="w-10 h-10 rounded-full object-cover border border-gray-300 cursor-pointer"
                    alt="Profile Image">

            </div>

        </div>
    </div>
</nav>