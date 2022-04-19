<?php

namespace Multiple\Admin\Controllers;

use Multiple\Admin\Components\Myescaper;
use Phalcon\Mvc\Controller;

class LoginController extends Controller
{
    public function indexAction()
    {
        if ($this->request->isPost()) {
            $postData = $this->request->getPost();
            $obj = new Myescaper();
            $user = $this->mongo->users->findOne(['name'=>$obj->sanitize($postData['name']), 'email'=>$obj->sanitize($postData['email'])]);
            if (count($user)>0) {
                $this->response->redirect('../admin/products-dashboard');
            } else {
                $this->response->redirect('../admin/login');
            }
        }
    }
}
