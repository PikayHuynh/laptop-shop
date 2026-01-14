<?php

require_once __DIR__ . '/Model.php';

class Role extends Model {
    protected $table = 'roles';
    protected $primaryKey = 'role_id';
    
    /**
     * Get role by name
     * @param string $name
     * @return array|false
     */
    public function getByName($name) {
        return $this->findOneBy('name', $name);
    }
}
