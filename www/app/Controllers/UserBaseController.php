<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Cookie\Cookie;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class UserBaseController extends Controller {
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    protected \CodeIgniter\Session\Session $session;

    protected ?string $sessionError = null;
    protected array $userData = [];
    protected array $userPrivateData = [];
    protected int $userId = 0;

    /**
     * @var \App\Models\UserClientModel
     */
    protected $userModel;

    /**
     * @var \App\Models\UserClientPrivateModel
     */
    protected $userPrivateModel;

    /**
     * @param \CodeIgniter\HTTP\RequestInterface  $request
     * @param \CodeIgniter\HTTP\ResponseInterface $response
     * @param \Psr\Log\LoggerInterface            $logger
     *
     * @return void
     * @throws \ReflectionException
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger): void {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        $this->session = \Config\Services::session();

        $this->userModel = model('UserClientModel');
        $this->userPrivateModel = model('UserClientPrivateModel');

        $this->checkSession();
    }

    /**
     * @throws \ReflectionException
     */
    protected function checkSession(): void {
        $this->sessionError = null;
        $user_id = $this->session->get('user_id');
        if ($user_id === null) {
            $this->sessionError = 'Session empty.';
            return;
        }

        $this->loadUser($user_id);

        $token = get_cookie('token');
        if ($token === null) {
            $this->sessionError = 'Token empty.';
            return;
        }

        if ($token !== $this->session->get('token')) {
            $this->sessionError = 'Session token invalid.';
            return;
        }

        if (time() > $this->userPrivateData['token_expires']) {
            $this->sessionError = 'Session token expired.';
            return;
        }

        $this->userPrivateModel->updateToken($user_id);
        $this->loadUser($user_id);
        $this->refreshSession();
    }

    protected function refreshSession(): void {
        $cookie = new Cookie(
            'token',
            $this->userPrivateData['token'],
            [
                'expires'  => new DateTime('+2 hours'),
                'samesite' => Cookie::SAMESITE_LAX,
            ]
        );
        set_cookie($cookie);

        $session_data = [
            'token' => $this->userPrivateData['token'],
        ];
        $this->session->set($session_data);
    }

    protected function createSession(): void {
        $cookie = new Cookie(
            'token',
            $this->userPrivateData['token'],
            [
                'expires'  => new DateTime('+2 hours'),
                'samesite' => Cookie::SAMESITE_LAX,
            ]
        );
        set_cookie($cookie);

        $session_data = [
            'user_id'    => $this->userId,
            'token'      => $this->userPrivateData['token'],
            'user_group' => 'client',
        ];
        $this->session->set($session_data);
    }

    protected function loadUser(int $user_id): void {
        $this->userId = $user_id;
        $this->userPrivateData = $this->userPrivateModel->find($user_id);
        $this->userData = $this->userModel->find($user_id);
    }

    protected function isUserValid(): bool {
        return !empty($this->userId) && empty($this->sessionError);
    }

    protected function getUserFullData(): array {
        return array_merge($this->userData, $this->userPrivateData);
    }
}
