<?php namespace App\Controllers;

use CodeIgniter\Cookie\Cookie;
use CodeIgniter\HTTP\RedirectResponse;
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

        $get_form_post = $this->request->getPost(null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$this->validateData($get_form_post, 'register')) {
            // Если валидация не прошла, возвращаемся к форме с ошибками
            $data_page['validation'] = $this->validator->getErrors();
            $data_page['form_data'] = $get_form_post;

            $this->user->addErrorToLog('Registration Validate error');

            return view('loginauth/templates/register_account', $data_page);
        } else {
            $get_form_post = $this->validator->getValidated();

            if (empty($get_form_post) or !is_array($get_form_post)) {
                $data_page['error'] = 'Data not found, try again.';
                $this->user->addErrorToLog('Registration Data not found');

                return view('loginauth/templates/register_account', $data_page);
            }

            if (!$this->userPrivateModel->isUniqueEmail($get_form_post['email'])) {
                $data_page['error'] = 'Email already exists';
                $data_page['form_data'] = $get_form_post;
                $this->user->addErrorToLog('Registration Email already exists');

                return view('loginauth/templates/register_account', $data_page);
            }

            $user = $this->userPrivateModel->where('email', $get_form_post['email'])->where('verified', 0)->first();
            if (empty($user)) {
                $user = $this->userPrivateModel->createUser($get_form_post['email']);
            } else {
                $user = $this->userPrivateModel->updateVerificationCode($user['id']);
            }

            if (!empty($user['verification_code']) && $user['verifies_count'] < 4) {
                $data = [
                    'id'           => $user['id'],
                    'company_name' => $get_form_post['сompany_name'],
                    'first_name'   => $get_form_post['first_name'],
                    'last_name'    => $get_form_post['last_name'],
                ];

                $this->userModel->save($data);
                $this->user->load($user['id']);

                if ($this->_send_verification_email($get_form_post['email'], $user['verification_code']) === true) {
                    $this->session->setFlashdata('success_msg', 'Check your email for a verification link');
                    $this->user->addErrorToLog('Registration Step 1 Success');
                    return redirect()->to('auth/register-success')->withCookies();
                } else {
                    $data_page['error'] = 'Something strange happened, try again in a few minutes.';
                    $this->user->addErrorToLog('Registration Something strange happened');
                }
            } else {
                $data_page['error'] = 'You have exhausted your registration attempts. The next attempt is possible after 24 hours.';
                $this->user->addErrorToLog('Registration Exhausted registration attempts');
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

        if (!$this->user->isValid()) {
            return $this->redirectTo('auth/register',
                ((!empty($this->user->getError())) ? $this->user->getError() : 'User Error empty, user Id = ' . $this->user->getId()),
                                     'Registration Verify'
            );
        }

        $user = $this->user->getFullData();

        if ($user['verification_code'] === $verification_code) {
            if (time() < $user['verification_code_expires']) {
                $data_page['form_anchor'] = base_url('auth/complete-registration');

                $data_page['form_data'] = [
                    'first_name' => $user['first_name'],
                    'last_name'  => $user['last_name'],
                    'email'      => $user['email'],
                ];
                return view('loginauth/templates/create_account', $data_page);
            } else {
                $session_data = [
                    'register_error' => 'Verification code has expired. Please register again.',
                ];
                $this->session->setFlashdata($session_data);
                $this->user->addErrorToLog('Registration Verify - Verification code has expired');
                return redirect()->to('auth/register')->withCookies();
            }
        } else {
            $session_data = [
                'register_error' => 'Invalid verification code',
            ];
            $this->session->setFlashdata($session_data);
            $this->user->addErrorToLog('Registration Verify - Invalid verification code');
            return redirect()->to('auth/register')->withCookies();
        }
    }

    private function redirectTo(string $route, $session_error = null, $log_error = null): RedirectResponse {
        if (!empty($session_error)) {
            $session_data = [
                'register_error' => $session_error,
            ];
            $this->session->setFlashdata($session_data);
        }
        if (!empty($log_error)) {
            $this->user->addErrorToLog($log_error . ' ' . $session_error);
        }
        $this->user->logout();
        return redirect()->to($route)->withCookies();
    }

    public function completeRegistration(): \CodeIgniter\HTTP\RedirectResponse|string {
        $data_page = [
            'title'       => lang('Loginauth.titleLoginCreate') . " - " . lang('Loginauth.defSignTitle'),
            'description' => lang('Loginauth.descriptionLoginCreate') . " - " . lang('Loginauth.defSignTitle'),
            'form_anchor' => base_url('auth/complete-registration'),
        ];

        if (!$this->user->isValid()) {
            return $this->redirectTo('auth/register',
                ((!empty($this->user->getError())) ? $this->user->getError() : 'User Error empty, user Id = ' . $this->user->getId()),
                                     'Registration Verify complete'
            );
        }

        $user = $this->user->getFullData();

        if ($user && $user['token_expires'] > time()) {
            $get_form_post = $this->request->getPost(null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if (!$this->validateData($get_form_post, 'register2')) {
                $data_page['validation'] = $this->validator->getErrors();
                $data_page['form_data'] = $get_form_post;
                $this->user->addErrorToLog('Registration Verify complete Validation error');
                return view('loginauth/templates/create_account', $data_page);
            } else {
                $get_form_post = $this->validator->getValidated();

                if (empty($get_form_post) or !is_array($get_form_post)) {
                    $data_page['error'] = 'Data not found, try again.';
                    $this->user->addErrorToLog('Registration Verify complete Data not found');
                    return view('loginauth/templates/create_account', $data_page);
                }

                if (!isUnique($this->userModel, 'phone', $this->request->getPost()['phone'])) {
                    $data_page['error'] = 'Phone number already exists';
                    $data_page['form_data'] = $this->request->getPost();
                    $this->user->addErrorToLog('Registration Verify complete Phone number already exists');
                    return view('loginauth/templates/create_account', $data_page);
                }

                $this->userModel->update($user['id'], [
                    'website_url'               => !empty($get_form_post['website_url']) ? $get_form_post['website_url'] : null,
                    'company_address'           => !empty($get_form_post['company_address']) ? $get_form_post['company_address'] : null,
                    'counties_worked'           => !empty($get_form_post['counties_worked']) && is_array($get_form_post['counties_worked']) ? $get_form_post['counties_worked'] : [],
                    'work_type'                 => !empty($get_form_post['work_type']) && is_array($get_form_post['work_type']) ? $get_form_post['work_type'] : [],
                    'how_did_you_hear_about_us' => !empty($get_form_post['how_did_you_hear_about_us']) ? $get_form_post['how_did_you_hear_about_us'] : null,
                    'phone'                     => !empty($get_form_post['phone']) ? $get_form_post['phone'] : null,
                ]);
                $this->userPrivateModel->update($user['id'], [
                    'password'          => password_hash($get_form_post['password'], PASSWORD_BCRYPT),
                    'verified'          => true,
                    'verification_code' => null,
                    'verifies_count'    => 0,
                ]);
                $this->userPrivateModel->updateToken($user['id']);

                return redirect()->to('welcome')->withCookies();
            }
        } else {
            return $this->redirectTo('auth/register',
                                     'Invalid or expired verification code.',
                                     'Registration Verify complete');
        }
    }

    public function welcome(): \CodeIgniter\HTTP\RedirectResponse|string {
        if (!$this->user->isValid()) {
            return $this->redirectTo('auth/register',
                ((!empty($this->user->getError())) ? $this->user->getError() : 'User Error empty, user Id = ' . $this->user->getId()),
                                     'Registration Verify complete ' .
                                     ((!empty($this->user->getError())) ? $this->user->getError() : 'User Error empty, user Id = ' . $this->user->getId())
            );
        }

        return view('welcome');
    }

    public function payment(): string {
        // Обработка данных оплаты
        return view('welcome');
    }

    public function login(): \CodeIgniter\HTTP\RedirectResponse|string {
        if ($this->user->isValid()) {
            return redirect()->to('welcome')->withCookies();
        }

        $data_page = [
            'title'       => lang('Loginauth.titleLoginAuth') . " - " . lang('Loginauth.defSignTitle'),
            'description' => lang('Loginauth.descriptionLoginAuth') . " - " . lang('Loginauth.defSignTitle'),
            'form_anchor' => base_url('auth/login'),
        ];

        if (!$this->request->is('post')) {
            return view('loginauth/templates/login_auth', $data_page);
        }

        // TODO: Потрібна валідація
        $get_form_post = $this->request->getPost(null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!$this->validateData($get_form_post, 'login')) {
            $data_page['validation'] = $this->validator->getErrors();
            $data_page['form_data'] = $get_form_post;
            $this->user->addErrorToLog('Login page Validation error');
            return view('loginauth/templates/login_auth', $data_page);
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userPrivateModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password']) && $user['verified']) {
            if (!$this->userPrivateModel->isLogged($user['id'])) {
                $this->userPrivateModel->updateToken($user['id']);
                $this->user->load($user['id']);
                $this->user->logIn();
                return redirect()->to('welcome')->withCookies();
            }

            $data_page['error'] = 'You`re already logged in.';
            $this->user->addErrorToLog('Login page You`re already logged in');
        } else {
            $data_page['error'] = 'Invalid credentials or email not verified.';
            $this->user->addErrorToLog('Login page Invalid credentials or email not verified');
        }
        $data_page['form_data'] = $get_form_post;
        return view('loginauth/templates/login_auth', $data_page);
    }

    public function logout(): \CodeIgniter\HTTP\RedirectResponse {
        $this->user->logout();
        return redirect()->to('/')->withCookies();
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

        $get_form_post = $this->request->getPost(null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data_page['form_data'] = $get_form_post;

        if (!$this->validateData($get_form_post, 'password_reset')) {
            $data_page['validation'] = $this->validator->getErrors();

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

            if ($this->userPrivateModel->updatePasswordResetCount($user['id']) > 3) {
                $data_page['count_too_big'] = 'You have exhausted your password reset attempts. The next attempt is possible after 10 days.';
                return view('loginauth/templates/forgot_password', $data_page);
            }

            $user = $this->userPrivateModel->updateVerificationCode($user['id']);
            if (empty($user['verification_code']) && $user['verifies_count'] > 3) {
                $data_page['count_too_big'] = 'You have exhausted your verification attempts. The next attempt is possible after 24 hours.';
                return view('loginauth/templates/forgot_password', $data_page);
            }

            $emailService = \Config\Services::email();

            $message = sprintf(lang('Loginauth.messageResetPasswordAccountMessage'), base_url('auth/password-verify/' . $user['verification_code']));

            $emailService->setTo($email);
            $emailService->setSubject(lang('Loginauth.messageResetPasswordAccountSubject'));
            $emailService->setMessage($message);

            $this->user->load($user['id']);

            if ($emailService->send()) {
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

        if (!$this->user->isValid()) {
            return $this->redirectTo('auth/password-reset',
                ((!empty($this->user->getError())) ? $this->user->getError() : 'User Error empty, user Id = ' . $this->user->getId()),
                                     'Password Verify'
            );
        }

        $data_page = [
            'title'       => lang('Loginauth.titleLoginResetPass') . " - " . lang('Loginauth.defSignTitle'),
            'description' => lang('Loginauth.descriptionLoginResetPass') . " - " . lang('Loginauth.defSignTitle'),
            'form_anchor' => base_url('auth/password-verify'),
        ];

        $user = $this->user->getFullData();

        if ($user && time() < $user['verification_code_expires'] && $user['verification_code'] === $verification_code) {
            return view('loginauth/templates/reset_password', $data_page);
        } else {
            return $this->redirectTo('auth/password-reset', 'Invalid or expired reset code.', 'Password Verify');
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

        if (!$this->user->isValid()) {
            return $this->redirectTo('auth/password-reset',
                ((!empty($this->user->getError())) ? $this->user->getError() : 'User Error empty, user Id = ' . $this->user->getId()),
                                     'Password Verify'
            );
        }

        // TODO: Потрібна валідація
        $get_form_post = $this->request->getPost(null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data_page['form_data'] = $get_form_post;

        if (!$this->validateData($get_form_post, 'password_reset2')) {
            $data_page['validation'] = $this->validator->getErrors();

            return view('loginauth/templates/reset_password', $data_page);
        }

        $user = $this->user->getFullData();

        if ($user) {
            if ($get_form_post['new_password'] === $get_form_post['confirm_password']) {
                $this->userPrivateModel->update($user['id'], [
                    'password'          => password_hash($get_form_post['new_password'], PASSWORD_BCRYPT),
                    'verification_code' => null,
                    'verifies_count'    => 0,
                ]);
                $this->userPrivateModel->updateToken($user['id']);
                $this->user->load($user['id']);

                return view('welcome');
            } else {
                $data_page['error'] = 'Password does not match';
                return view('loginauth/templates/reset_password', $data_page);
            }
        } else {
            return $this->redirectTo('auth/auth/password-reset',
                                     'Invalid or expired reset code.',
                                     'Password Reset Error'
            );
        }
    }

    public function testEmail(): string {
        $emailService = \Config\Services::email();
        //$emailService->clear(true);

        $message = 'This is a test email';

        $emailService->setTo('papamuzzy@gmail.com');
        $emailService->setSubject('Test email');
        $emailService->setMessage($message);

        $emailServiceObj = $emailService->setPriority();

        $emailService->send(false);

        /*$emailService = \Config\Services::email();
        $emailService->clear(true);

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
            <p>Hi ' . ucwords(strtolower('Andrew')) . '</p>
            <p>Complete verification and continue registration, confirm your email address. <a href="' . base_url('auth/verify/') . '">Click this link to continue</a>.</p>
        ';

        $emailService->setFrom('fbccm@web-dev-project.com', 'FcCM test');
        $emailService->setTo('papamuzzy@gmail.com');

        $emailService->setSubject('Email Verification');
        $emailService->setMessage($message);

        $emailServiceObj = $emailService->setPriority();

        $emailService->send(false);*/

        $archive = $emailService->archive;
        //$data['email_debug'] = 'archive' . PHP_EOL . var_export($archive, true) . PHP_EOL . PHP_EOL . 'emailServiceObj' . PHP_EOL . var_export($emailServiceObj,true) . PHP_EOL . PHP_EOL . 'debug' . PHP_EOL . $emailService->printDebugger();
        $data['email_debug'] = 'emailServiceObj' . PHP_EOL . var_export($emailServiceObj,true) . PHP_EOL . PHP_EOL . 'debug' . PHP_EOL . $emailService->printDebugger();
        return view('test_email', $data);
    }
}
