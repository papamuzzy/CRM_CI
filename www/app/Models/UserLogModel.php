<?php

namespace App\Models;

use CodeIgniter\Model;

class UserLogModel extends Model {
    protected $table = 'user_log';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'parent_id',
        'session_id',
        'user_id',
        'ip',
        'user_agent',
        'errors',
        'data',
    ];
    protected array $casts = [
        'parent_id' => 'int',
        'user_id'   => 'int',
        'errors'    => 'array',
        'data'      => 'array',
    ];

    protected $useTimestamps = true;

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    /**
     * @throws \ReflectionException
     */
    public function createLog(array $data): int {
        $session = service('session');

        $common_data = [
            'SERVER'           => $_SERVER,
            'GET'              => $_GET,
            'POST'             => $_POST,
            'COOKIE'           => $_COOKIE,
            'session'          => $session->get(),
            'sessionFlashData' => $session->getFlashData(),
        ];

        $data = [
            'parent_id'  => $session->getFlashData('parent_id') ?? 0,
            'session_id' => $session->session_id,
            'user_id'    => $session->get('user_id') ?? 0,
            'ip'         => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'errors'     => $data['errors'] ?? [],
            'data'       => !empty($data['data']) ? array_merge($common_data, $data['data']) : $common_data,
        ];
        return $this->insert($data);
    }

    /**
     * @throws \ReflectionException
     */
    public function addError(int $log_id, string $error): void {
        $log = $this->find($log_id);
        $errors = $log['errors'];
        $errors[] = $error;

        $this->update($log_id, ['errors' => $errors]);
    }

    /**
     * @throws \ReflectionException
     */
    public function updateUserId(int $log_id, $user_id): void {
        $this->update($log_id, ['user_id' => $user_id]);
    }
}