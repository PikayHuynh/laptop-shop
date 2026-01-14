<?php

require_once __DIR__ . '/Model.php';

class Category extends Model {
    protected $table = 'categories';
    protected $primaryKey = 'category_id';
    
    /**
     * Get category by name
     * @param string $name
     * @return array|false
     */
    public function getByName($name) {
        return $this->findOneBy('name', $name);
    }
}
