<?php

namespace app\src\controller;

class OpenController extends Controller
{
    public function index()
    {
        return $this->render('about');
    }
}