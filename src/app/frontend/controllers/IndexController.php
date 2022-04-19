<?php

namespace Multiple\Frontend\Controllers;


use Phalcon\Mvc\Controller;


class IndexController extends Controller
{
    public function indexAction()
    {
        $this->response->redirect('../admin/login');
        
        
        // return '<h1>Hello World!</h1>';
    }
}