<?php

namespace App\Models;

use CodeIgniter\Model;

class User extends Model {
    protected $table = 'users'; // Укажите название вашей таблицы
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id',
        'first_name',
        'last_name',
        'company',
        'company_address',
        'website_url',
        'counties_worked',
        'work_type',
        'how_did_you_hear_about_us',
        'email',
        'phone',
        'password',
        'verified',
        'token',
        'token_type',
        'token_expires',
        'created_at',
        'updated_at',
        'deleted_at',
        'status',
    ]; // Укажите поля вашей таблицы

    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
}