<?php

namespace Multiple\Admin\Controllers;

use Multiple\Admin\Components\Myescaper;
use Phalcon\Mvc\Controller;
use MongoDB\BSON\ObjectID;

class ProductsController extends Controller{

    public function addProductAction()
    {
        if ($this->request->isPost()) {
            $postData = $this->request->getPost();
            $obj = new Myescaper();

            $this->mongo->products->insertOne(
                [
                    "product_name" => $obj->sanitize($postData['productName']),
                    "category" => $obj->sanitize($postData['category']),
                    "price" => $obj->sanitize($postData['price']),
                    "stock" => $obj->sanitize($postData['stock'])
                ]
            );
            $this->response->redirect('../admin/products-dashboard');
        }
    }

    public function dashboardAction()
    {

        if ($this->request->getPost('search')) {

            // echo "<pre>";
            // print_r($this->request->getPost());die;
            $search = $this->request->getPost('search');
            $result = $this->mongo->products->find(
                [
                    "product_name" => $search
                ]
            );
            $this->view->data = $result;
        } elseif ($this->request->getPost('deleteProduct')) {
            $id = $this->request->getPost('deleteProduct');
            $this->mongo->products->deleteOne([
                "_id" => new ObjectID($id)
            ]);
            $this->response->redirect('../admin/products-dashboard');
        } else {
            $result = $this->mongo->products->find(
                []
            );
            $this->view->data = $result;
        }
    }

    public function editProductAction()
    {
        if ($this->request->has('updateProduct')) {
            $postData = $this->request->getPost();
            $obj = new Myescaper();
            $this->mongo->products->updateOne(['_id' => new ObjectID($obj->sanitize($postData['id']))],
                                    ['$set' => ['product_name' => $obj->sanitize($postData['product_name']), 'category'=> $obj->sanitize($postData['category']), 'price'=>$obj->sanitize($postData['price']), 'stock'=>$obj->sanitize($postData['stock'])]]                            
        );
            $this->response->redirect('../admin/products-dashboard');

        } elseif ($this->request->has("id")) {
            $id = $this->request->get("id");

            $result = $this->mongo->products->findOne([
                "_id" => new ObjectID($id)
            ]);

            $temp = json_decode(json_encode($result), true);

            $this->view->data = $result;
        }

    }
    
}