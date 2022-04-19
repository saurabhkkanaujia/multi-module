<?php

namespace Multiple\Frontend\Controllers;

use Multiple\Admin\Components\Myescaper;
use Phalcon\Mvc\Controller;
use MongoDB\BSON\ObjectID;

class ProductsController extends Controller{

    public function listProductsAction()
    {

        if ($this->request->getPost('search')) {

            $search = $this->request->getPost('search');
            $result = $this->mongo->products->find(
                [
                    "product_name" => $search
                ]
            );
            $this->view->data = $result;
        } else {
            $result = $this->mongo->products->find(
                []
            );
            $this->view->data = $result;
        }
    }

    
}