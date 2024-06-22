<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig {
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var list<string>
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------

    // Register step 1
    public array $register = [
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
            'label'  => 'Email',
            'rules'  => 'required|valid_email|max_length[250]',
            'errors' => [
                'required'    => 'Email is required',
                'valid_email' => 'Email must be valid',
                'max_length'  => 'Email cannot exceed 250 characters',
            ],
        ],
    ];

    // Register step 2
    public array $register2 = [
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

    // Login
    public array $login = [
        'email'    => [
            'label'  => 'Email',
            'rules'  => 'required|valid_email|max_length[250]',
            'errors' => [
                'required'    => 'Email is required',
                'valid_email' => 'Email must be valid',
                'max_length'  => 'Email cannot exceed 250 characters',
            ],
        ],
        'password' => [
            'rules'  => 'required|min_length[8]|max_length[15]',
            'errors' => [
                'required'   => 'Password is required',
                'min_length' => 'Password must be at least 8 characters long',
                'max_length' => 'Password cannot exceed 15 characters',
            ],
        ],
    ];

    // Password reset
    public array $password_reset = [
        'email' => [
            'label'  => 'Email',
            'rules'  => 'required|valid_email|max_length[250]',
            'errors' => [
                'required'    => 'Email is required',
                'valid_email' => 'Email must be valid',
                'max_length'  => 'Email cannot exceed 250 characters',
            ],
        ],
    ];

    public array $password_reset2 = [
        'email'            => [
            'label'  => 'Email',
            'rules'  => 'required|valid_email|max_length[250]',
            'errors' => [
                'required'    => 'Email is required',
                'valid_email' => 'Email must be valid',
                'max_length'  => 'Email cannot exceed 250 characters',
            ],
        ],
        'new_password'     => [
            'rules'  => 'required|min_length[8]|max_length[15]',
            'errors' => [
                'required'   => 'Password is required',
                'min_length' => 'Password must be at least 8 characters long',
                'max_length' => 'Password cannot exceed 15 characters',
            ],
        ],
        'confirm_password' => [
            'rules'  => 'required|min_length[8]|max_length[15]|matches[new_password]',
            'errors' => [
                'required'   => 'Confirm Password is required',
                'min_length' => 'Confirm Password must be at least 8 characters long',
                'max_length' => 'Confirm Password cannot exceed 15 characters',
                'matches'    => 'Confirm Password does not match with Password',
            ],
        ],
    ];
}
