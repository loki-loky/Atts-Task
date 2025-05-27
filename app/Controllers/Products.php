<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CategoryModel;

class Products extends BaseController
{
    protected $productModel;
    protected $categoryModel;

    public function __construct()
    {
       
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $data = array();

        $data['categories'] = $this->categoryModel->getCategory();
        return view('products/index', $data);
    }

    public function getProducts()
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

        $columns = ['id', 'name', 'description', 'price', 'category_name'];
        $order = $columns[$order];

        $products = $this->productModel->getProductsWithCategory($length, $start, $search, $order, $dir);
        $total = $this->productModel->countAll();
        $filtered = $this->productModel->countFiltered($search);

        return $this->response->setJSON([
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $products
        ]);
    }

    public function create()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Invalid request']);
        }

        $rules = [
            'name' => 'required|min_length[3]',
            'description' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|integer',
            'image' => 'uploaded[image]|max_size[image,2048]|is_image[image]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(400)->setJSON(['errors' => $this->validator->getErrors()]);
        }

        $image = $this->request->getFile('image');
        $newName = $image->getRandomName();
        $imageName = rand() . '.' . $image->getClientExtension();
        
        $image->move(FCPATH . 'public/uploads/', $newName);

        \Config\Services::image()
                ->withFile(FCPATH . 'public/uploads/' . $newName)
                ->resize(500, 500, true, 'width') 
                ->save(FCPATH . 'public/uploads/' . $imageName);

        if (file_exists(FCPATH . 'public/uploads/' . $newName)) {
            unlink(FCPATH . 'public/uploads/' . $newName);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'category_id' => $this->request->getPost('category_id'),
            'image' => $imageName
        ];
        

        if ($this->productModel->insert($data)) {
            return $this->response->setJSON(['success' => true]);
        }
        

        return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to create product']);
    }

    public function edit($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Invalid request']);
        }

        $product = $this->productModel->find($id);
        if (!$product) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Product not found']);
        }

        return $this->response->setJSON($product);
    }

    public function update($id)
{
    if (!$this->request->isAJAX()) {
        return $this->response->setStatusCode(403)->setJSON(['error' => 'Invalid request']);
    }

    $rules = [
        'name' => 'required|min_length[3]',
        'description' => 'required',
        'price' => 'required|numeric',
        'category_id' => 'required|integer'
    ];

    $image = $this->request->getFile('image');
    if ($image && $image->isValid() && !$image->hasMoved()) {
        $rules['image'] = 'uploaded[image]|max_size[image,2048]|is_image[image]';
    }

    if (!$this->validate($rules)) {
        return $this->response->setStatusCode(400)->setJSON(['errors' => $this->validator->getErrors()]);
    }

    $data = [
        'name' => $this->request->getPost('name'),
        'description' => $this->request->getPost('description'),
        'price' => $this->request->getPost('price'),
        'category_id' => $this->request->getPost('category_id')
    ];

    if ($image && $image->isValid() && !$image->hasMoved()) {
        $newName = $image->getRandomName();
        $uploadPath = FCPATH . 'public/uploads/';
        $image->move($uploadPath, $newName);

        // Resize the image
        \Config\Services::image()
            ->withFile($uploadPath . $newName)
            ->resize(500, 500, true, 'width')
            ->save($uploadPath . $newName);

        $data['image'] = $newName;
    }

    if ($this->productModel->update($id, $data)) {
        return $this->response->setJSON(['success' => true]);
    }

    return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to update product']);
}


    public function delete($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Invalid request']);
        }

        $product = $this->productModel->find($id);
        if (!$product) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Product not found']);
        }

        if ($this->productModel->delete($id)) {
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to delete product']);
    }
} 