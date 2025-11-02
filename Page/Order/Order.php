<?php
require __DIR__ . '/../../Database/db.php';
require __DIR__ . '/../../Database/Function.php';

$table = 'review';

$createSQL = " CREATE TABLE IF NOT EXISTS review (
id SERIAL PRIMARY KEY,
  product_id BIGINT REFERENCES product(id),
  user_id BIGINT REFERENCES users(id),
  order_id BIGINT REFERENCES orders(id),
  rating INT CHECK (rating BETWEEN 1 AND 5),
  comment TEXT,
  created_at TIMESTAMP DEFAULT now(),
  updated_at TIMESTAMP DEFAULT now(),
  is_approved BOOLEAN DEFAULT TRUE
);
";
checkAndCreateTable($pdo, $table, $createSQL);

$table1 = 'query';
$createSQL1 = "CREATE TABLE query (
id SERIAL PRIMARY KEY,
  product_id BIGINT REFERENCES product(id),
  user_id BIGINT REFERENCES users(id),
  order_id BIGINT REFERENCES orders(id),
  subject VARCHAR(255),
  message TEXT,
  resolve BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";
checkAndCreateTable($pdo, $table1, $createSQL1);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SwiftCart | Order Details</title>
    <?php include __DIR__ . '/../../Componenets/Other/Header.php'; ?>
</head>

