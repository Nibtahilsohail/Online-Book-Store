# Online-Book-Store
**BookVerse - Online Book Store 📚**
**Overview**
BookVerse is a vibrant web-based bookstore built with PHP and MySQL, designed to deliver a seamless and engaging experience for book lovers. Explore a dynamic book catalog, filter by genre or search for your next read, and manage purchases with an intuitive shopping cart. Secure user authentication ensures safe login and registration, while an order history page tracks your literary journey. Admins can effortlessly manage books and authors via a dedicated panel. Powered by vanilla JavaScript and stylish CSS with Google Fonts, BookVerse offers a responsive, mobile-friendly interface that shines across all devices.

**Functionalities**
User Management: Register and log in securely to access personalized cart and order features.
Book Browsing: Explore books with dynamic filtering by genre, search by title or author, and sorting options.
Cart Operations: Add, update, or remove books in the cart, with checkout for logged-in users.
Order Tracking: View detailed order history with book titles, quantities, and totals.
Admin Controls: Manage books and authors with tools for adding, updating, or deleting entries.
Responsive Interface: Access a mobile-optimized layout for seamless use on any device.
**Tools**
Backend: PHP for server-side logic, MySQL with PDO for secure database interactions. 💻
Frontend: HTML, custom CSS with Google Fonts (Poppins), and vanilla JavaScript for dynamic features. 🎨
Database: MySQL for storing books, authors, customers, orders, and order details. 🗃️
Web Server: Compatible with Apache or Nginx, with Apache preferred for .htaccess support. 🌐
No Dependencies: Built without frameworks for a lightweight, high-performance experience. 🚀
**Features**
Secure Authentication: Register and log in to unlock personalized cart and order functionalities. 🔒
Dynamic Book Catalog: Browse books with smart filters for search, genre, and sorting by title or price. 📖
Shopping Cart: Easily add, update, or remove books, with checkout for logged-in users. 🛒
Order Management: View detailed order history, including book titles, quantities, and totals. 📜
Admin Panel: Manage books and authors with tools to add, update, or delete entries. ⚙️
Responsive Design: Enjoy a mobile-friendly layout optimized for all screen sizes. 📱
**File Descriptions**
checkout.php: Processes checkout for logged-in users, validating stock, creating orders, and updating quantities using MySQL transactions. Returns JSON responses. 🛍️
create_tables.sql: Sets up the bookverse MySQL database with tables for authors, books, customers, orders, and order details, including seed data. 📋
db.php: Configures the PDO-based MySQL connection for secure database access. 🔗
index.php: The main homepage, displaying the book catalog with filtering, sorting, and cart/receipt modals, powered by JavaScript. 🏠
login.php: Provides a login form to authenticate users, setting session data and handling errors. 🔐
logout.php: Clears the user session and redirects to the homepage for secure logout. 🚪
orders.php: Shows order history for logged-in users with book titles, quantities, and totals in a card-based layout. 📦
register.php: Manages user registration, hashing passwords and handling errors like duplicate emails. ✍️
style.css: Defines responsive CSS with Google Fonts (Poppins), CSS Grid, and media queries for styling across devices. 🎨
