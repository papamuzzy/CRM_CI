<?php

namespace App\Models;

use CodeIgniter\Model;

class TestModel extends Model {
    protected $table      = 'users'; // Укажите название вашей таблицы
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['first_name', 'email']; // Укажите поля вашей таблицы

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}