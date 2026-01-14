<?php

require_once __DIR__ . '/Model.php';

class User extends Model {
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    
    /**
     * Get user by email
     * @param string $email
     * @return array|false
     */
    public function getByEmail($email) {
        return $this->findOneBy('email', $email);
    }
    
    /**
     * Verify user password
     * @param string $email
     * @param string $password
     * @return array|false User data if password matches, false otherwise
     */
    public function verifyPassword($email, $password) {
        $user = $this->getByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
    
    /**
     * Create user with hashed password
     * @param array $data
     * @return int|false
     */
    public function create($data) {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        return parent::create($data);
    }
    
    /**
     * Update user with optional password hashing
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } elseif (isset($data['password']) && empty($data['password'])) {
            unset($data['password']);
        }
        return parent::update($id, $data);
    }
}
