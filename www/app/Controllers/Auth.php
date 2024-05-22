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
        $get_form_post = false;

        $data_page = [
            'title'       => lang('Loginauth.titleLoginRegister') . " - " . lang('Loginauth.defSignTitle'),
            'description' => lang('Loginauth.descriptionLoginRegister') . " - " . lang('Loginauth.defSignTitle'),
            'form_anchor' => base_url('auth/register'),
        ];

        if (!$this->request->is('post')) {
            return view('loginauth/templates/' . $template, $data_page);
        }

        $validation = \Config\Services::validation();

        $rules = [
            'сompany_name' => [
                'label'  => 'Company Name',
                'rules'  => 'required|string|min_length[2]|max_length[300]',
                'errors' => [
                    'required'   => 'Company name is required',
                    'min_length' => 'Company name must be at least 2 characters long',
                    'max_length' => 'Company name cannot exceed 100 characters',
                ],
            ],
            'first_name'   => [
                'label'  => 'First name',
                'rules'  => 'required|alpha_numeric_space|min_length[3]|max_length[150]',
                'errors' => [
                    'required'   => 'First name is required',
                    'alpha'      => 'First name must contain only alphabetic characters',
                    'min_length' => 'First name must be at least 3 characters long',
                    'max_length' => 'First name cannot exceed 50 characters',
                ],
            ],
            'last_name'    => [
                'label'  => 'Last name',
                'rules'  => 'required|alpha_numeric_space|min_length[3]|max_length[150]',
                'errors' => [
                    'required'   => 'Last name is required',
                    'alpha'      => 'Last name must contain only alphabetic characters',
                    'min_length' => 'Last name must be at least 3 characters long',
                    'max_length' => 'Last name cannot exceed 50 characters',
                ],
            ],
            'email'        => [
                //'rules'  => 'required|valid_email|is_unique[users.email]',
                'label'  => 'Email',
                'rules'  => 'required|valid_email|max_length[250]',
                'errors' => [
                    'required'    => 'Email is required',
                    'valid_email' => 'Email must be valid',
                    //'is_unique'   => 'Email already exists',
                ],
            ],
        ];

        $get_form_post = $this->request->getPost(null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        //$get_form_post = $this->request->getPost();

        //if (!$this->validate($rules)) {
        if (!$this->validateData($get_form_post, $rules)) {
            // Если валидация не прошла, возвращаемся к форме с ошибками

            //$data_page['validation'] = $this->validator;
            $data_page['validation'] = $validation->getErrors();
            $data_page['form_data'] = $get_form_post;

            return view('loginauth/templates/' . $template, $data_page);
        } else {
            $get_form_post = $validation->getValidated();

            if (empty($get_form_post) or !is_array($get_form_post)) {
                $data_page['error'] = 'Data not found, try again.';

                return view('loginauth/templates/' . $template, $data_page);
            }

            //$res = $this->uniqueChecker->checkUnique($this->userModel, $this->request->getPost(), ['email',]);
            $res = $this->uniqueChecker->checkUnique($this->userModel, $get_form_post, ['email',]);
            if (!$res['email']) {
                $data_page['error'] = 'Email already exists';
                $data_page['form_data'] = $get_form_post;

                return view('loginauth/templates/' . $template, $data_page);
            }

            $verification_code = md5(rand());
            $verification_expires = date('Y-m-d H:i:s', strtotime('+1 hour')); // Срок действия 1 час

            /*$data = [
                'company'       => $this->request->getPost('сompany_name'),
                'first_name'    => $this->request->getPost('first_name'),
                'last_name'     => $this->request->getPost('last_name'),
                'email'         => $this->request->getPost('email'),
                'verified'      => 0,
                'token'         => $verification_code,
                'token_type'    => 'register',
                'token_expires' => $verification_expires,
            ];*/

            $data = [
                'company'       => !empty($get_form_post['сompany_name']) ? $get_form_post['сompany_name'] : null,
                'first_name'    => !empty($get_form_post['first_name']) ? $get_form_post['first_name'] : null,
                'last_name'     => !empty($get_form_post['last_name']) ? $get_form_post['last_name'] : null,
                'email'         => !empty($get_form_post['email']) ? $get_form_post['email'] : null,
                'verified'      => 0,
                'token'         => $verification_code,
                'token_type'    => 'register',
                'token_expires' => $verification_expires,
            ];

            $this->userModel->save($data);
            //$this->_send_verification_email($data['email'], $data['token']);

            if ($this->_send_verification_email($get_form_post['first_name'], $get_form_post['email'], $data['token']) === true) {
                $template = "check_email";
                $data_page['success_msg'] = 'Check your email for a verification link';
            } else {
                $data_page['error'] = 'Something strange happened, try again in a few minutes.';
            }

            return view('loginauth/templates/' . $template, $data_page);
            //return view('check_email');
        }
    }

    private function _send_verification_email($first_name, $email, $verification_code): bool {
        if (empty($first_name) or empty($email)) {
            return false;
        }

        $emailService = \Config\Services::email();
        $emailService->clear(true);
        $email_status = false;

        $configemail['SMTPHost'] = 'mail.adm.tools';
        $configemail['SMTPUser'] = 'fbccm@web-dev-project.com';
        $configemail['SMTPPass'] = '6AnSnC3v52';
        $configemail['SMTPPort'] = 465;
        $configemail['SMTPTimeout'] = 30;
        $configemail['mailType'] = 'html';
        $configemail['validate'] = true;
        $configemail['priority'] = 3;
        $configemail['SMTPKeepAlive'] = false;
        $configemail['SMTPCrypto'] = 'ssl';

        $emailService->initialize($configemail);

        $message = '
            <p>Hi ' . ucwords(strtolower($first_name)) . '</p>
            <p>Complete verification and continue registration, confirm your email address. <a href="' . base_url('auth/verify/' . $verification_code) . '">Click this link to continue</a>.</p>
        ';

        $emailService->setFrom('fbccm@web-dev-project.com', 'FcCM test');
        $emailService->setTo($email);

        $emailService->setSubject('Email Verification');
        //$message = '<p>Please click this link to verify your email: <a href="'.base_url('auth/verify/' . $verification_code).'"></a></p>';
        $emailService->setMessage($message);

        //$emailService->send();

        if ($emailService->send()) {
            return true;
        }

        /*if ($emailService->send(FALSE) === TRUE){
            $email_status['status_send'] = true;
        }else{
            $email_status['status_send'] = false;
            $email_status['log_send'] = $emailService->printDebugger(['headers']);
        }*/

        return false;
    }

    /**
     * @throws \ReflectionException
     */
    public function verify($verification_code) {
        helper(['form']);

        $data_page = [
            'title'       => lang('Loginauth.titleLoginCreate') . " - " . lang('Loginauth.defSignTitle'),
            'description' => lang('Loginauth.descriptionLoginCreate') . " - " . lang('Loginauth.defSignTitle'),
        ];

        $user = $this->userModel->where('token', $verification_code)->first();

        if ($user) {
            $current_time = date('Y-m-d H:i:s');
            if ($current_time < $user['token_expires']) {
                $data_page['form_anchor'] = base_url('auth/complete-registration-post');

                $this->userModel->update($user['id'], ['verified' => 1]);
                $data_page['form_data'] = [
                    'first_name' => $user['first_name'],
                    'last_name'  => $user['last_name'],
                    'email'      => $user['email'],
                    'user_id'    => $user['id'],
                ];
                return view('loginauth/templates/create_account', $data_page);
            } else {
                $data_page['form_anchor'] = base_url('auth/register');
                $data_page['error'] = 'Verification code has expired. Please register again.';
                return view('loginauth/templates/register_account', $data_page);
            }
        } else {
            //echo 'Invalid verification code';
            $data_page['form_anchor'] = base_url('auth/register');
            $data_page['error'] = 'Invalid verification code';
            return view('loginauth/templates/register_account', $data_page);
        }
    }

    public function complete_registration_post(): string {
        helper(['form']);

        $data_page = [
            'title'       => lang('Loginauth.titleLoginCreate') . " - " . lang('Loginauth.defSignTitle'),
            'description' => lang('Loginauth.descriptionLoginCreate') . " - " . lang('Loginauth.defSignTitle'),
            'form_anchor' => base_url('auth/complete-registration-post'),
        ];

        $user_id = $this->request->getPost('user_id');

        $get_form_post = false;
        $template = "create_account";

        /*if (!$this->request->is('post')) {
            //return redirect()->to(base_url('auth/register')); 
            return view('loginauth/templates/register_account', $data_page);
        }*/

        $validation = \Config\Services::validation();

        $user = $this->userModel->where('id', $user_id)->first();

        if ($user && strtotime($user['token_expires']) > time()) {
            $rules = [
                'first_name'                => [
                    'label'  => 'First name',
                    'rules'  => 'required|alpha_numeric_space|min_length[3]|max_length[150]',
                    'errors' => [
                        'required'   => 'First name is required',
                        'alpha'      => 'First name must contain only alphabetic characters',
                        'min_length' => 'First name must be at least 3 characters long',
                        'max_length' => 'First name cannot exceed 50 characters',
                    ],
                ],
                'last_name'                 => [
                    'label'  => 'Last name',
                    'rules'  => 'required|alpha_numeric_space|min_length[3]|max_length[150]',
                    'errors' => [
                        'required'   => 'Last name is required',
                        'alpha'      => 'Last name must contain only alphabetic characters',
                        'min_length' => 'Last name must be at least 3 characters long',
                        'max_length' => 'Last name cannot exceed 50 characters',
                    ],
                ],
                'email'                     => [
                    //'rules'  => 'required|valid_email|is_unique[users.email]',
                    'label'  => 'Email',
                    'rules'  => 'required|valid_email|max_length[250]',
                    'errors' => [
                        'required'    => 'Email is required',
                        'valid_email' => 'Email must be valid',
                        //'is_unique'   => 'Email already exists',
                    ],
                ],
                'website_url'               => [
                    'label'  => 'Website',
                    'rules'  => 'permit_empty|valid_url|max_length[300]',
                    'errors' => [
                        'max_length' => 'Website cannot exceed 300 characters',
                    ],
                ],
                'company_address'           => [
                    'label'  => 'Company Address',
                    'rules'  => 'required|string|min_length[5]|max_length[400]',
                    'errors' => [
                        'required'   => 'Company Address is required',
                        'min_length' => 'Company Address must be at least 5 characters long',
                        'max_length' => 'Company Address cannot exceed 400 characters',
                    ],
                ],
                'counties_worked.*'         => [
                    'label'  => 'Counties Worked',
                    'rules'  => 'required|string|max_length[50]',
                    'errors' => [
                        'required'   => 'Counties Worked is required',
                        'max_length' => 'Counties Worked cannot exceed 50 characters',
                    ],
                ],
                'work_type.*'               => [
                    'label'  => 'Work Type',
                    'rules'  => 'required|string|max_length[50]',
                    'errors' => [
                        'required'   => 'Work Type is required',
                        'max_length' => 'Work Type cannot exceed 50 characters',
                    ],
                ],
                'how_did_you_hear_about_us' => [
                    'label'  => 'About us',
                    'rules'  => 'required|string|min_length[5]|max_length[300]',
                    'errors' => [
                        'required'   => 'About us is required',
                        'min_length' => 'About us must be at least 5 characters long',
                        'max_length' => 'About us cannot exceed 300 characters',
                    ],
                ],
                'phone'                     => [
                    'rules'  => 'required|min_length[10]|max_length[15]',
                    'errors' => [
                        'required'   => 'Phone is required',
                        'min_length' => 'Phone must be at least 10 characters long',
                        'max_length' => 'Phone cannot exceed 15 characters',
                        //'numeric'    => 'Phone must be a number',
                    ],
                ],
                'password'                  => [
                    'rules'  => 'required|min_length[8]|max_length[15]',
                    'errors' => [
                        'required'   => 'Password is required',
                        'min_length' => 'Password must be at least 8 characters long',
                        'max_length' => 'Password cannot exceed 15 characters',
                    ],
                ],
                'confirm_password'          => [
                    'rules'  => 'required|min_length[8]|max_length[15]|matches[password]',
                    'errors' => [
                        'required'   => 'Confirm Password is required',
                        'min_length' => 'Confirm Password must be at least 8 characters long',
                        'max_length' => 'Confirm Password cannot exceed 15 characters',
                        'matches'    => 'Confirm Password does not match with Password',
                    ],
                ],
            ];

            $get_form_post = $this->request->getPost(null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            //$get_form_post = $this->request->getPost();

            //if (!$this->validate($rules)) {
            if (!$this->validateData($get_form_post, $rules)) {
                //$data_page['validation'] = $this->validator;

                $data_page['validation'] = $validation->getErrors();
                $data_page['form_data'] = $get_form_post;

                return view('loginauth/templates/' . $template, $data_page);
            } else {
                $get_counties_worked = $get_work_type = false;

                $get_form_post = $validation->getValidated();

                if (empty($get_form_post) or !is_array($get_form_post)) {
                    $data_page['error'] = 'Data not found, try again.';

                    return view('loginauth/templates/' . $template, $data_page);
                }

                //$res = $this->uniqueChecker->checkUnique($this->userModel, $this->request->getPost(), ['phone',]);
                $res = $this->uniqueChecker->checkUnique($this->userModel, $get_form_post, ['phone',]);
                if (!$res['phone']) {
                    $data_page['error'] = 'Phone number already exists';
                    $data_page['form_data'] = $this->request->getPost();

                    //return view('complete_registration', $data_page);
                    return view('loginauth/templates/' . $template, $data_page);
                }

                /*$this->userModel->update($user['id'], [
                    'phone'         => $this->request->getPost('phone'),
                    'password'      => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
                    'verified'      => 1,
                    'token'         => null,
                    'token_type'    => null,
                    'token_expires' => null,
                ]);*/

                if (is_array($get_form_post['counties_worked'])) {
                    $get_counties_worked = json_encode($get_form_post['counties_worked']);
                }

                if (is_array($get_form_post['work_type'])) {
                    $get_work_type = json_encode($get_form_post['work_type']);
                }

                $this->userModel->update($user['id'], [
                    'website_url'               => !empty($get_form_post['website_url']) ? $get_form_post['website_url'] : null,
                    'company_address'           => !empty($get_form_post['company_address']) ? $get_form_post['company_address'] : null,
                    'counties_worked'           => !empty($get_counties_worked) ? $get_counties_worked : null,
                    'work_type'                 => !empty($get_work_type) ? $get_work_type : null,
                    'how_did_you_hear_about_us' => !empty($get_form_post['how_did_you_hear_about_us']) ? $get_form_post['how_did_you_hear_about_us'] : null,
                    'phone'                     => !empty($get_form_post['phone']) ? $get_form_post['phone'] : null,
                    'password'                  => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
                    'verified'                  => 1,
                    'token'                     => null,
                    'token_type'                => null,
                    'token_expires'             => null,
                ]);

                return view('welcome');
            }
        } else {
            $data_page['error'] = 'Invalid or expired verification code.';
            return view('loginauth/templates/register_account', $data_page);
            //return view('loginauth/templates/register_account', ['error' => 'Invalid or expired verification code.']);
        }
    }

    public function payment(): string {
        // Обработка данных оплаты
        return view('welcome');
    }

    public function login(): string {
        helper(['form']);

        $data_page = [
            'title'       => lang('Loginauth.titleLoginAuth') . " - " . lang('Loginauth.defSignTitle'),
            'description' => lang('Loginauth.descriptionLoginAuth') . " - " . lang('Loginauth.defSignTitle'),
            'form_anchor' => base_url('auth/login_post'),
        ];
        return view('loginauth/templates/login_auth', $data_page);
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
        helper(['form']);

        $data_page = [
            'title'       => lang('Loginauth.titleLoginResetPass') . " - " . lang('Loginauth.defSignTitle'),
            'description' => lang('Loginauth.descriptionLoginResetPass') . " - " . lang('Loginauth.defSignTitle'),
            'form_anchor' => base_url('auth/send_password_reset_email'),
        ];
        return view('loginauth/templates/forgot_password', $data_page);
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
            $emailService->clear(true);
            $email_status = false;

            $configemail['SMTPHost'] = 'mail.adm.tools';
            $configemail['SMTPUser'] = 'fbccm@web-dev-project.com';
            $configemail['SMTPPass'] = '6AnSnC3v52';
            $configemail['SMTPPort'] = 465;
            $configemail['SMTPTimeout'] = 30;
            $configemail['mailType'] = 'html';
            $configemail['validate'] = true;
            $configemail['priority'] = 3;
            $configemail['SMTPKeepAlive'] = false;
            $configemail['SMTPCrypto'] = 'ssl';

            $emailService->initialize($configemail);

            $message = '
            <p>Hi ' . ucwords(strtolower($user['first_name'])) . '</p>
            <p>Reset password. <a href="' . base_url('auth/reset_password/' . $verification_code) . '">Click this link to continue</a>.</p>
        ';

            $emailService->setFrom('fbccm@web-dev-project.com', 'FcCM test');
            $emailService->setTo($email);

            $emailService->setSubject('Email Verification');
            //$message = '<p>Please click this link to verify your email: <a href="'.base_url('auth/verify/' . $verification_code).'"></a></p>';
            $emailService->setMessage($message);

            //$emailService->send();

            $emailService->send();
        }

        return view('check_email');
    }

    public function reset_password($verification_code): string {
        helper(['form']);

        $data_page = [
            'title'       => lang('Loginauth.titleLoginResetPass') . " - " . lang('Loginauth.defSignTitle'),
            'description' => lang('Loginauth.descriptionLoginResetPass') . " - " . lang('Loginauth.defSignTitle'),
            'form_anchor' => base_url('auth/send_password_reset_email'),
        ];

        $user = $this->userModel->where('token', $verification_code)->first();

        if ($user && strtotime($user['token_expires']) > time()) {
            $data_page['email'] = $user['email'];
            $data_page['user_id'] = $user['id'];
            $data_page['form_anchor'] = base_url('auth/reset_password_post');

            return view('loginauth/templates/reset_password', $data_page);
            //return view('reset_password', ['email' => $user['email'], 'verification_code' => $verification_code]);
        } else {
            $data_page['error'] = 'Invalid or expired reset code.';
            return view('loginauth/templates/forgot_password', $data_page);
        }
    }

    public function reset_password_post(): string {
        $data_page = [
            'title'       => lang('Loginauth.titleLoginResetPass') . " - " . lang('Loginauth.defSignTitle'),
            'description' => lang('Loginauth.descriptionLoginResetPass') . " - " . lang('Loginauth.defSignTitle'),
            'form_anchor' => base_url('auth/send_password_reset_email'),
        ];

        $user_id = $this->request->getPost('user_id');
        $user = $this->userModel->where('id', $user_id)->first();

        if ($user) {
            $new_password = $this->request->getPost('new_password');
            $confirm_password = $this->request->getPost('confirm_password');

            if ($new_password === $confirm_password) {
                $this->userModel->update($user['id'], [
                    'password'      => password_hash($new_password, PASSWORD_BCRYPT),
                    'token'         => null,
                    'token_type'    => null,
                    'token_expires' => null,
                ]);

                return view('welcome');
            } else {
                return view('reset_password', [
                    'email'   => $user['email'],
                    'user_id' => $user['id'],
                    'error'   => 'Passwords do not match.',
                ]);
            }
        } else {
            $data_page['error'] = 'Invalid or expired reset code.';
            return view('loginauth/templates/forgot_password', $data_page);
        }
    }
}

