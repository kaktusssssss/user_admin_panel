INSTALLATION GUIDE

1. DATABASE SETUP
   - Open phpMyAdmin or MySQL client
   - Import file sql/dump.sql into your MySQL server
   - The database "user_admin_db" will be created automatically

2. DATABASE CONNECTION
   - Open file includes/config.php
   - Change database credentials on lines 4-7:
     
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'user_admin_db');
     define('DB_USER', 'root');      // Your MySQL username
     define('DB_PASS', '');          // Your MySQL password

3. FILE LOCATION
   - Copy all files to your web server directory
   - For XAMPP: C:\xampp\htdocs\user_admin\
   - For WAMP:  C:\wamp\www\user_admin\
   - For Linux: /var/www/html/user_admin/

4. ACCESS APPLICATION
   - Open browser and go to: http://localhost/user_admin/login.php
   - Login with: admin / admin123

DEFAULT TEST USERS
- Login: admin / Password: admin123
- Login: john_doe / Password: password123
- Login: jane_smith / Password: password123


MODEL CLASSES
UserManager
  - pdo : PDO
  + __construct(PDO $pdo)
  + getUsers(int $offset, int $limit, string $sort_by, string $order) : array
  + getTotalUsers() : int
  + getUserById(int $id) : array|false
  + createUser(array $data) : bool|string
  + updateUser(int $id, array $data) : bool|string
  + deleteUser(int $id) : bool|string
  + authenticate(string $login, string $password) : bool
  - loginExists(string $login) : bool

SYSTEM DESCRIPTION
The application implements a user administration panel with the following workflow:

1. AUTHENTICATION
   - User accesses login.php
   - System verifies credentials against database using password_verify()
   - Session is created upon successful login
2. USER LIST (index.php)
   - Displays all users with pagination (5 per page)
   - Columns sortable by clicking headers: ID, Login, First Name, Last Name, Birth Date
   - Click once for ASC order (↑), click again for DESC order (↓)
   - Sorting persists with pagination (sorting applied across all pages)
   - Actions: View, Edit, Delete
3. USER OPERATIONS
   - VIEW: Displays all user data including calculated age
   - ADD: Form with validation (required fields, unique login, age 18-100)
   - EDIT: Pre-filled form, optional password change
   - DELETE: Confirmation page before permanent deletion
4. VALIDATION RULES
   - Login must be unique
   - Age must be between 18 and 100 years
   - Birth date cannot be in the future
   - All fields except password (on edit) are required
5. SECURITY
   - All database queries use PDO prepared statements
   - Output is escaped with htmlspecialchars()
   - Passwords are hashed with password_hash()
   - Protected pages check session before rendering
   - Sort paramenters are whitelisted to prevent SQL injection

