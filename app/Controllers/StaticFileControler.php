<?php
namespace App\Controllers;

use Config\View;
use App\Controllers\Controller;

class StaticFileControler extends Controller
{

    
    /**
     * Show the index page
     *
     * @return void
     */
    public function index()
    {
        return View::renderStaticFile();
    }
}
