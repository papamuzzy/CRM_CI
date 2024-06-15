<?php

namespace App\Models;

use CodeIgniter\Model;

class UserClientModel extends Model {
    protected $table = 'user_client';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;

    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
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
        'status',
    ];

    //protected $dateFormat = 'datetime';
    protected $useTimestamps = true;
    //protected $createdField = 'created_at';
    //protected $updatedField = 'updated_at';
    //protected $deletedField = 'deleted_at';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
}