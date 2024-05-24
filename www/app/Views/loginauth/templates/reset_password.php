<?= $this->extend('loginauth/layout/default') ?>

<?= $this->section('content') ?>

    <div id="login-page" class="section login_page login_page_lw pgs_reset_password">
        <div class="section_wdth login_form_fw">
            <div class="login_form_bi_lw">
                <div class="login_form_bi_f">
                    <div class="login_form_tt">
                        <div class="login_form_logo">
                            <img src="<?= base_url("img/login-crtacc-logo.svg") ?>" title="Freedom Code Compliance - Reset Password" width="175">
                        </div>
                        <div class="login_form_tt_cn">
                            <div class="login_form_tt_n">
                                <span>Reset Password</span>
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
                            <input type="hidden" name="user_id" value="<?= $user_id; ?>">
                            <div class="row form_row">
                                <div class="col form_col">
                                    <label for="email" class="form-label">Email</label>
                                    <input id="email" type="email" name="email" class="form-control form_input form_input_email <?= (!empty($validation['email']) ? 'inp_invalid' : '') ?>" placeholder="Email" aria-label="Email" maxlength="250" required value="">
                                    <?php if (!empty($validation['email'])) : ?>
                                        <span class="inp_invalid invalid_cs_inp"><?= esc($validation['email']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row form_row">
                                <div class="col form_col">
                                    <label for="input-pass" class="form-label">New Password</label>
                                    <input type="password" id="input-pass" name="new_password" class="form-control form_input form_input_pass <?= (!empty($validation['new_password']) ? 'inp_invalid' : '') ?>" placeholder="Password" aria-label="Password" maxlength="50" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required value="">
                                    <?php if (!empty($validation['new_password'])) : ?>
                                        <span class="inp_invalid invalid_cs_inp"><?= esc($validation['new_password']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row form_row">
                                <div class="col form_col">
                                    <label for="input-pass-confirm" class="form-label">Confirm New Password </label>
                                    <input type="password" id="input-pass-confirm" name="confirm_password" class="form-control form_input form_input_pass <?= (!empty($validation['confirm_password']) ? 'inp_invalid' : '') ?>" placeholder="Confirm Password" aria-label="Confirm Password" maxlength="50" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required value="">
                                    <?php if (!empty($validation['confirm_password'])) : ?>
                                        <span class="inp_invalid invalid_cs_inp"><?= esc($validation['confirm_password']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="row form_row">
                                <div class="col form_col">
                                    <div id="message-passw-check" class="message_passw_check">
                                        <div class="message_passw_check_tt">
                                            <span>Password must contain the following:</span>
                                        </div>
                                        <div class="message_passw_check_ds">
                                            <span id="message-letter-check" class="message_passw_lbl message_passw_invalid">A lowercase letter</span>
                                            <span id="message-capital-check" class="message_passw_lbl message_passw_invalid">A capital (uppercase) letter</span>
                                            <span id="message-number-check" class="message_passw_lbl message_passw_invalid">A number</span>
                                            <span id="message-length-check" class="message_passw_lbl message_passw_invalid">Minimum 8 characters</span>
                                        </div>
                                    </div>
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
                    let validCheckSm = validListCheck();

                    if (validCheckSm) {
                        $(form).find('button[type="submit"] span').html("Submitting ...");
                        $(form).find('button[type="submit"]').prop("disabled", true);
                        form.submit();
                    } else {
                        $("#input-pass").focus();
                    }   
                }
            });
        });
        
        var inputPass = document.getElementById("input-pass");
        var messageletter = document.getElementById("message-letter-check");
        var messagecapital = document.getElementById("message-capital-check");
        var messagenumber = document.getElementById("message-number-check");
        var messagelength = document.getElementById("message-length-check");

        inputPass.onfocus = function() {
            document.getElementById("message-passw-check").style.opacity = "1";
        }

        inputPass.onblur = function() {
            document.getElementById("message-passw-check").style.display = "0.7";
        }

        // When the user starts to type something inside the password field
        inputPass.onkeyup = function() {
            validListCheck();
        }

        function validListCheck() {
            var validCheck = true;

            // Validate lowercase letters
            var lowerCaseLetters = /[a-z]/g;
            if(inputPass.value.match(lowerCaseLetters)) {  
                messageletter.classList.remove("message_passw_invalid");
                messageletter.classList.add("message_passw_valid");
            } else {
                messageletter.classList.remove("message_passw_valid");
                messageletter.classList.add("message_passw_invalid");

                validCheck = false;
            }
            
            // Validate capital letters
            var upperCaseLetters = /[A-Z]/g;
            if(inputPass.value.match(upperCaseLetters)) {  
                messagecapital.classList.remove("message_passw_invalid");
                messagecapital.classList.add("message_passw_valid");
            } else {
                messagecapital.classList.remove("message_passw_valid");
                messagecapital.classList.add("message_passw_invalid");

                validCheck = false;
            }

            // Validate numbers
            var numbers = /[0-9]/g;
            if(inputPass.value.match(numbers)) {  
                messagenumber.classList.remove("message_passw_invalid");
                messagenumber.classList.add("message_passw_valid");
            } else {
                messagenumber.classList.remove("message_passw_valid");
                messagenumber.classList.add("message_passw_invalid");

                validCheck = false;
            }
            
            // Validate length
            if(inputPass.value.length >= 8) {
                messagelength.classList.remove("message_passw_invalid");
                messagelength.classList.add("message_passw_valid");
            } else {
                messagelength.classList.remove("message_passw_valid");
                messagelength.classList.add("message_passw_invalid");

                validCheck = false;
            }

            return validCheck;
        }
    </script>

<?= $this->endSection() ?>