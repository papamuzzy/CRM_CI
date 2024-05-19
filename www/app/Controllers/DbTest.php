<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\TestModel;

class DbTest extends Controller
{
    public function index(): void {
        $model = new TestModel();
        $data = $model->findAll();
        echo '<pre>';
        var_export($data);
        echo '</pre>';
    }
}
