<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\User;

class Auth extends Controller {
    public function register(): string {
        return view('register');
    }

    /**
     * @throws \ReflectionException
     */
    public function register_post(): string {
        $userModel = new User();

        $email = $this->request->getPost('email');
        $existingUser = $userModel->where('email', $email)->first();

        if ($existingUser) {
            return view('register', ['error' => 'Email already exists.']);
        }

        $verification_code = md5(rand());
        $verification_expires = date('Y-m-d H:i:s', strtotime('+1 hour')); // Срок действия 1 час

        $data = [
            'company'       => $this->request->getPost('company'),
            'first_name'    => $this->request->getPost('first_name'),
            'last_name'     => $this->request->getPost('last_name'),
            'email'         => $this->request->getPost('email'),
            'verified'      => 0,
            'token'         => $verification_code,
            'token_type'    => 'register',
            'token_expires' => $verification_expires,
        ];

        $userModel->save($data);
        $this->_send_verification_email($data['email'], $data['token']);

        return view('check_email');
    }

    private function _send_verification_email($email, $verification_code): void {
        $emailService = \Config\Services::email();

        $emailService->setFrom('papamuzzy@gmail.com', 'CI CRM');
        $emailService->setTo($email);

        $emailService->setSubject('Email Verification');
        $message = 'Please click this link to verify your email: ' . base_url() . '/auth/verify/' . $verification_code;
        $emailService->setMessage($message);

        $emailService->send();
    }

    /**
     * @throws \ReflectionException
     */
    public function verify($verification_code) {
        $userModel = new User();
        $user = $userModel->where('token', $verification_code)->first();

        if ($user) {
            $current_time = date('Y-m-d H:i:s');
            if ($current_time < $user['token_expires']) {
                $userModel->update($user['id'], ['verified' => 1]);
                return view('complete_registration', [
                    'first_name'        => $user['first_name'],
                    'last_name'         => $user['last_name'],
                    'email'             => $user['email'],
                    'verification_code' => $verification_code,
                ]);
            } else {
                return view('register', ['error' => 'Verification code has expired. Please register again.']);
            }
        } else {
            echo 'Invalid verification code';
        }
    }

    public function complete_registration_post(): string {
        $userModel = new User();
        $verification_code = $this->request->getPost('verification_code');
        $user = $userModel->where('token', $verification_code)->first();

        if ($user && strtotime($user['token_expires']) > time()) {
            $phone = $this->request->getPost('phone');
            $password = $this->request->getPost('password');
            $confirm_password = $this->request->getPost('confirm_password');

            if ($password === $confirm_password) {
                $userModel->update($user['id'], [
                    'phone'         => $phone,
                    'password'      => password_hash($password, PASSWORD_DEFAULT),
                    'verified'      => 1,
                    'token'         => null,
                    'token_type'    => null,
                    'token_expires' => null,
                ]);

                return view('welcome');
            } else {
                return view('complete_registration', [
                    'first_name'        => $user['first_name'],
                    'last_name'         => $user['last_name'],
                    'email'             => $user['email'],
                    'verification_code' => $verification_code,
                    'error'             => 'Passwords do not match.',
                ]);
            }
        } else {
            return view('register', ['error' => 'Invalid or expired verification code.']);
        }
    }

    public function payment(): string {
        // Обработка данных оплаты
        return view('welcome');
    }

    public function login(): string {
        return view('login');
    }

    public function login_post(): string {
        $userModel = new User();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $userModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password']) && $user['verified']) {
            return view('welcome');
        } else {
            return view('login', ['error' => 'Invalid credentials or email not verified.']);
        }
    }

    public function request_password_reset(): string {
        return view('request_password_reset');
    }

    public function send_password_reset_email(): string {
        $userModel = new User();
        $email = $this->request->getPost('email');
        $user = $userModel->where('email', $email)->first();

        if ($user) {
            $verification_code = md5(rand());
            $verification_expires = date('Y-m-d H:i:s', strtotime('+1 hour')); // Срок действия 1 час

            $userModel->update($user['id'], [
                'token'         => $verification_code,
                'token_type'    => 'reset',
                'token_expires' => $verification_expires,
            ]);

            $emailService = \Config\Services::email();
            $emailService->setFrom('papamuzzy@gmail.com', 'CI CRM');
            $emailService->setTo($email);

            $emailService->setSubject('Password Reset');
            $message = 'Please click this link to reset your password: ' . base_url() . '/auth/reset_password/' . $verification_code;
            $emailService->setMessage($message);

            $emailService->send();
        }

        return view('check_email');
    }

    public function reset_password($verification_code): string {
        $userModel = new User();
        $user = $userModel->where('token', $verification_code)->first();

        if ($user && strtotime($user['token_expires']) > time()) {
            return view('reset_password', ['email' => $user['email'], 'verification_code' => $verification_code]);
        } else {
            return view('request_password_reset', ['error' => 'Invalid or expired reset code.']);
        }
    }

    public function reset_password_post(): string {
        $userModel = new User();
        $verification_code = $this->request->getPost('verification_code');
        $user = $userModel->where('token', $verification_code)->first();

        if ($user && strtotime($user['token_expires']) > time()) {
            $new_password = $this->request->getPost('new_password');
            $confirm_password = $this->request->getPost('confirm_password');

            if ($new_password === $confirm_password) {
                $userModel->update($user['id'], [
                    'password'      => password_hash($new_password, PASSWORD_DEFAULT),
                    'token'         => null,
                    'token_type'    => null,
                    'token_expires' => null,
                ]);

                return view('welcome');
            } else {
                return view('reset_password', [
                    'email'             => $user['email'],
                    'verification_code' => $verification_code,
                    'error'             => 'Passwords do not match.',
                ]);
            }
        } else {
            return view('request_password_reset', ['error' => 'Invalid or expired reset code.']);
        }
    }
}

