<nav class="fixed top-0 z-50 w-full bg-[#224e51] text-[#64883e] shadow-lg">
    <div class="px-4 py-3 sm:px-8">
        <div class="flex items-center justify-between">

            <a href="/" class="text-2xl font-semibold tracking-tight text-[#FFFFFF] transition-colors duration-300 hover:text-[#F5F7F4]">
                SwiftCart
            </a>

            <div class="flex items-center gap-5">

                <div class="relative hidden sm:block">
                    <x-lucide-search
                        class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-[#344054]" />

                    <input
                        type="text"
                        placeholder="Search..."
                        class="h-10 w-72 rounded-xl border border-[#FFFFFF] bg-white py-2 pl-10 pr-4 text-sm text-[#000000] placeholder:text-[#000000] transition focus:border-[#FFFFFF] focus:outline-none focus:ring-2 focus:ring-[#FFFFFF]" />
                </div>

                <a href="{{ route('admin.notification') }}" class="relative cursor-pointer rounded-xl p-2 transition duration-300 hover:bg-white/15 hover:text-[#d9e4e4]" aria-label="Notifications" title="Notifications">
                    <x-lucide-bell class="h-6 w-6 text-[#FFFFFF]" />
                    <span id="nav-unread-badge"
                        class="absolute -top-1 -right-1 flex items-center justify-center w-5 h-5 text-[10px] font-semibold text-white bg-red-500 rounded-full">
                        8
                    </span>
                </a>

                <a href="{{ route('admin.profile') }}" title="My Profile" class="block shrink-0">
                    <img
                        src="{{ asset('images/Review4.jpg') }}"
                        class="h-10 w-10 cursor-pointer rounded-full border-2 border-[#FFFFFF] object-cover transition duration-300 hover:scale-105"
                        alt="Profile Image">
                </a>

            </div>

        </div>
    </div>
</nav>