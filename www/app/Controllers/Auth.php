<?php namespace App\Controllers;

use App\Libraries\UniqueChecker;
use CodeIgniter\Controller;
use App\Models\User;

class Auth extends Controller {
    /**
     * @var \App\Models\User
     */
    private User $userModel;
    private UniqueChecker $uniqueChecker;

    public function __construct() {
        // Загружаем модель пользователя
        $this->userModel = new User();
        $this->uniqueChecker = new UniqueChecker();
    }

    public function register(): string {
        helper(['form']);

        $template = "register_account";

        $data_page = [
            'title'       => lang('Loginauth.titleLoginRegister') . " - " . lang('Loginauth.defSignTitle'),
            'description' => lang('Loginauth.descriptionLoginRegister') . " - " . lang('Loginauth.defSignTitle'),
            'form_anchor' => base_url('auth/register'),
        ];

        if (!$this->request->is('post')) {
            return view('loginauth/templates/' . $template, $data_page);
        }

        $rules = [
            'сompany_name' => [
                'rules'  => 'required|min_length[2]|max_length[100]',
                'errors' => [
                    'required'   => 'Company name is required',
                    'min_length' => 'Company name must be at least 2 characters long',
                    'max_length' => 'Company name cannot exceed 100 characters',
                ],
            ],
            'first_name'   => [
                'rules'  => 'required|alpha|min_length[3]|max_length[12]',
                'errors' => [
                    'required'   => 'First name is required',
                    'alpha'      => 'First name must contain only alphabetic characters',
                    'min_length' => 'First name must be at least 3 characters long',
                    'max_length' => 'First name cannot exceed 12 characters',
                ],
            ],
            'last_name'    => [
                'rules'  => 'required|alpha|min_length[3]|max_length[12]',
                'errors' => [
                    'required'   => 'Last name is required',
                    'alpha'      => 'Last name must contain only alphabetic characters',
                    'min_length' => 'Last name must be at least 3 characters long',
                    'max_length' => 'Last name cannot exceed 12 characters',
                ],
            ],
            'email'        => [
                //'rules'  => 'required|valid_email|is_unique[users.email]',
                'rules'  => 'required|valid_email',
                'errors' => [
                    'required'    => 'Email is required',
                    'valid_email' => 'Email must be valid',
                    //'is_unique'   => 'Email already exists',
                ],

            ],
        ];

        if (!$this->validate($rules)) {
            // Если валидация не прошла, возвращаемся к форме с ошибками

            $data_page['validation'] = $this->validator;
            $data_page['form_data'] = $this->request->getPost();

            return view('loginauth/templates/' . $template, $data_page);
        } else {
            $res = $this->uniqueChecker->checkUnique($this->userModel, $this->request->getPost(), ['email',]);
            if (!$res['email']) {
                $data_page['error'] = 'Email already exists';
                $data_page['form_data'] = $this->request->getPost();

                return view('loginauth/templates/' . $template, $data_page);
            }

            $template = "check_email";

            $verification_code = md5(rand());
            $verification_expires = date('Y-m-d H:i:s', strtotime('+1 hour')); // Срок действия 1 час

            $data = [
                'company'       => $this->request->getPost('сompany_name'),
                'first_name'    => $this->request->getPost('first_name'),
                'last_name'     => $this->request->getPost('last_name'),
                'email'         => $this->request->getPost('email'),
                'verified'      => 0,
                'token'         => $verification_code,
                'token_type'    => 'register',
                'token_expires' => $verification_expires,
            ];

            $this->userModel->save($data);
            $this->_send_verification_email($data['email'], $data['token']);

            return view('loginauth/templates/' . $template, $data_page);
            //return view('check_email');
        }
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
        $user = $this->userModel->where('token', $verification_code)->first();

        if ($user) {
            $current_time = date('Y-m-d H:i:s');
            if ($current_time < $user['token_expires']) {
                $this->userModel->update($user['id'], ['verified' => 1]);
                $data_page['form_data'] = [
                    'first_name'        => $user['first_name'],
                    'last_name'         => $user['last_name'],
                    'email'             => $user['email'],
                    'verification_code' => $verification_code,
                ];
                return view('complete_registration', $data_page);
            } else {
                return view('loginauth/templates/register_account', ['error' => 'Verification code has expired. Please register again.']);
            }
        } else {
            echo 'Invalid verification code';
        }
    }

    public function complete_registration_post(): string {
        $verification_code = $this->request->getPost('verification_code');
        $user = $this->userModel->where('token', $verification_code)->first();

        if ($user && strtotime($user['token_expires']) > time()) {
            $rules = [
                'phone'            => [
                    'rules'  => 'required|min_length[12]|max_length[15]|numeric',
                    'errors' => [
                        'required'   => 'Phone is required',
                        'min_length' => 'Phone must be at least 12 characters long',
                        'max_length' => 'Phone cannot exceed 15 characters',
                        'numeric'    => 'Phone must be a number',
                    ],
                ],
                'password'         => [
                    'rules'  => 'required|min_length[8]|max_length[12]',
                    'errors' => [
                        'required'   => 'Password is required',
                        'min_length' => 'Password must be at least 8 characters long',
                        'max_length' => 'Password cannot exceed 12 characters',
                    ],
                ],
                'confirm_password' => [
                    'rules'  => 'required|min_length[8]|max_length[12]|matches[password]',
                    'errors' => [
                        'required'   => 'Confirm Password is required',
                        'min_length' => 'Confirm Password must be at least 8 characters long',
                        'max_length' => 'Confirm Password cannot exceed 12 characters',
                        'matches'    => 'Confirm Password does not match with Password',
                    ],
                ],
            ];

            if (!$this->validate($rules)) {
                $data_page['validation'] = $this->validator;
                $data_page['form_data'] = $this->request->getPost();

                return view('complete_registration', $data_page);
            } else {
                $res = $this->uniqueChecker->checkUnique($this->userModel, $this->request->getPost(), ['phone',]);
                if (!$res['phone']) {
                    $data_page['error'] = 'Phone number already exists';
                    $data_page['form_data'] = $this->request->getPost();

                    return view('complete_registration', $data_page);
                }

                $this->userModel->update($user['id'], [
                    'phone'         => $this->request->getPost('phone'),
                    'password'      => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                    'verified'      => 1,
                    'token'         => null,
                    'token_type'    => null,
                    'token_expires' => null,
                ]);

                return view('welcome');
            }
        } else {
            return view('loginauth/templates/register_account', ['error' => 'Invalid or expired verification code.']);
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
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->where('email', $email)->first();

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
        $email = $this->request->getPost('email');
        $user = $this->userModel->where('email', $email)->first();

        if ($user) {
            $verification_code = md5(rand());
            $verification_expires = date('Y-m-d H:i:s', strtotime('+1 hour')); // Срок действия 1 час

            $this->userModel->update($user['id'], [
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
        $user = $this->userModel->where('token', $verification_code)->first();

        if ($user && strtotime($user['token_expires']) > time()) {
            return view('reset_password', ['email' => $user['email'], 'verification_code' => $verification_code]);
        } else {
            return view('request_password_reset', ['error' => 'Invalid or expired reset code.']);
        }
    }

    public function reset_password_post(): string {
        $verification_code = $this->request->getPost('verification_code');
        $user = $this->userModel->where('token', $verification_code)->first();

        if ($user && strtotime($user['token_expires']) > time()) {
            $new_password = $this->request->getPost('new_password');
            $confirm_password = $this->request->getPost('confirm_password');

            if ($new_password === $confirm_password) {
                $this->userModel->update($user['id'], [
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