<body>
  <?php require __DIR__ . '/../../Componenets/Home/Navbar.php' ?>
  <div class="bg-gray-100 p-6 min-h-[60vh]" >
    <div class="max-w-7xl mx-auto">
      <h1 class="text-3xl font-bold mb-6 text-center" data-aos="zoom-in">All Orders</h1>
      <div id="orderContainer" class="space-y-6"></div>
    </div>
  </div>

  <div id="crud-modal" tabindex="-1" aria-hidden="true" class="backdrop-blur-sm hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full transform scale-95 opacity-0 translate-y-[-20px] transition-all duration-300 ease-out">
    <div class="relative p-4 w-full max-w-md max-h-full">
      <div class="relative bg-white rounded-lg shadow-sm ">
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t bg-[#204d4f]  border-gray-200">
          <h3 class="text-lg font-semibold text-white">
            Contact the Vendor
          </h3>
          <button type="button" onclick="CloseQuestionModal()" class="text-white cursor-pointer bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-toggle="crud-modal">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>
        <form class="p-4 md:p-5" id="QuestionForm">
          <input type="hidden" name="product_id" id="product_id">
          <input type="hidden" name="order_id" id="order_id">
          <div class="grid gap-4 mb-4 grid-cols-2">
            <div class="col-span-2">
              <label for="name" class="block mb-2 text-sm font-medium text-gray-900">subject</label>
              <input type="text" name="subject" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="Subject" required="">
            </div>
            <div class="col-span-2">
              <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Description</label>
              <textarea id="description" rows="4" name="message" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300" placeholder="Write Question description"></textarea>
            </div>
          </div>
          <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 cursor-pointer text-white px-4 py-2 rounded-lg text-sm flex font-semibold transition gap-2 items-center">
            <div id="modalSpinner" class="hidden w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
            <span id="ButtonText">Send Question</span>
          </button>
        </form>
      </div>
    </div>
  </div>

  <div id="review-modal" tabindex="-1" aria-hidden="true" class="backdrop-blur-sm hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full transform scale-95 opacity-0 translate-y-[-20px] transition-all duration-300 ease-out">
    <div class="relative p-4 w-full max-w-md max-h-full">
      <div class="relative bg-white rounded-lg shadow-sm ">
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t bg-[#204d4f]  border-gray-200">
          <h3 class="text-lg font-semibold text-white">
            Share Your Experience
          </h3>
          <button type="button" onclick="CloseReviewModal()" class="text-white cursor-pointer bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-toggle="crud-modal">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>
        <form id="ReviewForm" class="p-4 md:p-5">
          <input type="hidden" name="product_id" id="review_product_id">
          <input type="hidden" name="order_id" id="review_order_id">
          <div class="grid gap-4 mb-4 grid-cols-2">
            <div class="col-span-2">
              <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Rating</label>
              <input type="number" min="1" max="5" name="rating" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="Rate 1 - 5" required="">
            </div>
            <div class="col-span-2">
              <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Description</label>
              <textarea id="description" rows="4" name="comment" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300" placeholder="Write Experience"></textarea>
            </div>
          </div>
          <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 cursor-pointer text-white px-4 py-2 rounded-lg text-sm flex font-semibold transition gap-2 items-center">
            <div id="ReviewmodalSpinner" class="hidden w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
            <span id="ReviewButtonText">Submit</span>
          </button>
        </form>
      </div>
    </div>
  </div>

  <?php include __DIR__ . '/../../Componenets/Home/Footer.php'; ?>
  <script>
    async function loadOrders() {
      const formData = new FormData();
      formData.append('action', 'FetchOrder')
      const res = await fetch('/SwiftCart/AJAX/Checkout_ajax.php', {
        method: "POST",
        body: formData
      });
      const json = await res.json();

      if (json.status === 'success') {
        const container = document.getElementById('orderContainer');
        container.innerHTML = '';

        if (json.data.length == 0) {
          container.innerHTML = `
                <div class="flex justify-center items-center min-h-[60vh] text-gray-500 text-xl font-medium">
                  No Order Found
                </div>
              `;
          return

        }
        const groupedOrders = {};

        json.data.forEach(order => {
          const createdAt = new Date(order.created_at);
          createdAt.setMilliseconds(0);
          createdAt.setSeconds(0);
          const createdKey = createdAt.toISOString();

          const groupKey = `${order.total_amount}_${order.payment_status}_${createdKey}`;

          if (!groupedOrders[groupKey]) {
            groupedOrders[groupKey] = {
              ...order,
              created_at: createdAt,
              orders: [],
            };
          }

          groupedOrders[groupKey].orders.push(order);
        });

        // Now render one card per group
        Object.values(groupedOrders).forEach(group => {
          const products = group.orders.map(p => `
              <div class="flex items-center flex-col sm:flex-row gap-4 py-3 border-b last:border-b-0">
                <img src="${p.product_image || '#'}" alt="${p.name || ''}" class="w-16 h-16 object-contain rounded-lg bg-gray-100 border" />
                <div class="flex-1">
                  <div class="font-semibold text-gray-800">${p.name || 'Product'}</div>
                  <div class="text-sm text-gray-500">₹${p.product_price || 0} x ${p.quantity}</div>
                </div>
                <div>
                  <button onclick="OpenQuestionModal(${p.productid},${p.id})" class="bg-yellow-500 hover:bg-yellow-600 cursor-pointer text-white px-4 py-2 rounded-lg text-sm font-semibold transition">Any Question</button>
                  <button onclick="OpenReviewModal(${p.productid},${p.id})" class="bg-yellow-500 hover:bg-yellow-600 cursor-pointer text-white px-4 py-2 rounded-lg text-sm font-semibold transition">Review Product</button>
                </div>
              </div>
            `).join('');

          container.innerHTML += `
    <div data-aos="zoom-in" class="bg-white shadow-lg rounded-2xl p-8 border border-gray-100">
      <div class="flex flex-wrap justify-between items-center mb-4 gap-2">
        <div class="text-lg font-bold text-[#234445]"># ${group.id}</div>
        <div class="text-sm text-gray-500">${new Date(group.created_at).toLocaleString()}</div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
          <div class="mb-1"><span class="font-semibold text-gray-700">Customer:</span> ${group.first_name} ${group.last_name}</div>
          <div class="mb-1"><span class="font-semibold text-gray-700">Email:</span> ${group.email}</div>
          <div class="mb-1"><span class="font-semibold text-gray-700">Phone:</span> ${group.phone}</div>
        </div>
        <div>
          <div class="mb-1"><span class="font-semibold text-gray-700">Address:</span> ${group.address}, ${group.city}, ${group.country} (${group.zip_code})</div>
          <div class="mb-1"><span class="font-semibold text-gray-700">Company:</span> ${group.company || '-'}</div>
        </div>
      </div>
      <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div><span class="font-semibold text-gray-700">Total Amount:</span> <span class="text-green-700 font-bold">₹${parseFloat(group.total_amount).toFixed(2)}</span></div>
        <div><span class="font-semibold text-gray-700">Payment Status:</span> <span class="${group.payment_status === 'Completed' ? 'text-green-600' : 'text-red-600'} font-semibold">${group.payment_status}</span></div>
      </div>
      <div>
        <span class="font-semibold text-gray-800 mb-2 block">Products:</span>
        <div class="divide-y divide-gray-200">${products}</div>
      </div>
    </div>
  `;
        });

      } else {
        showToast("Failed to load orders", 'danger');
      }
    }

    document.addEventListener('DOMContentLoaded', function() {
      loadOrders();
    })

    function OpenQuestionModal(productid, orderid) {
      document.getElementById('crud-modal').classList.remove('hidden')
      document.getElementById('crud-modal').classList.add('flex')
      document.getElementById('product_id').value = productid
      document.getElementById('order_id').value = orderid
      setTimeout(() => {
        document.getElementById('crud-modal').classList.remove("scale-95", "opacity-0", "translate-y-[-20px]");
        document.getElementById('crud-modal').classList.add("scale-100", "opacity-100", "translate-y-0");
      }, 10);
    }

    function CloseQuestionModal() {
      document.getElementById('crud-modal').classList.add("scale-95", "opacity-0", "translate-y-[-20px]");
      document.getElementById('crud-modal').classList.remove("scale-100", "opacity-100", "translate-y-0");
      document.getElementById('QuestionForm').reset()
      setTimeout(() => {
        document.getElementById('crud-modal').classList.remove('flex')
        document.getElementById('crud-modal').classList.add('hidden')
      }, 300);
    }

    function OpenReviewModal(productid, orderid) {
      document.getElementById('review-modal').classList.remove('hidden')
      document.getElementById('review-modal').classList.add('flex')
      document.getElementById('review_product_id').value = productid
      document.getElementById('review_order_id').value = orderid
      setTimeout(() => {
        document.getElementById('review-modal').classList.remove("scale-95", "opacity-0", "translate-y-[-20px]");
        document.getElementById('review-modal').classList.add("scale-100", "opacity-100", "translate-y-0");
      }, 10);
    }

    function CloseReviewModal() {
      document.getElementById('review-modal').classList.add("scale-95", "opacity-0", "translate-y-[-20px]");
      document.getElementById('review-modal').classList.remove("scale-100", "opacity-100", "translate-y-0");
      document.getElementById('ReviewForm').reset()
      setTimeout(() => {
        document.getElementById('review-modal').classList.remove('flex')
        document.getElementById('review-modal').classList.add('hidden')
      }, 300);
    }

    document.getElementById('QuestionForm').addEventListener('submit', function(e) {
      e.preventDefault();
      document.getElementById('modalSpinner').classList.remove('hidden')
      document.getElementById('ButtonText').textContent = 'Sending'

      const form = e.target;
      const formData = new FormData(form);
      formData.append('action', 'Create')

      fetch('/SwiftCart/AJAX/Query_ajax.php', {
        method: "POST",
        body: formData
      }).then(res => res.json()).then((res) => {
        if (res.success == true) {
          showToast("Question Send Successfully Seller will contact you Soon", 'success')
        } else {
          showToast("Failed to Send Question", 'danger')
        }
      }).then(() => {
        CloseQuestionModal()
      })
    })
    document.getElementById('ReviewForm').addEventListener('submit', function(e) {
      e.preventDefault();
      document.getElementById('ReviewmodalSpinner').classList.remove('hidden')
      document.getElementById('ReviewButtonText').textContent = 'Submitting...'

      const form = e.target;
      const formData = new FormData(form);
      formData.append('action', 'Create')

      fetch('/SwiftCart/AJAX/Review_ajax.php', {
        method: "POST",
        body: formData
      }).then(res => res.json()).then((res) => {
        if (res.success == true) {
          showToast("Review Added Successfully", 'success')
        } else {
          showToast("Failed to add Review", 'danger')
        }
      }).then(() => {
        CloseReviewModal()
      })
    })
  </script>
</body>

</html>