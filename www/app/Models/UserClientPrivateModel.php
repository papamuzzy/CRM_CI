<?php

namespace App\Models;

use CodeIgniter\Model;

class UserClientPrivateModel extends Model {
    protected $table = 'user_client_private';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'password',
        'verified',
        'token',
        'token_type',
        'token_expires',
        'status',
    ];

    protected $useTimestamps = true;

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
}