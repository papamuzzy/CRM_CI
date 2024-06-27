<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Account extends UserBaseController {
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

    public function index() {
        if (!$this->user->isValid()) {
            $session_data = [
                'register_error' => ((!empty($this->user->getError())) ? $this->user->getError() : 'User Error empty, user Id = ' . $this->user->getId()),
            ];
            $this->session->setFlashdata($session_data);
            $this->user->addErrorToLog('Registration Verify ' .
                                       ((!empty($this->user->getError())) ? $this->user->getError() : 'User Error empty, user Id = ' . $this->user->getId()));
            return redirect()->to('auth/login')->withCookies();
        }

        $data_page = [
            'title'       => lang('Account.titleAccountEdit') . " - " . lang('Account.defSignTitle'),
            'description' => lang('Account.descriptionAccountEdit') . " - " . lang('Account..defSignTitle'),
            'form_anchor' => base_url('account'),
        ];

        $user = $this->user->getFullData();
        $data_page['form_data'] = $user;

        if (!$this->request->is('post')) {
            return view('account/templates/edit_account', $data_page);
        }

        $get_form_post = $this->request->getPost(null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!$this->validateData($get_form_post, 'edit_account')) {
            $data_page['validation'] = $this->validator->getErrors();
            $data_page['form_data'] = $get_form_post;
            $this->user->addErrorToLog('Edit Account Validation error');
            return view('account/templates/edit_account', $data_page);
        } else {
            $get_form_post = $this->validator->getValidated();
            $get_form_post['email'] = $user['email'];

            if (empty($get_form_post) or !is_array($get_form_post)) {
                $data_page['error'] = 'Data not found, try again.';
                $this->user->addErrorToLog('Edit Account Data not found');
                return view('account/templates/edit_account', $data_page);
            }

            if (!$this->userModel->isUniquePhone($user['id'], $this->request->getPost()['phone'])) {
                $data_page['error'] = 'Phone number already exists';
                $data_page['form_data'] = $get_form_post;
                $this->user->addErrorToLog('Edit Account Phone number already exists');
                return view('account/templates/edit_account', $data_page);
            }

            $this->userModel->update($user['id'], [
                'first_name'                => !empty($get_form_post['first_name']) ? $get_form_post['first_name'] : null,
                'last_name'                 => !empty($get_form_post['last_name']) ? $get_form_post['last_name'] : null,
                'company_name'              => !empty($get_form_post['company_name']) ? $get_form_post['company_name'] : null,
                'website_url'               => !empty($get_form_post['website_url']) ? $get_form_post['website_url'] : null,
                'company_address'           => !empty($get_form_post['company_address']) ? $get_form_post['company_address'] : null,
                'counties_worked'           => !empty($get_form_post['counties_worked']) && is_array($get_form_post['counties_worked']) ? $get_form_post['counties_worked'] : [],
                'work_type'                 => !empty($get_form_post['work_type']) && is_array($get_form_post['work_type']) ? $get_form_post['work_type'] : [],
                'how_did_you_hear_about_us' => !empty($get_form_post['how_did_you_hear_about_us']) ? $get_form_post['how_did_you_hear_about_us'] : null,
                'phone'                     => !empty($get_form_post['phone']) ? $get_form_post['phone'] : null,
            ]);
            $this->userPrivateModel->updateToken($user['id']);

            return redirect()->to('welcome')->withCookies();
        }
    }

    public function changePassword(): string|\CodeIgniter\HTTP\RedirectResponse {
        if (!$this->user->isValid()) {
            $session_data = [
                'register_error' => ((!empty($this->user->getError())) ? $this->user->getError() : 'User Error empty, user Id = ' . $this->user->getId()),
            ];
            $this->session->setFlashdata($session_data);
            $this->user->addErrorToLog('Registration Verify ' .
                                       ((!empty($this->user->getError())) ? $this->user->getError() : 'User Error empty, user Id = ' . $this->user->getId()));
            return redirect()->to('auth/login')->withCookies();
        }

        $data_page = [
            'title'       => lang('Account.titleChangePassword') . " - " . lang('Account.defSignTitle'),
            'description' => lang('Account.descriptionChangePassword') . " - " . lang('Account..defSignTitle'),
            'form_anchor' => base_url('account/password'),
        ];

        $user = $this->user->getFullData();
        $data_page['form_data'] = $user;

        if (!$this->request->is('post')) {
            return view('account/templates/edit_password', $data_page);
        }

        $get_form_post = $this->request->getPost(null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!$this->validateData($get_form_post, 'change_password')) {
            $data_page['validation'] = $this->validator->getErrors();
            $data_page['form_data'] = $get_form_post;
            $this->user->addErrorToLog('Change Password Validation error');
            return view('account/templates/edit_password', $data_page);
        } else {
            $get_form_post = $this->validator->getValidated();

            if (empty($get_form_post) or !is_array($get_form_post)) {
                $data_page['error'] = 'Data not found, try again.';
                $this->user->addErrorToLog('Edit Account Data not found');
                return view('account/templates/edit_password', $data_page);
            }
            if ($get_form_post['password'] === $get_form_post['confirm_password']) {
                $this->userPrivateModel->update($user['id'], [
                    'password' => password_hash($get_form_post['password'], PASSWORD_BCRYPT),
                ]);
                $this->userPrivateModel->updateToken($user['id']);
                $this->user->load($user['id']);

                return redirect()->to('account')->withCookies();
            } else {
                $data_page['error'] = 'Password does not match';
                return view('account/templates/edit_password', $data_page);
            }
        }
    }
}