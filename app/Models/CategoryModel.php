<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'name' => 'required|min_length[2]|is_unique[categories.name,id,{id}]'
    ];

    public function getCategory()
    {
        $builder = $this->db->table('categories');
        $builder->select('id, name');
        
        
        
        return $builder->get()->getResultArray();
    }
} 