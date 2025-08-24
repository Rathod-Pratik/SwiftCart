<img width="2766" height="3014" alt="ProductDetails" src="https://github.com/user-attachments/assets/d704b967-992f-4f93-b3f0-7981605371f1" /><img width="2766" height="2546" alt="CompleteOrder" src="https://github.com/user-attachments/assets/69b7221d-853d-422c-9e66-8090048e8c2e" /># SwiftCart

SwiftCart is a modern, user-friendly e-commerce marketplace designed to connect buyers and vendors across India. The platform offers seamless shopping, vendor management, and secure transactions with a responsive UI.

---

## Features
- User authentication (customer & vendor)
- Product management (add, edit, delete, view)
- Shopping cart & wishlist
- Order management & tracking
- Vendor dashboard with analytics
- Responsive UI (Tailwind CSS)
- AJAX for seamless user experience
- Secure database integration (MySQL)
- FAQ & support section

---

## Technologies Used
- **Languages:** PHP, HTML, CSS, JavaScript
- **Frameworks/Libraries:** Tailwind CSS, Bootstrap (optional)
- **Database:** MySQL
- **Other:** AJAX, AOS (Animate On Scroll)

---

## Project Structure
```
SwiftCart/
├── README.md
├── Componenets/
│   ├── Footer.php
│   ├── Header.php
│   └── Navbar.php
├── Image/
│   ├── Banking.png
│   ├── Login.webp
│   └── SignUp.webp
├── Page/
│   ├── Auth/
│   │   ├── Login.php
│   │   └── SignUp.php
│   └── ...
```

---

## Setup Instructions
1. Clone the repository:
   ```
   git clone https://github.com/Rathod-Pratik/SwiftCart.git
   ```
2. Import the database from `/db/SwiftCart.sql` into your MySQL server.
3. Configure database credentials and environment variables in `.env`:
   - Set your MySQL host, username, password, and database name.
   - Example:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', 'your_password');
     define('DB_NAME', 'SwiftCart');
     ```
4. The project includes a function to automatically create required tables if they do not exist. On first run, the backend will check and create missing tables for you.
5. Start your local server (e.g., XAMPP) and navigate to the project folder.
6. Access the website via `http://localhost/SwiftCart`.

---

## Screenshots


<img width="2766" height="2094" alt="Login" src="https://github.com/user-attachments/assets/a95130eb-8c7b-415a-8980-979ccf1ad12b" />
<img width="2766" height="2238" alt="signUp" src="https://github.com/user-attachments/assets/2a5712d7-4477-48c5-bd07-fcc6765da3a7" />
<img width="2766" height="11258" alt="home" src="https://github.com/user-attachments/assets/c2645377-f74e-4d99-8b07-965fc1c44353" />
<img width="2766" height="5292" alt="Product" src="https://github.com/user-attachments/assets/4a310ecc-90a1-47dd-8c6f-f22570684c6e" />
<img width="2766" height="3014" alt="ProductDetails" src="https://github.com/user-attachments/assets/c2e4d341-20e2-47a7-a818-c860518867c9" />
<img width="2766" height="5960" alt="About" src="https://github.com/user-attachments/assets/ad4b17ab-ae03-49f9-837e-80658a3cc273" />
<img width="2766" height="3096" alt="ContactUs" src="https://github.com/user-attachments/assets/f9797174-6912-4297-974c-0494ec8a5760" />
<img width="2766" height="2414" alt="WishList" src="https://github.com/user-attachments/assets/dd149654-9d05-4ecb-8a1b-7d71bba91057" />
<img width="2766" height="1962" alt="Cart" src="https://github.com/user-attachments/assets/fa22b954-96e0-4f83-86af-c1e9a4d15930" />
<img width="2766" height="2406" alt="CheckOut" src="https://github.com/user-attachments/assets/f11a2ec6-4753-4e1b-b310-5c7642e9ea48" />
<img width="2766" height="2546" alt="CompleteOrder" src="https://github.com/user-attachments/assets/53a3b4d3-cc45-4bf1-a0f4-bdd164da7027" />
<img width="2766" height="1598" alt="Profile" src="https://github.com/user-attachments/assets/95001f15-4cb9-4c71-9453-26f3c2583dff" />
<img width="2766" height="3858" alt="Order" src="https://github.com/user-attachments/assets/3c070bc3-7959-4cd2-9f89-05c05e2e7538" />
<img width="2766" height="1462" alt="AdminDashBoard" src="https://github.com/user-attachments/assets/16dbfd74-3f6c-4618-9960-29b4e2a2d2e5" />
<img width="2862" height="1942" alt="AdminCategory" src="https://github.com/user-attachments/assets/99996796-1c99-43a3-9cb5-12fb31adbe0f" />
<img width="2862" height="3530" alt="AdminProduct" src="https://github.com/user-attachments/assets/9c162616-b6b4-4976-902c-27af1e43d8d1" />
<img width="2862" height="1884" alt="AdminOrder" src="https://github.com/user-attachments/assets/9f45e682-3bb0-49ed-96ae-2b975bfb7db2" />
<img width="2856" height="1390" alt="AdminUser" src="https://github.com/user-attachments/assets/cd35f4a6-0c39-4154-9850-9b69e8fa1689" />
<img width="3428" height="1390" alt="AdminVender" src="https://github.com/user-attachments/assets/ca3f4477-fe60-450a-924a-edf5659dcd18" />
<img width="2726" height="1390" alt="AdminRating" src="https://github.com/user-attachments/assets/3c23c8c5-c9f6-477c-95df-f092d86c06b5" />
<img width="2698" height="1390" alt="AdminContact" src="https://github.com/user-attachments/assets/2ba9a8c3-2235-4b30-b9f1-9598b85838f4" />
<img width="2698" height="1446" alt="VenderDashBoard" src="https://github.com/user-attachments/assets/3eb6d0b3-b35d-4251-872c-86eda0211695" />
<img width="2862" height="3060" alt="VenderProduct" src="https://github.com/user-attachments/assets/e94c7e9c-918a-4c0f-b015-e96f1ae88f81" />
<img width="2862" height="2892" alt="VenderOrder" src="https://github.com/user-attachments/assets/ece24b7d-17ee-432c-bf80-428f89378ea7" />
<img width="2862" height="1418" alt="VenderRating" src="https://github.com/user-attachments/assets/aa2d4be4-dc44-4b4d-890a-6881a1f419c4" />
<img width="2862" height="1418" alt="VenderQuery" src="https://github.com/user-attachments/assets/8f5d3d40-d8b2-4b75-98a7-e65299ea7cde" />
<img width="2862" height="1418" alt="VenderProfile" src="https://github.com/user-attachments/assets/c86eb693-352b-4010-b959-ed0276ddfd08" />



## Limitations
- Scalability for large catalogs may require optimization
- Basic security; advanced features like 2FA not included
- Payment gateway integration is simulated
- Limited analytics and reporting
- No live chat or ticketing system
- Single region/currency support

---

## License
This project is for educational purposes.
