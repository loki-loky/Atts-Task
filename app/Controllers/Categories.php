<?php

namespace App\Controllers;

use App\Models\CategoryModel;

class Categories extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        return view('categories/index');
    }

    public function getCategories()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Invalid request']);
        }

        $draw = $this->request->getGet('draw');
        $start = $this->request->getGet('start');
        $length = $this->request->getGet('length');
        $search = $this->request->getGet('search')['value'];
        $order = $this->request->getGet('order')[0]['column'];
        $dir = $this->request->getGet('order')[0]['dir'];

        $columns = ['id', 'name'];
        $order = $columns[$order];

        $builder = $this->categoryModel->builder();
        $builder->select('*');
        
        if (!empty($search)) {
            $builder->like('name', $search);
        }
        
        $builder->orderBy($order, $dir);
        $builder->limit($length, $start);
        
        $categories = $builder->get()->getResult();
        $total = $this->categoryModel->countAll();
        $filtered = $this->categoryModel->countAllResults();

        return $this->response->setJSON([
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $categories
        ]);
    }

    public function create()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Invalid request']);
        }

        $rules = [
            'name' => 'required|min_length[2]|is_unique[categories.name]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(400)->setJSON(['errors' => $this->validator->getErrors()]);
        }

        $data = [
            'name' => $this->request->getPost('name')
        ];

        if ($this->categoryModel->insert($data)) {
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to create category']);
    }

    public function edit($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Invalid request']);
        }

        $category = $this->categoryModel->find($id);
        if (!$category) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Category not found']);
        }

        return $this->response->setJSON($category);
    }

    public function update($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Invalid request']);
        }

        $rules = [
            'name' => "required|min_length[2]|is_unique[categories.name,id,$id]"
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(400)->setJSON(['errors' => $this->validator->getErrors()]);
        }

        $data = [
            'name' => $this->request->getPost('name')
        ];

        if ($this->categoryModel->update($id, $data)) {
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to update category']);
    }

    public function delete($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Invalid request']);
        }

        $category = $this->categoryModel->find($id);
        if (!$category) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Category not found']);
        }

        if ($this->categoryModel->delete($id)) {
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to delete category']);
    }
} 