<?php namespace App\Controllers;

use CodeIgniter\Cookie\Cookie;
use DateTime;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Auth extends UserBaseController {
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

        helper(['form']);
    }

    /**
     * @throws \ReflectionException
     */
    public function register(): \CodeIgniter\HTTP\RedirectResponse|string {
        $data_page = [
            'title'       => lang('Loginauth.titleLoginRegister') . " - " . lang('Loginauth.defSignTitle'),
            'description' => lang('Loginauth.descriptionLoginRegister') . " - " . lang('Loginauth.defSignTitle'),
            'form_anchor' => base_url('auth/register'),
        ];

        $error = $this->session->getFlashData('register_error');
        if ($error !== null) {
            $data_page['error'] = $error;
        }
        if (!$this->request->is('post')) {
            return view('loginauth/templates/register_account', $data_page);
        }

        $validation = \Config\Services::validation();
        $validation->setRuleGroup('register');

        $get_form_post = $this->request->getPost(null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$this->validateData($get_form_post)) {
            // Если валидация не прошла, возвращаемся к форме с ошибками
            $data_page['validation'] = $validation->getErrors();
            $data_page['form_data'] = $get_form_post;

            return view('loginauth/templates/register_account', $data_page);
        } else {
            $get_form_post = $validation->getValidated();

            if (empty($get_form_post) or !is_array($get_form_post)) {
                $data_page['error'] = 'Data not found, try again.';

                return view('loginauth/templates/register_account', $data_page);
            }

            if (!$this->userPrivateModel->isUniqueEmail($get_form_post['email'])) {
                $data_page['error'] = 'Email already exists';
                $data_page['form_data'] = $get_form_post;

                return view('loginauth/templates/register_account', $data_page);
            }

            $user = $this->userPrivateModel->where('email', $get_form_post['email'])->where('verified', 0)->first();
            if ($user === null) {
                $user = $this->userPrivateModel->createUser($get_form_post['email']);
            } else {
                $user = $this->userPrivateModel->updateVerificationCode($user['id']);
            }

            if (!empty($user['verification_code']) && $user['verifies_count'] < 4) {
                $data = [
                    'id'         => $user['id'],
                    'company'    => $get_form_post['сompany_name'],
                    'first_name' => $get_form_post['first_name'],
                    'last_name'  => $get_form_post['last_name'],
                ];

                $this->userModel->save($data);

                if ($this->_send_verification_email($get_form_post['email'], $user['verification_code']) === true) {
                    $this->loadUser($user['id']);
                    $this->createSession();

                    $this->session->setFlashdata('success_msg', 'Check your email for a verification link');
                    return redirect()->to('auth/register-success');
                } else {
                    $data_page['error'] = 'Something strange happened, try again in a few minutes.';
                }
            } else {
                $data_page['error'] = 'You have exhausted your registration attempts. The next attempt is possible after 24 hours.';
            }
            return view('loginauth/templates/register_account', $data_page);
        }
    }

    public function registerSuccess(): string {
        $data_page = [
            'title'       => lang('Loginauth.titleLoginRegisterSuccess') . " - " . lang('Loginauth.defSignTitle'),
            'description' => lang('Loginauth.descriptionLoginRegisterSuccess') . " - " . lang('Loginauth.defSignTitle'),
        ];
        if (($success_msg = $this->session->getFlashData('success_msg')) !== null) {
            $data_page['success_msg'] = $success_msg;
        }
        return view('loginauth/templates/check_email', $data_page);
    }

    private function _send_verification_email($email, $verification_code): bool {
        $emailService = \Config\Services::email();

        $message = sprintf(lang('Loginauth.messagePreRegisterAccountMessage'), base_url('auth/register-verify/' . $verification_code));
        $emailService->setTo($email);
        $emailService->setSubject(lang('Loginauth.messagePreRegisterAccountSubject'));
        $emailService->setMessage($message);

        if ($emailService->send()) {
            return true;
        }

        return false;
    }

    /**
     * @throws \ReflectionException
     */
    public function registerVerify($verification_code): \CodeIgniter\HTTP\RedirectResponse|string {
        $data_page = [
            'title'       => lang('Loginauth.titleLoginCreate') . " - " . lang('Loginauth.defSignTitle'),
            'description' => lang('Loginauth.descriptionLoginCreate') . " - " . lang('Loginauth.defSignTitle'),
        ];

        if (!$this->isUserValid()) {
            $session_data = [
                'register_error' => $this->error,
            ];
            $this->session->markAsFlashdata('register_error');
            $this->session->set($session_data);
            return redirect()->to('auth/register');
        }

        $user = $this->getUserFullData();

        if ($user['verification_code'] === $verification_code) {
            if (time() < $user['verification_code_expires']) {
                $data_page['form_anchor'] = base_url('auth/complete-registration');

                $data_page['session'] = var_export($this->session->get(), true) . PHP_EOL . '$this->userId = ' . var_export($this->userId, true) . PHP_EOL . '$this->error = ' . var_export($this->error, true);
                //$this->userPrivateModel->update($user['id'], ['verified' => true]);
                $data_page['form_data'] = [
                    'first_name' => $user['first_name'],
                    'last_name'  => $user['last_name'],
                    'email'      => $user['email'],
                    'user_id'    => $user['id'],
                ];
                return view('loginauth/templates/create_account', $data_page);
            } else {
                $session_data = [
                    'register_error' => 'Verification code has expired. Please register again.',
                ];
                $this->session->markAsFlashdata('register_error');
                $this->session->set($session_data);
                return redirect()->to('auth/register');
            }
        } else {
            $session_data = [
                'register_error' => 'Invalid verification code',
            ];
            $this->session->markAsFlashdata('register_error');
            $this->session->set($session_data);
            return redirect()->to('auth/register');
        }
    }

    public function completeRegistration(): \CodeIgniter\HTTP\RedirectResponse|string {
        $data_page = [
            'title'       => lang('Loginauth.titleLoginCreate') . " - " . lang('Loginauth.defSignTitle'),
            'description' => lang('Loginauth.descriptionLoginCreate') . " - " . lang('Loginauth.defSignTitle'),
            'form_anchor' => base_url('auth/complete-registration'),
        ];

        if (!$this->isUserValid()) {
            $session_data = [
                'register_error' => $this->error,
            ];
            $this->session->markAsFlashdata('register_error');
            $this->session->set($session_data);
            return redirect()->to('auth/register');
        }

        $validation = \Config\Services::validation();
        $validation->setRuleGroup('register2');

        $user = $this->userPrivateData;

        if ($user && $user['token_expires'] > time()) {
            $get_form_post = $this->request->getPost(null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if (!$this->validateData($get_form_post)) {
                $data_page['validation'] = $validation->getErrors();
                $data_page['form_data'] = $get_form_post;

                return view('loginauth/templates/create_account', $data_page);
            } else {
                $get_counties_worked = $get_work_type = false;

                $get_form_post = $validation->getValidated();

                if (empty($get_form_post) or !is_array($get_form_post)) {
                    $data_page['error'] = 'Data not found, try again.';

                    return view('loginauth/templates/create_account', $data_page);
                }

                if (!isUnique($this->userModel, 'phone', $this->request->getPost()['phone'])) {
                    $data_page['error'] = 'Phone number already exists';
                    $data_page['form_data'] = $this->request->getPost();

                    return view('loginauth/templates/create_account', $data_page);
                }

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
                ]);
                $this->userPrivateModel->update($user['id'], [
                    'password'          => password_hash($get_form_post['password'], PASSWORD_BCRYPT),
                    'verified'          => true,
                    'verification_code' => null,
                    'verifies_count'    => 0,
                ]);

                return redirect()->to('welcome');
            }
        } else {
            $session_data = [
                'register_error' => 'Invalid or expired verification code.',
            ];
            $this->session->markAsFlashdata('register_error');
            $this->session->set($session_data);
            return redirect()->to('auth/register');
        }
    }

    public function welcome(): string {
        return view('welcome');
    }

    public function payment(): string {
        // Обработка данных оплаты
        return view('welcome');
    }

    public function login(): \CodeIgniter\HTTP\RedirectResponse|string {
        $data_page = [
            'title'       => lang('Loginauth.titleLoginAuth') . " - " . lang('Loginauth.defSignTitle'),
            'description' => lang('Loginauth.descriptionLoginAuth') . " - " . lang('Loginauth.defSignTitle'),
            'form_anchor' => base_url('auth/login'),
        ];

        if (!$this->request->is('post')) {
            return view('loginauth/templates/login_auth', $data_page);
        }

        // TODO: Потрібна валідація

        $validation = \Config\Services::validation();
        $validation->setRuleGroup('login');

        $get_form_post = $this->request->getPost(null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!$this->validateData($get_form_post)) {
            $data_page['validation'] = $validation->getErrors();
            $data_page['form_data'] = $get_form_post;

            return view('loginauth/templates/login_auth', $data_page);
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userPrivateModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password']) && $user['verified']) {
            $this->loadUser($user['id']);
            $this->createSession();
            return redirect()->to('welcome');
        } else {
            $data_page['error'] = 'Invalid credentials or email not verified.';
            $data_page['form_data'] = $get_form_post;
            return view('loginauth/templates/login_auth', $data_page);
        }
    }

    public function logout(): \CodeIgniter\HTTP\RedirectResponse {
        $this->session->destroy();
        return redirect()->to('/');
    }

    public function requestPasswordReset(): \CodeIgniter\HTTP\RedirectResponse|string {
        // TODO: Після першої форми, верифікації емейлу, юзер може не завершивши реєстрацію, скористатись скиданням пароля, це потрібно пофіксити, юзер не може скидати пароль заповнивши лише першу форму реєстрації, не закінчивши реєстрацію
        // ToDo ця перевірка можлива тільки після введення емейлу

        $data_page = [
            'title'       => lang('Loginauth.titleLoginResetPass') . " - " . lang('Loginauth.defSignTitle'),
            'description' => lang('Loginauth.descriptionLoginResetPass') . " - " . lang('Loginauth.defSignTitle'),
            'form_anchor' => base_url('auth/password-reset'),
        ];

        $error = $this->session->getFlashData('register_error');
        if ($error !== null) {
            $data_page['error'] = $error;
        }

        if (!$this->request->is('post')) {
            return view('loginauth/templates/forgot_password', $data_page);
        }

        $validation = \Config\Services::validation();
        $validation->setRuleGroup('password_reset');

        $get_form_post = $this->request->getPost(null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data_page['form_data'] = $get_form_post;

        if (!$this->validateData($get_form_post)) {
            $data_page['validation'] = $validation->getErrors();

            return view('loginauth/templates/forgot_password', $data_page);
        }

        $email = $this->request->getPost('email');
        $user = $this->userPrivateModel->where('email', $email)->first();

        // TODO: Потрібно тут переробити логіку, наприклад якщо юзера нема то повертати на сторінку відновлення, без відображення форми але з повідомленням що лист наліслано
        // ToDo Це буде обман юзера, думаю краще повідомити, що емейл не існує й знову видати форму. При цьому рахувати кількість попиток. Якщо з третього разу не вгадав — блокуємо.

        if ($user) {
            if (!$user['verified']) {
                $data_page['not_verified'] = true;
                return view('loginauth/templates/forgot_password', $data_page);
            }

            $user = $this->userPrivateModel->updateVerificationCode($user['id']);
            if (empty($user['verification_code']) && $user['verifies_count'] > 3) {
                $data_page['count_too_big'] = 'You have exhausted your registration attempts. The next attempt is possible after 24 hours.';
                return view('loginauth/templates/forgot_password', $data_page);
            }

            $emailService = \Config\Services::email();

            $message = sprintf(lang('Loginauth.messageResetPasswordAccountMessage'), base_url('auth/password-verify/' . $user['verification_code']));

            $emailService->setTo($email);
            $emailService->setSubject(lang('Loginauth.messageResetPasswordAccountSubject'));
            $emailService->setMessage($message);

            if ($emailService->send()) {
                $this->loadUser($user['id']);
                $this->createSession();

                return view('check_email');
            } else {
                $data_page['error'] = 'Something strange happened, try again in a few minutes.';
                return view('loginauth/templates/forgot_password', $data_page);
            }
        }

        $email_not_found_count = $this->session->get('email_not_found_count') ?? 0;
        $this->session->set('email_not_found_count', ++$email_not_found_count);
        if ($email_not_found_count > 3) {
            $data_page['email_count_too_big'] = 'You have exhausted your email error attempts. The next attempt is possible after 2 hours.';
        }
        $data_page['error'] = 'Email not found!';
        return view('loginauth/templates/forgot_password', $data_page);
    }

    public function passwordVerify($verification_code): \CodeIgniter\HTTP\RedirectResponse|string {
        // TODO: Та сама ситуація, лінк з токеном доступний на інший пристроях та браузерах, потрібно переробити логіку привʼязки токену суто для конкретного юзера. Емейл не потрібно виводити але поле нехай буде, нехай юзер вводить свій емейл, для того щоб підтвердити.
        // TODO: Потрібна валідація токену
        // ToDo токен використовується один раз при перевірці $user['verification_code'] == $verification_code, ні в запитах,
        // ні деінде ще не використовується, навіщо його додатково валідувати?

        if (!$this->isUserValid()) {
            $session_data = [
                'register_error' => $this->sessionError,
            ];
            $this->session->markAsFlashdata('register_error');
            $this->session->set($session_data);
            return redirect()->to('auth/password-reset');
        }

        $data_page = [
            'title'       => lang('Loginauth.titleLoginResetPass') . " - " . lang('Loginauth.defSignTitle'),
            'description' => lang('Loginauth.descriptionLoginResetPass') . " - " . lang('Loginauth.defSignTitle'),
            'form_anchor' => base_url('auth/password-verify'),
        ];

        $user = $this->userPrivateData;

        if ($user && time() < $user['verification_code_expires'] && $user['verification_code'] === $verification_code) {
            return view('loginauth/templates/reset_password', $data_page);
        } else {
            $session_data = [
                'register_error' => 'Invalid or expired reset code.',
            ];
            $this->session->markAsFlashdata('register_error');
            $this->session->set($session_data);
            return redirect()->to('auth/auth/password-reset');
        }
    }

    /**
     * @throws \ReflectionException
     */
    public function passwordVerifyPost(): \CodeIgniter\HTTP\RedirectResponse|string {
        $data_page = [
            'title'       => lang('Loginauth.titleLoginResetPass') . " - " . lang('Loginauth.defSignTitle'),
            'description' => lang('Loginauth.descriptionLoginResetPass') . " - " . lang('Loginauth.defSignTitle'),
            'form_anchor' => base_url('auth/password-verify'),
        ];

        if (!$this->isUserValid()) {
            $session_data = [
                'register_error' => $this->sessionError,
            ];
            $this->session->markAsFlashdata('register_error');
            $this->session->set($session_data);
            return redirect()->to('auth/password-reset');
        }

        // TODO: Потрібна валідація
        $validation = \Config\Services::validation();
        $validation->setRuleGroup('password_reset2');

        $get_form_post = $this->request->getPost(null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data_page['form_data'] = $get_form_post;

        if (!$this->validateData($get_form_post)) {
            $data_page['validation'] = $validation->getErrors();

            return view('loginauth/templates/reset_password', $data_page);
        }

        $user = $this->userPrivateData;

        if ($user) {
            if ($get_form_post['new_password'] === $get_form_post['confirm_password']) {
                $this->userPrivateModel->update($user['id'], [
                    'password'          => password_hash($get_form_post['new_password'], PASSWORD_BCRYPT),
                    'verification_code' => null,
                    'verifies_count'    => 0,
                ]);

                return view('welcome');
            } else {
                $data_page['error'] = 'Password does not match';
                return view('loginauth/templates/reset_password', $data_page);
            }
        } else {
            $session_data = [
                'register_error' => 'Invalid or expired reset code.',
            ];
            $this->session->markAsFlashdata('register_error');
            $this->session->set($session_data);
            return redirect()->to('auth/auth/password-reset');
        }
    }
}

