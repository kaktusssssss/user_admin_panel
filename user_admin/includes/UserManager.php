<?php
/**
 * UserManager class handles all user-related database operations
 */

class UserManager {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Get all users with pagination
     * @param int $offset
     * @param int $limit
     * @param string $sort_by
     * @param string $order
     * @return array
     */
    public function getUsers($offset = 0, $limit = ITEMS_PER_PAGE, $sort_by = 'id', $order = 'ASC') {
        $allowed_sort = ['id', 'login', 'first_name', 'last_name', 'birth_date'];
        $sort_by = in_array($sort_by, $allowed_sort) ? $sort_by : 'id';
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';
        
        $sql = "SELECT id, login, first_name, last_name, gender, birth_date, created_at 
                FROM users 
                ORDER BY $sort_by $order 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Get total number of users
     * @return int
     */
    public function getTotalUsers() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM users");
        return $stmt->fetchColumn();
    }
    
    /**
     * Get user by ID
     * @param int $id
     * @return array|false
     */
    public function getUserById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Create new user
     * @param array $data
     * @return bool|string Returns true on success, error message on failure
     */
    public function createUser($data) {
        // Check if login exists
        if ($this->loginExists($data['login'])) {
            return "Login '{$data['login']}' already exists";
        }
        
        $sql = "INSERT INTO users (login, password, first_name, last_name, gender, birth_date) 
                VALUES (:login, :password, :first_name, :last_name, :gender, :birth_date)";
        
        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            ':login' => $data['login'],
            ':password' => $hashed_password,
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':gender' => $data['gender'],
            ':birth_date' => $data['birth_date']
        ]);
        
        return $result ? true : "Failed to create user";
    }
    
    /**
     * Update existing user
     * @param int $id
     * @param array $data
     * @return bool|string
     */
    public function updateUser($id, $data) {
        $user = $this->getUserById($id);
        if (!$user) {
            return "User not found";
        }
        
        // Check if login exists for another user
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE login = :login AND id != :id");
        $stmt->execute([':login' => $data['login'], ':id' => $id]);
        if ($stmt->fetch()) {
            return "Login '{$data['login']}' already exists";
        }
        
        $sql = "UPDATE users 
                SET login = :login, 
                    first_name = :first_name, 
                    last_name = :last_name, 
                    gender = :gender, 
                    birth_date = :birth_date";
        
        $params = [
            ':login' => $data['login'],
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':gender' => $data['gender'],
            ':birth_date' => $data['birth_date'],
            ':id' => $id
        ];
        
        // Update password only if provided
        if (!empty($data['password'])) {
            $sql .= ", password = :password";
            $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        $sql .= " WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute($params);
        
        return $result ? true : "Failed to update user";
    }
    
    /**
     * Delete user
     * @param int $id
     * @return bool|string
     */
    public function deleteUser($id) {
        $user = $this->getUserById($id);
        if (!$user) {
            return "User not found";
        }
        
        // Prevent deleting last admin (optional, but good practice)
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM users");
        if ($stmt->fetchColumn() <= 1 && $user['login'] === 'admin') {
            return "Cannot delete the only admin user";
        }
        
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        $result = $stmt->execute([':id' => $id]);
        
        return $result ? true : "Failed to delete user";
    }
    
    /**
     * Check if login exists
     * @param string $login
     * @return bool
     */
    private function loginExists($login) {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE login = :login");
        $stmt->execute([':login' => $login]);
        return $stmt->fetch() !== false;
    }
    
    /**
     * Authenticate user
     * @param string $login
     * @param string $password
     * @return bool
     */
    public function authenticate($login, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE login = :login");
        $stmt->execute([':login' => $login]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_login'] = $user['login'];
            return true;
        }
        
        return false;
    }
}
?>