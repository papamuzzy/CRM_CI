<?php

namespace App\Libraries;

use CodeIgniter\Cookie\Cookie;
use Config\Cookie as CookieConfig;
use DateTime;

class User {
    private \CodeIgniter\Session\Session $session;
    private $cookie;

    private ?string $sessionError = null;
    private array $data = [];
    private array $privateData = [];
    private int $id = 0;

    /**
     * @var \App\Models\UserClientModel
     */
    protected $model;

    /**
     * @var \App\Models\UserClientPrivateModel
     */
    protected $privateModel;

    public function __construct() {
        $this->session = \Config\Services::session();

        $this->model = model('UserClientModel');
        $this->privateModel = model('UserClientPrivateModel');

        helper('cookie');

        $this->cookie = config(CookieConfig::class);

        $this->checkSession();
    }

    /**
     * @throws \ReflectionException
     */
    private function checkSession(): void {
        $this->sessionError = null;
        $user_id = $this->session->get('user_id');
        if ($user_id === null) {
            $this->sessionError = 'Session empty.';
            return;
        }

        $this->load($user_id);

        $token = get_cookie('token');
        if ($token === null) {
            $this->sessionError = 'Token empty.';
            return;
        }

        if ($token !== $this->session->get('token')) {
            $this->sessionError = 'Session token invalid.';
            return;
        }

        if (time() > $this->privateData['token_expires']) {
            $this->sessionError = 'Session token expired.';
            return;
        }

        $this->privateModel->updateToken($user_id);
        $this->load($user_id);
        $this->refreshSession();
    }

    private function refreshSession(): void {
        $session_data = [
            'token' => $this->privateData['token'],
        ];
        $this->session->set($session_data);

        $cookie = (new Cookie(
            'token',
            $this->privateData['token'],
            [
                'expires'  => new DateTime('+2 hours'),
                'path'     => $this->cookie->path,
                'domain'   => $this->cookie->domain,
                'secure'   => $this->cookie->secure,
                'httponly' => true, // for security
                'samesite' => $this->cookie->samesite ?? Cookie::SAMESITE_LAX,
                'raw'      => $this->cookie->raw ?? false,
            ]
        ))->withPrefix('');
        set_cookie($cookie);
    }

    public function createSession(): void {
        $session_data = [
            'user_id'    => $this->id,
            'token'      => $this->privateData['token'],
            'user_group' => 'client',
        ];
        $this->session->set($session_data);

        $cookie = (new Cookie(
            'token',
            $this->privateData['token'],
            [
                'expires'  => new DateTime('+2 hours'),
                'path'     => $this->cookie->path,
                'domain'   => $this->cookie->domain,
                'secure'   => $this->cookie->secure,
                'httponly' => true, // for security
                'samesite' => $this->cookie->samesite ?? Cookie::SAMESITE_LAX,
                'raw'      => $this->cookie->raw ?? false,
            ]
        ))->withPrefix('');
        set_cookie($cookie);
    }

    public function load(int $user_id): void {
        $this->id = $user_id;
        $this->privateData = $this->privateModel->find($user_id);
        $this->data = $this->model->find($user_id);

        $this->createSession();
    }

    public function isValid(): bool {
        return !empty($this->id) && empty($this->sessionError);
    }

    public function getId(): int {
        return $this->id;
    }

    public function getFullData(): array {
        return array_merge($this->data, $this->privateData);
    }

    public function getError(): ?string {
        return $this->sessionError;
    }

    public function logout(): void {
        $this->session->destroy();
        delete_cookie('token');
        $this->id = 0;
    }
}