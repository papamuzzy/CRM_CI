<?= $this->extend('loginauth/layout/default') ?>

<?= $this->section('content') ?>

    <div id="login-page" class="section login_page login_page_lw pgs_forgot_password">
        <div class="section_wdth login_form_fw">
            <div class="login_form_bi_lw">
                <div class="login_form_bi_f">
                    <div class="login_form_tt">
                        <div class="login_form_logo">
                            <img src="<?= base_url("img/login-crtacc-logo.svg") ?>" title="Freedom Code Compliance - Forgot Password" width="175">
                        </div>
                        <div class="login_form_tt_cn">
                            <div class="login_form_tt_n">
                                <span>Forgot Password</span>
                            </div>
                            <div class="login_form_tt_lk">
                                <span>Remembered your password? <a href="<?= base_url("auth/login") ?>">Login</a></span>
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

                    <?php if (empty($not_verified) && empty($count_too_big) && empty($email_count_too_big)): ?>
                    <div id="login-form-auth" class="login_form_auth">
                        <?php if (!empty($form_anchor) and is_string($form_anchor)) : ?>
                        <?= form_open($form_anchor, array('id' => 'form-login-register', 'class'=>'form_login_auth', 'autocomplete'=>'off')) ?> 
                            <div class="row form_row">
                                <div class="col form_col">
                                    <label for="email" class="form-label">Email</label>
                                    <input id="email" type="email" name="email" class="form-control form_input form_input_email <?= (!empty($validation['email']) ? 'inp_invalid' : '') ?>" placeholder="Email" aria-label="Email" maxlength="250" required<?= ((!empty($form_data['email'])) ? ' value="' . esc($form_data['email']) . '"' : '') ?>>
                                    <?php if (!empty($validation['email'])) : ?>
                                        <span class="inp_invalid invalid_cs_inp"><?= esc($validation['email']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row form_row form_login_button">
                                <div class="col form_col">
                                    <button type="submit" class="btn btn-primary"><span>Send reset email</span></button>
                                </div>
                            </div>
                        <?= form_close() ?><!-- #form-login-register -->
                        <?php else: ?>
                            <div class="data_not_found_msg">
                                <span class="form_validation_msg_inp_invalid">Data not found</span>
                            </div>
                        <?php endif; ?>
                    </div><!-- #login-form-auth -->
                    <?php elseif (!empty($not_verified)): ?>
                    <div>
                        <div>Sorry, you are not verified yet!</div>
                        <div><a href="<?= base_url("auth/register") ?>">Register</a> </div>
                    </div>
                    <?php elseif (!empty($count_too_big)): ?>
                        <div>
                            <div><?= $count_too_big ?></div>
                        </div>
                    <?php elseif (!empty($email_count_too_big)): ?>
                        <div>
                            <div><?= $email_count_too_big ?></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div><!-- .login_form_bi_lw -->
        </div><!-- .login_form_fw -->

        <div class="login_copy login_copy_lw">
          <?= $this->include('loginauth/includes/copylogin') ?> 
        </div>
    </div><!-- #login-page -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js" integrity="sha512-WMEKGZ7L5LWgaPeJtw9MBM4i5w5OSBlSjTjCtSnvFJGSVD26gE5+Td12qN5pvWXhuWaWcVwF++F7aqu9cvqP0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/additional-methods.min.js" integrity="sha512-TiQST7x/0aMjgVTcep29gi+q5Lk5gVTUPE9XgN0g96rwtjEjLpod4mlBRKWHeBcvGBAEvJBmfDqh2hfMMmg+5A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function() {
            $("#form-login-register").validate({
                ignore: ".ignore",
                errorClass: "inp_invalid",
                validClass: "inp_success",
                errorElement: "span",
                rules: {
                    email: {
                        required: true,
                        maxlength: 200,
                        email: true
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