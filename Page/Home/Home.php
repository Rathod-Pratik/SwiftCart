<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
    <?php include __DIR__ . '/../../Componenets/Other/Header.php'; ?>
  <style>
    .scrollbar-hide::-webkit-scrollbar {
      display: none;
    }

    .scrollbar-hide {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
  </style>
</head>

<body>
  <?php require __DIR__ . '/../../Componenets/Home/Navbar.php' ?>
  <?php require __DIR__ . '/../../Componenets/Home/Hero.php' ?>
  <?php require __DIR__ . '/../../Componenets/Home/Services.php' ?>
  <?php require __DIR__ . '/../../Componenets/Home/Collection.php' ?>
  <?php require __DIR__ . '/../../Componenets/Home/Categories.php' ?>
  <?php require __DIR__ . '/../../Componenets/Home/Product.php' ?>
  <?php require __DIR__ . '/../../Componenets/Home/FlashSale.php' ?>
  <?php require __DIR__ . '/../../Componenets/Home/CashBack.php' ?>
  <?php require __DIR__ . '/../../Componenets/Home/Review.php' ?>
  <?php require __DIR__ . '/../../Componenets/Home/FAQ.php' ?>
  <?php require __DIR__ . '/../../Componenets/Home/SubScribe.php' ?>
  <?php include __DIR__ . '/../../Componenets/Home/Footer.php'; ?>

  <script>
    document.querySelectorAll('.faq-item').forEach(item => {
      const question = item.querySelector('.faq-question');
      const icon = item.querySelector('.faq-icon');
      const answer = item.querySelector('.faq-answer');

      question?.addEventListener('click', () => {
        const isOpen = item.classList.contains('bg-[#245955]');

        // Close all
        document.querySelectorAll('.faq-item').forEach(el => {
          el.classList.remove('bg-[#245955]', 'text-white');
          el.classList.add('bg-gray-100');

          const ic = el.querySelector('.faq-icon');
          ic.textContent = '→';
          ic.classList.remove('text-white');
          ic.classList.add('text-gray-600');

          const ans = el.querySelector('.faq-answer');
          if (ans) {
            ans.style.maxHeight = '0px';
          }
        });

        // Open current if not already open
        if (!isOpen) {
          item.classList.remove('bg-gray-100');
          item.classList.add('bg-[#245955]', 'text-white');
          icon.textContent = '×';
          icon.classList.remove('text-gray-600');
          icon.classList.add('text-white');

          if (answer) {
            answer.style.maxHeight = answer.scrollHeight + 'px';
          }
        }
      });
    });

    document.addEventListener("DOMContentLoaded", () => {
      const container = document.getElementById("reviewScrollWrapper");
      const STEP = 660; // width + gap
      const DURATION = 400;

      function smoothScrollBy(delta) {
        return new Promise(res => {
          const start = container.scrollLeft;
          const target = start + delta;
          container.scrollTo({
            left: target,
            behavior: "smooth"
          });
          setTimeout(res, DURATION);
        });
      }

      document.getElementById("reviewNextBtn").addEventListener("click", () => smoothScrollBy(STEP));
      document.getElementById("reviewPrevBtn").addEventListener("click", () => smoothScrollBy(-STEP));
    });

    document.addEventListener("DOMContentLoaded", () => {
      const container = document.getElementById("scrollWrapper");
      const STEP = 260;
      const DURATION = 400;

      function smoothScrollBy(delta) {
        return new Promise(res => {
          const start = container.scrollLeft;
          const target = start + delta;
          container.scrollTo({
            left: target,
            behavior: "smooth"
          });
          setTimeout(res, DURATION);
        });
      }

      document.getElementById("nextBtn").addEventListener("click", () => smoothScrollBy(STEP));
      document.getElementById("prevBtn").addEventListener("click", () => smoothScrollBy(-STEP));
    });

    const AddToWishList = (productid) => {
      const formData = new FormData();
      formData.append('action', "ADD")
      formData.append('productid', productid)
      fetch('./AJAX/WishList_ajax.php', {
        method: "POST",
        body: formData,
        credentials: "include"
      }).then(res => res.json()).then((res) => {
        if (res.status == 'unauthorized') {
          showToast(res.message, "denger")
        }
        if (res.status == 'added') {
          showToast("Product Add To WishList Successfully", "success")
          const WishList = parseInt(document.getElementById('wishListLength').textContent) || 0;
          const updatedWishList = WishList + 1;
          document.getElementById('wishListLength').classList.remove('hidden')
          document.getElementById('wishListLength').textContent = updatedWishList;

        }
        if (res.status == 'exists') {
          showToast("Product Already Exist in Cart", "warning")
        }
      })
    }

     const AddToCart = (productid) => {
      const formData = new FormData();
      formData.append('action', "ADD")
      formData.append('productid', productid)
      fetch('./AJAX/Cart_ajax.php', {
        method: "POST",
        body: formData,
        credentials: "include"
      }).then(res => res.json()).then((res) => {
        if (res.status == 'unauthorized') {
          showToast(res.message, "denger")
        }
        if (res.status == 'added') {
          showToast("Product Add To Cart Successfully", "success")
          const Cart = parseInt(document.getElementById('CartLength').textContent) || 0;
          const UpdatedCart = Cart + 1;
          document.getElementById('CartLength').classList.remove('hidden')
          document.getElementById('CartLength').textContent = UpdatedCart;

        }
        if (res.status == 'exists') {
          showToast("Product Already Exist in Cart", "warning")
        }
      })
    }
  </script>
</body>

</html>