# SwiftCart

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



---

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