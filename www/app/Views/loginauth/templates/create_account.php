<?= $this->extend('loginauth/layout/default') ?>

<?= $this->section('content') ?>

<?php 
    $data_work_type = ((!empty($form_data['work_type']) and is_array($form_data['work_type'])) ? $form_data['work_type'] : false);
    $data_counties_worked = ((!empty($form_data['counties_worked']) and is_array($form_data['counties_worked'])) ? $form_data['counties_worked'] : false);

    $list_work_type = array(
        'Aluminum',
        'Commercial',
        'Construction',
        'Generator Installation',
        'HVAC',
        'Multi-Family Construction',
        'Pool Construction',
        'Roofing',
        'Single Family Construction',
        'Solar'
    );

    $list_counties_worked = array(
        'One',
        'Two',
        'Three'
    );
?>

    <div id="login-page" class="section login_page login_page_lw">
        <div class="section_wdth login_form_fw">
            <div class="login_form_bi_lw">
                <div class="login_form_bi_f">
                    <div class="login_form_tt">
                        <div class="login_form_logo">
                            <img src="<?= base_url("img/login-crtacc-logo.svg") ?>" title="Freedom Code Compliance - Create Account" width="175">
                        </div>
                        <div class="login_form_tt_cn">
                            <div class="login_form_tt_n">
                            <span>Create Account</span>
                            </div>
                        </div>
                    </div><!-- .login_form_tt -->
                    
                    <?php if (!empty($error) and is_string($error)): ?>
                        <div class="form_validation_msg">
                            <div class="form_validation_msg_ls form_validation_msg_ls_error">
                                <span class="form_validation_msg_inp_invalid"><?= esc($error) ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div id="login-form-auth" class="login_form_auth">
                        <?php if (!empty($form_anchor) and is_string($form_anchor)) : ?>
                        <?= form_open($form_anchor, array('id' => 'form-login-create', 'class'=>'form_login_auth', 'autocomplete'=>'off')) ?>
                            <input type="hidden" name="user_id" value="<?= $form_data['user_id']; ?>">
                            <div class="row form_row">
                                <div class="col form_col">
                                    <label for="first-name" class="form-label">First Name</label>
                                    <input id="first-name" type="text" name="first_name" class="form-control form_input <?= (!empty($validation['first_name']) ? 'inp_invalid' : '') ?>" placeholder="First Name" aria-label="First Name" maxlength="150" required<?= ((!empty($form_data['first_name'])) ? ' value="' . esc($form_data['first_name']) . '"' : '') ?>>
                                    <?php if (!empty($validation['first_name'])) : ?>
                                        <span class="inp_invalid invalid_cs_inp"><?= esc($validation['first_name']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row form_row">
                                <div class="col form_col">
                                    <label for="last-name" class="form-label">Last Name</label>
                                    <input id="last-name" type="text" name="last_name" class="form-control form_input <?= (!empty($validation['last_name']) ? 'inp_invalid' : '') ?>" placeholder="Last Name" aria-label="Last Name" maxlength="150" required<?= ((!empty($form_data['last_name'])) ? ' value="' . esc($form_data['last_name']) . '"' : '') ?>>
                                    <?php if (!empty($validation['last_name'])) : ?>
                                        <span class="inp_invalid invalid_cs_inp"><?= esc($validation['last_name']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row form_row">
                                <div class="col form_col">
                                    <label for="email" class="form-label">Email</label>
                                    <input id="email" type="email" name="email" class="form-control form_input form_input_email <?= (!empty($validation['email']) ? 'inp_invalid' : '') ?>" placeholder="Email" aria-label="Email" maxlength="250" required<?= ((!empty($form_data['email'])) ? ' value="' . esc($form_data['email']) . '"' : '') ?>>
                                    <?php if (!empty($validation['email'])) : ?>
                                        <span class="inp_invalid invalid_cs_inp"><?= esc($validation['email']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>




                            <div class="row form_row">
                                <div class="col form_col">
                                    <label for="input-company-phone" class="form-label">Company Phone</label>
                                    <input id="input-company-phone" type="tel" name="phone" class="form-control form_input <?= (!empty($validation['phone']) ? 'inp_invalid' : '') ?>" placeholder="Company Phone" aria-label="Company Phone" maxlength="14" required<?= ((!empty($form_data['phone'])) ? ' value="' . esc($form_data['phone']) . '"' : '') ?>>
                                    <?php if (!empty($validation['phone'])) : ?>
                                        <span class="inp_invalid invalid_cs_inp"><?= esc($validation['phone']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row form_row">
                                <div class="col form_col">
                                    <label for="input-website-url" class="form-label">Website</label>
                                    <input id="input-website-url" type="url" name="website_url" class="form-control form_input <?= (!empty($validation['website_url']) ? 'inp_invalid' : '') ?>" placeholder="https://" pattern="https://.*" aria-label="Website" maxlength="300" <?= ((!empty($form_data['website_url'])) ? ' value="' . esc($form_data['website_url']) . '"' : '') ?>>
                                    <?php if (!empty($validation['website_url'])) : ?>
                                        <span class="inp_invalid invalid_cs_inp"><?= esc($validation['website_url']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row form_row">
                                <div class="col form_col">
                                    <label for="input-company-address" class="form-label">Company Address</label>
                                    <input id="input-company-address" type="text" name="company_address" class="form-control form_input <?= (!empty($validation['company_address']) ? 'inp_invalid' : '') ?>" placeholder="Company Address" aria-label="Company Address" maxlength="400" required<?= ((!empty($form_data['company_address'])) ? ' value="' . esc($form_data['company_address']) . '"' : '') ?>>
                                    <?php if (!empty($validation['company_address'])) : ?>
                                        <span class="inp_invalid invalid_cs_inp"><?= esc($validation['company_address']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if (!empty($list_counties_worked) and is_array($list_counties_worked)) : ?>
                            <div class="row form_row">
                                <div class="col form_col">
                                    <label for="input-counties-worked" class="form-label">Counties Worked</label>
                                    <select id="input-counties-worked" name="counties_worked[]" class="form-select form_input_select <?= (!empty($validation['counties_worked']) ? 'inp_invalid' : '') ?>" multiple="multiple" required>
                                        <option></option>
                                        <?php foreach ($list_counties_worked as $val_counties_worked) : ?>
                                            <?php if (!empty($val_counties_worked)) : ?>
                                            <option value="<?= esc($val_counties_worked) ?>" <?= ((!empty($data_counties_worked) and in_array($val_counties_worked, $data_counties_worked)) ? ' selected ' : '') ?>><?= esc($val_counties_worked) ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (!empty($validation['counties_worked'])) : ?>
                                        <span class="inp_invalid invalid_cs_inp"><?= esc($validation['counties_worked']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($list_work_type) and is_array($list_work_type)) : ?>
                            <div class="row form_row">
                                <div class="col form_col">
                                    <label for="input-work-type" class="form-label">Work Type</label>
                                    <select id="input-work-type" name="work_type[]" class="form-select form_input_select <?= (!empty($validation['work_type']) ? 'inp_invalid' : '') ?>" multiple="multiple" required>
                                        <option></option>
                                        <?php foreach ($list_work_type as $val_work_type) : ?>
                                            <?php if (!empty($val_work_type)) : ?>
                                            <option value="<?= esc($val_work_type) ?>" <?= ((!empty($data_work_type) and in_array($val_work_type, $data_work_type)) ? ' selected ' : '') ?>><?= esc($val_work_type) ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (!empty($validation['work_type'])) : ?>
                                        <span class="inp_invalid invalid_cs_inp"><?= esc($validation['work_type']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            <div class="row form_row">
                                <div class="col form_col">
                                    <label for="input-how-did-you-hear-about-us" class="form-label">How did you hear about us?</label>
                                    <select id="input-how-did-you-hear-about-us" name="how_did_you_hear_about_us" class="form-select form_input_select <?= (!empty($validation['how_did_you_hear_about_us']) ? 'inp_invalid' : '') ?>" required>
                                        <option></option>
                                        <option value="Word of Mouth" <?= (!empty($form_data['how_did_you_hear_about_us']) and $form_data['how_did_you_hear_about_us'] == "Word of Mouth") ? ' selected ' : ''?>>Word of Mouth</option>
                                        <option value="Email Campaign" <?= (!empty($form_data['how_did_you_hear_about_us']) and $form_data['how_did_you_hear_about_us'] == "Email Campaign") ? ' selected ' : ''?>>Email Campaign</option>
                                        <option value="Facebook Group" <?= (!empty($form_data['how_did_you_hear_about_us']) and $form_data['how_did_you_hear_about_us'] == "Facebook Group") ? ' selected ' : ''?>>Facebook Group</option>
                                        <option value="Facebook Ad" <?= (!empty($form_data['how_did_you_hear_about_us']) and $form_data['how_did_you_hear_about_us'] == "Facebook Ad") ? ' selected ' : ''?>>Facebook Ad</option>
                                    </select>
                                    <?php if (!empty($validation['how_did_you_hear_about_us'])) : ?>
                                        <span class="inp_invalid invalid_cs_inp"><?= esc($validation['how_did_you_hear_about_us']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>




                            <div class="row form_row">
                                <div class="col form_col">
                                    <label for="input-pass" class="form-label">Create New Password</label>
                                    <input type="password" id="input-pass" name="password" class="form-control form_input form_input_pass <?= (!empty($validation['password']) ? 'inp_invalid' : '') ?>" placeholder="Password" aria-label="Password" maxlength="50" required value="">
                                    <?php if (!empty($validation['password'])) : ?>
                                        <span class="inp_invalid invalid_cs_inp"><?= esc($validation['password']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row form_row">
                                <div class="col form_col">
                                    <label for="input-pass-confirm" class="form-label">Confirm Password </label>
                                    <input type="password" id="input-pass-confirm" name="confirm_password" class="form-control form_input form_input_pass <?= (!empty($validation['confirm_password']) ? 'inp_invalid' : '') ?>" placeholder="Confirm Password" aria-label="Confirm Password" maxlength="50" required value="">
                                    <?php if (!empty($validation['confirm_password'])) : ?>
                                        <span class="inp_invalid invalid_cs_inp"><?= esc($validation['confirm_password']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>




                            <div class="row form_row form_login_button">
                                <div class="col form_col">
                                    <button type="submit" class="btn btn-primary">Create Account</button>
                                </div>
                            </div>
                        <?= form_close() ?><!-- #form-login-create -->
                        <div class="login_form_cslk">
                            <span>By creating an account i agree to binance <a href="#">terms of service</a> and <a href="#">privacy policy</a></span>
                        </div>
                        <?php else: ?>
                            <div class="data_not_found_msg">
                                <span class="form_validation_msg_inp_invalid">Data not found</span>
                            </div>
                        <?php endif; ?>
                    </div><!-- #login-form-auth -->
                </div>
            </div><!-- .login_form_bi_lw -->
        </div><!-- .login_form_fw -->

        <div class="login_copy login_copy_lw">
          <?= $this->include('loginauth/includes/copylogin') ?> 
        </div>
    </div><!-- #login-page -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js" integrity="sha512-efAcjYoYT0sXxQRtxGY37CKYmqsFVOIwMApaEbrxJr4RwqVVGw8o+Lfh/+59TU07+suZn1BWq4fDl5fdgyCNkw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/i18n/en.min.js" integrity="sha512-IS4cS3xCf0ASMwMgYwIo/fiAz3UCorMca4XSHGAEIvd/Qzy0SBez7HtLrKrjzGdKjAwPBS0B7yEi3zxVIcCXKA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js" integrity="sha512-WMEKGZ7L5LWgaPeJtw9MBM4i5w5OSBlSjTjCtSnvFJGSVD26gE5+Td12qN5pvWXhuWaWcVwF++F7aqu9cvqP0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/additional-methods.min.js" integrity="sha512-TiQST7x/0aMjgVTcep29gi+q5Lk5gVTUPE9XgN0g96rwtjEjLpod4mlBRKWHeBcvGBAEvJBmfDqh2hfMMmg+5A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function() {
            $('#input-company-phone').inputmask({"mask": "(999) 999-9999"});

            $('#input-counties-worked, #input-work-type, #input-how-did-you-hear-about-us').select2({
                minimumResultsForSearch: Infinity,
                placeholder: "choose..."
            });  

            $("#form-login-create").validate({
                ignore: ".ignore",
                errorClass: "inp_invalid",
                validClass: "inp_success",
                errorElement: "span",
                rules: {
                    first_name: {
                        required: true,
                        minlength: 3,
                        maxlength: 150
                    },
                    last_name: {
                        required: true,
                        minlength: 3,
                        maxlength: 150
                    },
                    email: {
                        required: true,
                        maxlength: 200,
                        email: true
                    },
                    phone: {
                        required: true,
                        minlength: 14,
                        maxlength: 14,
                        phoneUS: true
                    },
                    website_url: {
                        required: false,
                        maxlength: 300,
                        url: true
                    },
                    company_address: {
                        required: true,
                        minlength: 5,
                        maxlength: 400
                    },
                    counties_worked: {
                        required: true,
                        maxlength: 100
                    },
                    work_type: {
                        required: true,
                        maxlength: 500
                    },
                    how_did_you_hear_about_us: {
                        required: true,
                        maxlength: 100
                    },
                    password: {
                        required: true,
                        maxlength: 50
                    },
                    confirm_password: {
                        equalTo: "#input-pass"
                    }
                },
                submitHandler: function(form) {
                    $(form).find('button[type="submit"] span').html("Submitting ...");
                    $(form).find('button[type="submit"]').prop("disabled", true);
                    form.submit();
                }
            });
        });
    </script>

<?= $this->endSection() ?>