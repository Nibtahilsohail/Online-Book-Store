**BookVerse - Your Literary Adventure Awaits 📚✨**

Welcome to BookVerse, a captivating online bookstore built to spark your love for reading! Crafted with PHP and MySQL, this web application offers a seamless, mobile-friendly experience for browsing, purchasing, and managing books. Whether you're a book enthusiast or an admin managing inventory, BookVerse delivers a delightful journey through its vibrant catalog and intuitive interface.

**Overview 🌟
**
BookVerse is a dynamic web-based bookstore that brings the joy of reading to your fingertips. Explore a rich book catalog, filter by genre or search for your next favorite story, and fill your shopping cart with ease. Secure user authentication ensures safe login and registration, while an order history page tracks your literary adventures. Admins can manage books and authors effortlessly via a dedicated panel. Powered by vanilla JavaScript and elegant CSS with Google Fonts, BookVerse offers a responsive design that shines on any device, from desktops to smartphones.

**Functionalities 🛠️**

User Management: Securely register and log in to unlock personalized cart and order features.

Book Browsing: Dive into a dynamic catalog with filters for genre, search by title or author, and sorting options.

Cart Operations: Add, update, or remove books with ease, with checkout exclusive to logged-in users.

Order Tracking: View detailed order history, including book titles, quantities, and totals.

Admin Controls: Manage books and authors with tools to add, update, or delete entries.

Responsive Interface: Enjoy a mobile-optimized layout for seamless use across all devices.

**Tools 🧰**

Backend: PHP for robust server-side logic, paired with MySQL and PDO for secure database interactions. 💻

Frontend: HTML, custom CSS with Google Fonts (Poppins), and vanilla JavaScript for lively, lightweight features. 🎨

Database: MySQL for storing books, authors, customers, orders, and order details with precision. 🗃️

Web Server: Compatible with Apache or Nginx, with Apache preferred for .htaccess support. 🌐

No Dependencies: Built without frameworks for a fast, streamlined experience. 🚀


**Features 🌈**

Secure Authentication: Register and log in to access personalized cart and order functionalities. 🔒

Dynamic Book Catalog: Browse books with smart filters for search, genre, and sorting by title or price. 📖

Shopping Cart: Easily add, update, or remove books, with checkout for logged-in users. 🛒

Order Management: View detailed order history with book titles, quantities, and totals. 📜

Admin Panel: Manage books and authors with intuitive tools for adding, updating, or deleting entries. ⚙️

Responsive Design: Enjoy a stunning, mobile-friendly layout optimized for all screen sizes. 📱

**File Descriptions 📂**

checkout.php: Processes checkout for logged-in users, validating stock, creating orders, and updating quantities using MySQL transactions. Returns JSON responses for success or errors. 🛍️

create_tables.sql: Sets up the bookverse MySQL database with tables for authors, books, customers, orders, and order details, including seed data for initial content. 📋

db.php: Configures the PDO-based MySQL connection with secure settings for host, database, user, and password. 🔗

index.php: The main homepage, showcasing the book catalog with filtering, sorting, and cart/receipt modals, powered by dynamic JavaScript. 🏠

login.php: Provides a login form to authenticate users by email and password, with session management and error handling. 🔐

logout.php: Clears the user session and redirects to the homepage for secure logout. 🚪

orders.php: Displays order history for logged-in users, listing book titles, quantities, and totals in a clean, card-based layout. 📦

register.php: Manages user registration with a form for name, email, and password, hashing passwords and handling errors like duplicate emails. ✍️

style.css: Defines responsive, visually appealing CSS with Google Fonts (Poppins), CSS Grid, and media queries for styling navigation, book cards, modals, and forms. 🎨

**
Installation Steps**

Is project ko GitHub se download ya clone karein.

Is folder ko XAMPP ke htdocs folder mein paste karein.

XAMPP Control Panel open karein aur Apache & MySQL start karein.

Browser mein phpMyAdmin open karein aur bookverse naam ka naya database banayein.

sql/schema.sql file ko import karein us database mein.

Browser mein yeh URL open karein: http://localhost/bookverse/


**Set Up the Database:**

Create a MySQL database named bookverse.

Import the create_tables.sql file:mysql -u root -p bookverse < create_tables.sql


**Usage 📖**

Browse Books: Head to index.php to explore the catalog, using search, genre filters, or sort options to find your next read. 📚

Manage Your Cart: Click "Add to Cart," then open the cart (🛒) to view, update quantities, or check out (requires login). 🛍️

Place Orders: Log in, add books, and click "Checkout" then "Place Order" to complete your purchase. ✅

View Orders: Check past orders on orders.php when logged in to see titles, quantities, and totals. 📜

Admin Tasks: Use the admin panel (if implemented) to manage books and authors with ease. ⚙️

**Admin Login Details: Username: admin Password: admin123 (Aap yeh credentials database ke zariye change bhi kar sakte hain)**

**Developer Name: Nibtahil Sohail 

github : 
**
License: Yeh project sirf educational purposes ke liye banaya gaya hai. Aap isay freely modify aur use kar sakte hain.
