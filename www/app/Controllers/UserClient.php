<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class UserClient extends UserBaseController {
    private ?string $error = null;
    private array $userData = [];
    private array $userPrivateData = [];
    private int $userId = 0;

    /**
     * @var \App\Models\UserClientModel
     */
    private $userModel;

    /**
     * @var \App\Models\UserClientPrivateModel
     */
    private $userPrivateModel;

    /**
     * @param \CodeIgniter\HTTP\RequestInterface  $request
     * @param \CodeIgniter\HTTP\ResponseInterface $response
     * @param \Psr\Log\LoggerInterface            $logger
     *
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger): void {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        $this->userModel = model('UserClientModel');
        $this->userPrivateModel = model('UserClientPrivateModel');

        $this->checkSession();
    }

    public function checkSession(): void {
        $this->error = null;
        $user_id = $this->session->get('user_id');
        if ($user_id === null) {
            $this->error = 'Session empty.';
            return;
        }

        $this->loadUser($user_id);

        $token = get_cookie('token');
        if ($token === null) {
            $this->error = 'Token empty.';
            return;
        }

        if ($token !== $this->session->get('token')) {
            $this->error = 'Session token invalid.';
            return;
        }

        if (time() > $this->userPrivateData['token_expire']) {
            $this->error = 'Session token expired.';
            return;
        }
    }

    public function loadUser(int $user_id): void {
        $this->userId = $user_id;
        $this->userPrivateData = $this->userPrivateModel->find($user_id);
        $this->userData = $this->userModel->find($user_id);
    }

    public function getId(): int {
        return $this->userId;
    }

    public function getData(): array {
        return $this->userData;
    }

    public function getPrivateData(): array {
        return $this->userPrivateData;
    }

    public function getError(): ?string {
        return $this->error;
    }
}