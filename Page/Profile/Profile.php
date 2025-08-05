<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftCart | Profile</title>
    <?php include __DIR__ . '/../../Componenets/Header.php'; ?>
</head>

<body>
    <?php require __DIR__ . '/../../Componenets/Navbar.php' ?>
    <div class="min-h-[100vh] w-full md:w-[90%] lg:w-[80%] mt-10 mx-auto flex flex-col gap-8 p-4">
        <p data-aos="fade-left" class="flex justify-center lg:justify-end gap-2 text-[#d09523] text-lg">
            <span data-aos="fade-left" class="text-black">Welcome</span> <span id="welcome"></span>
        </p>

        <section class="flex flex-col-reverse lg:flex-row gap-8 min-h-[80vh]">
            <div data-aos="fade-right" class="flex flex-col space-y-6 w-full md:w-[40%] lg:w-[25%]">
                <div>
                    <h2 class="font-medium text-lg mb-2">Manage My Account</h2>
                    <div class="flex flex-col space-y-2 text-gray-500">
                        <p class="hover:text-gray-700">My Profile</p>
                        <p class="hover:text-gray-700">Address Book</p>
                        <p class="hover:text-gray-700">My Payment Options</p>
                    </div>
                </div>

                <div>
                    <h2 class="font-medium text-lg mb-2">My Orders</h2>
                    <div class="flex flex-col space-y-2 text-gray-500">
                        <p class="hover:text-gray-700">My Returns</p>
                        <p class="hover:text-gray-700">My Cancellations</p>
                    </div>
                </div>

                <p class="font-medium text-lg hover:text-gray-700">My Wishlist</p>
            </div>

            <form data-aos="fade-left" method="post" id="Profileform" class="flex flex-col w-full">
                <h2 class="text-[#d09523] text-xl font-semibold mb-4">Edit Your Profile</h2>

                <div class="flex flex-col gap-6">
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="w-full">
                            <p class="text-black">First Name</p>
                            <input
                                class="bg-[#F5F5F5] p-3 border-none w-full outline-none text-gray-500"
                                type="text" name="firstName" id="firstName" />
                        </div>
                        <div class="w-full">
                            <p class="text-black">Last Name</p>
                            <input
                                class="bg-[#F5F5F5] p-3 border-none w-full outline-none text-gray-500"
                                type="text" name="lastName" id="lastName" />
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="w-full">
                            <p class="text-black">Email</p>
                            <input
                                class="bg-[#F5F5F5] p-3 border-none w-full outline-none text-gray-500"
                                type="text" id="email" name="email" />
                        </div>
                        <div class="w-full">
                            <p class="text-black">mobile</p>
                            <input
                                class="bg-[#F5F5F5] p-3 border-none w-full outline-none text-gray-500"
                                type="text" id="mobile" name="mobile" />
                        </div>
                        <div class="w-full">
                            <p class="text-black">Address</p>
                            <input
                                class="bg-[#F5F5F5] p-3 border-none w-full outline-none text-gray-500"
                                type="text" id="address" name="address" />

                        </div>
                    </div>

                    <div class="flex flex-col gap-6">
                        <p class="text-black">Password Changes</p>
                        <input
                            placeholder="Old Password"
                            class="bg-[#F5F5F5] p-3 border-none w-full outline-none text-gray-500"
                            type="password" name="oldPassword" />
                        <input
                            placeholder="New Password"
                            class="bg-[#F5F5F5] p-3 border-none w-full outline-none text-gray-500"
                            type="password" name="newPassword" />
                        <input
                            placeholder="Confirm Password"
                            class="bg-[#F5F5F5] p-3 border-none w-full outline-none text-gray-500"
                            type="password" />
                    </div>

                    <div class="flex justify-center lg:justify-end">
                        <button type="submit"
                            class="py-3 px-6 rounded-md font-semibold shadow text-white bg-[#d09523] hover:bg-[#f4b942] cursor-pointer text-sm transition text-center">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </section>
    </div>
    <!-- <?php include __DIR__ . '/../../Componenets/Footer.php'; ?> -->
    <script>
        function fetchDetail() {
            const formData = new FormData();
            formData.append('action', 'fetch');
            fetch('/SwiftCart/AJAX/Profile_ajax.php', {
                method: 'POST',
                body: formData
            }).then(res => res.json()).then((res) => {
                console.log(res.data)
                const nameParts = res.data.name.trim().split(" ");

                document.getElementById("firstName").value = nameParts[0] || "";
                document.getElementById("lastName").value = nameParts[1] || "";
                document.getElementById('welcome').textContent = res.data.name
                document.getElementById('mobile').value = res.data.mobile ? res.data.mobile : ""
                document.getElementById('address').value = res.data.address ? res.data.address : ""
            })

        }
        document.addEventListener('DOMContentLoaded', function() {
            fetchDetail()
        })
        document.getElementById('Profileform').addEventListener('submit', function(e) {
            e.preventDefault();
            const form=e.target;
            const formData=new FormData(form);
            formData.append('action','UpdateCustomerProfile')
             fetch('/SwiftCart/AJAX/Profile_ajax.php', {
                method: 'POST',
                body: formData
            }).then(res => res.json()).then((res)=>{
                if(res.notfound == true){
                    showToast("User not found.",'danger')
                }
                else if(res.wrongpass == true){
                    showToast("Please enter Currect Old Password",'danger')
                }else if(res.success == true){
                    showToast("Profile updated successfully","success")
                }else{
                    showToast("Failed to update profile.","danger")
                }
            })

        })
    </script>
</body>

</html>