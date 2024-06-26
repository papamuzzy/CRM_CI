<?php

namespace App\Libraries;

use CodeIgniter\Cookie\Cookie;
use Config\Cookie as CookieConfig;
use DateTime;

class User {
    private \CodeIgniter\Session\Session $session;
    private $cookie;
    private int $logId;

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

    protected $logModel;

    /**
     * @throws \ReflectionException
     */
    public function __construct() {
        $this->session = \Config\Services::session();

        $this->model = model('UserClientModel');
        $this->privateModel = model('UserClientPrivateModel');
        $this->logModel = model('UserLogModel');

        helper('cookie');

        $this->cookie = config(CookieConfig::class);

        $this->checkSession();

        $this->logId = $this->logModel->createLog(['errors' => [$this->sessionError],]);
        $this->session->setFlashdata('parent_id', $this->logId);
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
        $this->setSession();
        $this->logIn();
    }

    public function setSession(): void {
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

        $this->setSession();
        if (!empty($this->logId)) {
            $this->logModel->updateUserId($this->logId, $user_id);
        }
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

    public function logIn() {
        $data = [
            'logged'        => true,
            'last_activity' => time(),
        ];
        $this->privateModel->update($this->id, $data);
    }

    public function logout(): void {
        $data = [
            'logged'        => false,
        ];
        $this->privateModel->update($this->id, $data);

        $this->session->destroy();
        delete_cookie('token');
        $this->id = 0;
    }

    public function addErrorToLog(string $error): void {
        $this->logModel->addError($this->logId, $error);
    }
}