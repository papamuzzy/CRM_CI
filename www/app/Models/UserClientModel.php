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
        'company_name',
        'company_address',
        'website_url',
        'counties_worked',
        'work_type',
        'how_did_you_hear_about_us',
        'phone',
    ];
    protected array $casts = [
        'counties_worked' => '?json-array',
        'work_type'       => '?json-array',
    ];

    protected $useTimestamps = true;

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;


    public function isUniquePhone(int $user_id, string $phone): bool {
        $res = $this->where('id !=', $user_id)->where('phone', $phone)->first();

        return $res === null;
    }

}