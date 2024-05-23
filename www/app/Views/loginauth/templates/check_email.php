<?= $this->extend('loginauth/layout/default') ?>

<?= $this->section('content') ?>

    <div id="login-page" class="section login_page login_page_lw pgs_check_email">
        <div class="section_wdth login_form_fw">
            <div class="login_form_bi_lw">
                <div class="login_form_bi_f">
                    <div class="login_form_tt">
                        <div class="login_form_logo">
                            <img src="<?= base_url("img/login-crtacc-logo.svg") ?>" title="Freedom Code Compliance - Register Account" width="175">
                        </div>
                        <div class="login_form_tt_cn">
                            <div class="login_form_tt_n">
                            <span>Register Account</span>
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
                        
                    <?php if (!empty($success_msg) and is_string($success_msg)): ?>
                        <div class="form_validation_msg">
                            <div class="form_validation_msg_ls form_validation_msg_ls_success">
                                <span class="form_validation_msg_inp_valid_success"><?= esc($success_msg) ?></span>
                            </div>
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

<?= $this->endSection() ?>