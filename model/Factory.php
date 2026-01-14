<?php

require_once __DIR__ . '/Model.php';

class Factory extends Model {
    protected $table = 'factories';
    protected $primaryKey = 'factory_id';
    
    /**
     * Get factory by name
     * @param string $name
     * @return array|false
     */
    public function getByName($name) {
        return $this->findOneBy('name', $name);
    }
}
