<?php

namespace App\Controllers;

use Core\Attribute\Route;
use Core\Controller;

class HomeController extends Controller
{
    #[Route('/')]
    public function index() 
    {
        $this->render('pages/homepage');
    }
}
