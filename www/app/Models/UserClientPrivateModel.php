<?php

namespace App\Models;

use App\Models\Cast\UnixTs;
use CodeIgniter\Model;

class UserClientPrivateModel extends Model {
    protected $table = 'user_client_private';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected bool $allowEmptyInserts = true;

    protected $allowedFields = [
        'email',
        'password',
        'verification_code',
        'verification_code_expires',
        'verifies_last',
        'verifies_count',
        'password_reset_count',
        'password_reset_last',
        'verified',
        'logged',
        'last_activity',
        'token',
        'token_expires',
        'status',
        'permission',
    ];

    // Specify the type for the field
    protected array $casts = [
        'verification_code_expires' => 'unixts',
        'verifies_last'             => 'unixts',
        'verifies_count'            => 'int',
        'password_reset_count'      => 'int',
        'password_reset_last'       => '?unixts',
        'verified'                  => 'int-bool',
        'logged'                    => 'int-bool',
        'last_activity'             => '?unixts',
        'token_expires'             => 'unixts',
        'status'                    => '?int',
    ];

    // Bind the type to the handler
    protected array $castHandlers = [
        'unixts' => UnixTs::class,
    ];

    protected $useTimestamps = true;

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    /**
     * @throws \ReflectionException
     */
    public function createUser(string $email): array|object|null {
        do {
            $verification_code = md5(rand());
            $res = $this->where('verification_code', $verification_code)->first();
        } while ($res !== null);
        do {
            $token = md5(rand());
            $res = $this->where('token', $token)->first();
        } while ($res !== null);

        $data = [
            'email'                     => $email,
            'verification_code'         => $verification_code,
            'verification_code_expires' => strtotime('+1 hour'), // Срок действия 1 час
            'verifies_last'             => time(),
            'verifies_count'            => 1,
            'password_reset_count'      => 0,
            'password_reset_last'       => null,
            'verified'                  => false,
            'token'                     => $token,
            'token_expires'             => strtotime('+2 hour'), // Срок действия 2 часа
            'status'                    => 2,
        ];

        $this->insert($data);

        return $this->where('email', $email)->first();
    }

    /**
     * @throws \ReflectionException
     */
    public function updateVerificationCode(int $user_id): array|object|null {
        $user = $this->find($user_id);
        $verifies_count = $user['verifies_count'] + 1;
        if (time() > $user['verifies_last'] + 24 * 60 * 60) {
            $verifies_count = 1;
        }

        if ($verifies_count > 3) {
            $verification_code = null;
        } else {
            do {
                $verification_code = md5(rand());
                $res = $this->where('verification_code', $verification_code)->first();
            } while ($res !== null);
        }

        $data = [
            'verification_code'         => $verification_code,
            'verification_code_expires' => strtotime('+1 hour'), // Срок действия 1 час
            'verifies_last'             => time(),
            'verifies_count'            => $verifies_count,
            'token_expires'             => strtotime('+2 hour'), // Срок действия 2 часа
        ];

        $this->update($user_id, $data);

        return $this->find($user_id);
    }

    /**
     * @throws \ReflectionException
     */
    public function updatePasswordResetCount(int $user_id): int {
        $user = $this->find($user_id);
        $count = $user['password_reset_count'] + 1;
        if (time() > $user['password_reset_last'] + 10 * 24 * 60 * 60) {
            $count = 1;
        }

        $data = [
            'password_reset_count' => $count,
            'password_reset_last'  => time(),
        ];

        $this->update($user_id, $data);

        return $count;
    }

    /**
     * @throws \ReflectionException
     */
    public function updateToken(int $user_id): array|object|null {
        $data = [
            'token_expires' => strtotime('+2 hour'), // Срок действия 2 часа
        ];

        $this->update($user_id, $data);

        return $this->find($user_id);
    }

    public function isUniqueEmail(string $email): bool {
        $res = $this->where('verified', 1)->where('email', $email)->first();

        return $res === null;
    }

    public function isLogged(int $user_id): bool {
        $user = $this->find($user_id);
        if ($user['logged']) {
            if (time() <= $user['last_activity'] + 2 * 60 * 60) {
                return true;
            }
            $this->update($user_id, ['logged' => false]);
        }

        return false;
    }
}