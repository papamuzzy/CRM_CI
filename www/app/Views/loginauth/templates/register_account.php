<?php echo $this->extend('loginauth/layout/default') ?>

<?php echo $this->section('content') ?>

    <div id="login-page" class="section login_page login_page_lw">
        <div class="section_wdth login_form_fw">
            <div class="login_form_bi_lw">
                <div class="login_form_bi_f">
                    <div class="login_form_tt">
                        <div class="login_form_logo">
                            <img src="<?php echo base_url("img/login-crtacc-logo.svg") ?>" title="Freedom Code Compliance - Register Account" width="175">
                        </div>
                        <div class="login_form_tt_cn">
                            <div class="login_form_tt_n">
                            <span>Register Account</span>
                            </div>
                        </div>
                    </div><!-- .login_form_tt -->

                    <?php if (isset($validation)): ?>
                        <div class="form_validation_msg">
                            <div class="form_validation_msg_">
                                <?= $validation->listErrors() ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($form_data)): ?>
                        <div class="form_validation_msg">
                            <div class="form_validation_msg_">
                                <pre>
                                <?= var_export($form_data, true) ?>
                                </pre>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div id="login-form-auth" class="login_form_auth">
                        <?php if (!empty($form_anchor) and is_string($form_anchor)) : ?>
                        <?php echo form_open($form_anchor, array('id' => 'form-login-auth', 'class'=>'form_login_auth', 'autocomplete'=>'off')) ?> 
                            <div class="row form_row">
                                <div class="col form_col">
                                    <label for="сompany-name" class="form-label">Сompany Name</label>
                                    <input id="сompany-name" type="text" name="сompany_name" class="form-control form_input" placeholder="Company Name" aria-label="Company Name" maxlength="350" required<?= ((!empty($form_data['сompany_name'])) ? ' value="' . $form_data['сompany_name'] . '"' : '') ?>>
                                </div>
                            </div>
                            <div class="row form_row">
                                <div class="col form_col">
                                    <label for="first-name" class="form-label">First Name</label>
                                    <input id="first-name" type="text" name="first_name" class="form-control form_input" placeholder="First Name" aria-label="First Name" maxlength="350" required<?= ((!empty($form_data['first_name'])) ? ' value="' . $form_data['first_name'] . '"' : '') ?>>
                                </div>
                            </div>
                            <div class="row form_row">
                                <div class="col form_col">
                                    <label for="last-name" class="form-label">Last Name</label>
                                    <input id="last-name" type="text" name="last_name" class="form-control form_input" placeholder="Last Name" aria-label="Last Name" maxlength="350" required<?= ((!empty($form_data['last_name'])) ? ' value="' . $form_data['last_name'] . '"' : '') ?>>
                                </div>
                            </div>
                            <div class="row form_row">
                                <div class="col form_col">
                                    <label for="email" class="form-label">Primary Email</label>
                                    <input id="email" type="email" name="email" class="form-control form_input form_input_email" placeholder="Email" aria-label="Email" maxlength="250" required<?= ((!empty($form_data['email'])) ? ' value="' . $form_data['email'] . '"' : '') ?>>
                                </div>
                            </div>
                            <div class="row form_row form_login_button">
                                <div class="col form_col">
                                    <button type="submit" class="btn btn-primary">Registration</button>
                                </div>
                            </div>
                        <?php echo form_close() ?><!-- #form-login-auth -->
                        <?php else: ?>
                            <div class="data_not_found_msg">
                                <span>Data not found</span>
                            </div>
                        <?php endif; ?>
                    </div><!-- #login-form-auth -->
                </div>
            </div><!-- .login_form_bi_lw -->
        </div><!-- .login_form_fw -->

        <div class="login_copy login_copy_lw">
          <div class="copyright_t">
            <span>2024 Freedom Code Compliance. All right reserved.</span>
          </div>
        </div>
    </div><!-- #login-page -->

<?php echo $this->endSection() ?>