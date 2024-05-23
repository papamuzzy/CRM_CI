<?= $this->extend('loginauth/layout/default') ?>

<?= $this->section('content') ?>

    <div id="login-page" class="section login_page login_page_lw pgs_register_account">
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
                            <div class="login_form_tt_lk">
                                <span>Don't have an Account? <a href="<?= base_url("auth/login") ?>">Log in</a></span>
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
                        <?= form_open($form_anchor, array('id' => 'form-login-register', 'class'=>'form_login_auth', 'autocomplete'=>'off')) ?> 
                            <div class="row form_row">
                                <div class="col form_col">
                                    <label for="сompany-name" class="form-label">Сompany Name</label>
                                    <input id="сompany-name" type="text" name="сompany_name" class="form-control form_input <?= (!empty($validation['сompany_name']) ? 'inp_invalid' : '') ?>" placeholder="Company Name" aria-label="Company Name" maxlength="300" required<?= ((!empty($form_data['сompany_name'])) ? ' value="' . esc($form_data['сompany_name']) . '"' : '') ?>>
                                    <?php if (!empty($validation['сompany_name'])) : ?>
                                        <span class="inp_invalid invalid_cs_inp"><?= esc($validation['сompany_name']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
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
                            <div class="row form_row form_login_button">
                                <div class="col form_col">
                                    <button type="submit" class="btn btn-primary"><span>Registration</span></button>
                                </div>
                            </div>
                        <?= form_close() ?><!-- #form-login-register -->
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
                    сompany_name: {
                        required: true,
                        minlength: 3,
                        maxlength: 300
                    },
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