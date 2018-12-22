<?php
namespace App\Controllers;

abstract class Controller
{
    /**
     * Response data
     *
     * @param Any    $data Data response
     * @param number $code Code header
     *
     * @return void
     */
    protected function response($data, $code = 200)
    {
        header('Content-Type: application/json');
        http_response_code($code);
        echo json_encode($data);
    }
}
