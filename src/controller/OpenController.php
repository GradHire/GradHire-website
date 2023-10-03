<?php

namespace app\src\controller;

class OpenController extends Controller
{
	public function index(): string
	{
		return $this->render('about');
	}
}