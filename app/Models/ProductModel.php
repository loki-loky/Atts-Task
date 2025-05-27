<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'description', 'price', 'category_id', 'image'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'name' => 'required|min_length[3]',
        'description' => 'required',
        'price' => 'required|numeric',
        'category_id' => 'required|integer'
    ];

    public function getProductsWithCategory($limit = 10, $offset = 0, $search = '', $order = 'id', $dir = 'DESC')
    {
        $builder = $this->db->table('products');
        $builder->select('products.*, categories.name as category_name');
        $builder->join('categories', 'categories.id = products.category_id');
        
        if (!empty($search)) {
            $builder->groupStart()
                ->like('products.name', $search)
                ->orLike('products.description', $search)
                ->orLike('categories.name', $search)
                ->groupEnd();
        }
        
        $builder->orderBy($order, $dir);
        $builder->limit($limit, $offset);
        
        return $builder->get()->getResult();
    }

    public function countFiltered($search = '')
    {
        $builder = $this->db->table('products');
        $builder->join('categories', 'categories.id = products.category_id');
        
        if (!empty($search)) {
            $builder->groupStart()
                ->like('products.name', $search)
                ->orLike('products.description', $search)
                ->orLike('categories.name', $search)
                ->groupEnd();
        }
        
        return $builder->countAllResults();
    }
} 