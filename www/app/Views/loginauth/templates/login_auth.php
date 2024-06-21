<?= $this->extend('loginauth/layout/default') ?>

<?= $this->section('content') ?>

<div id="login-page" class="section login_page login_page_mw pgs_login_auth">
    <div class="section_wdth login_form">
        <div class="login_form_bi">
            <div class="login_form_bi_f">
                <div class="login_form_tt">
                    <div class="login_form_logo">
                        <img src="<?= base_url("img/login-auth-logo.svg") ?>" title="Freedom Code Compliance - Login Auth" width="250">
                    </div>
                    <div class="login_form_tt_cn">
                        <div class="login_form_tt_n">
                            <span>Sign in to FCC</span>
                        </div>
                        <div class="login_form_tt_lk">
                            <span>New to FCC? <a href="<?= base_url("auth/register") ?>">Create an account</a></span>
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
                    <?= form_open($form_anchor, array('id' => 'form-login-auth', 'class'=>'form_login_auth', 'autocomplete'=>'off')) ?> 
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
                                <label for="input-pass" class="form-label">Password</label>
                                <input type="password" id="input-pass" name="password" class="form-control form_input form_input_pass <?= (!empty($validation['password']) ? 'inp_invalid' : '') ?>" placeholder="Password" aria-label="Password" maxlength="50" required value="">
                                <?php if (!empty($validation['password'])) : ?>
                                    <span class="inp_invalid invalid_cs_inp"><?= esc($validation['password']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row form_row form_row_cn">
                            <div class="col form_col">
                                <div class="form-check">
                                    <input class="form-check-input" name="login_auth_check_remember input_check_remember" type="checkbox" id="form-check-remember">
                                    <label class="form-check-label form_login_auth_check_remember" for="form-check-remember" checked>
                                        Remember me?
                                    </label>
                                </div>
                            </div>
                            <div class="col form_login_col_lnk">
                                <div class="form_login_lnk">
                                    <a href="<?= base_url("auth/password-reset") ?>">Forgot Password?</a>
                                </div>
                            </div>
                        </div>
                        <div class="row form_row form_login_button">
                            <div class="col form_col">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </div>
                    <?= form_close() ?><!-- #form-login-auth -->
                    <?php else: ?>
                        <div class="data_not_found_msg">
                            <span class="form_validation_msg_inp_invalid">Data not found</span>
                        </div>
                    <?php endif; ?>
                </div><!-- #login-form-auth -->
            </div>
        </div><!-- .login_form_bi -->
        <div class="login_copy login_copy_pt">
            <?= $this->include('loginauth/includes/copylogin') ?> 
        </div>

    </div><!-- .login_form -->
    <div class="section_wdth login_auth_slider_mn">
        <div class="login_auth_slider_lsc"> 
            <div id="login-auth-slider" class="login_auth_slider_lsc_cn">

                <div class="login_auth_slider_itm">
                    <div class="login_auth_slider_tt">
                        <span>Expert plan reviews & virtual inspections for florida contractors 1</span>
                    </div>
                    <div class="login_auth_slider_cn">
                        <p>Freedom Code Compliance is a private company which is licensed to perform the same plan review and building inspections as a building department normally would. Private Providers essentially operate as supplemental, private building departments alongside local municipal building departments.</p>
                    </div>
                </div><!-- .login_auth_slider_itm -->

                <div class="login_auth_slider_itm">
                    <div class="login_auth_slider_tt">
                        <span>Expert plan reviews & virtual inspections for florida contractors 2</span>
                    </div>
                    <div class="login_auth_slider_cn">
                        <p>Freedom Code Compliance is a private company which is licensed to perform the same plan review and building inspections as a building department normally would. Private Providers essentially operate as supplemental, private building departments alongside local municipal building departments.</p>
                    </div>
                </div><!-- .login_auth_slider_itm -->

                <div class="login_auth_slider_itm">
                    <div class="login_auth_slider_tt">
                        <span>Expert plan reviews & virtual inspections for florida contractors 3</span>
                    </div>
                    <div class="login_auth_slider_cn">
                        <p>Freedom Code Compliance is a private company which is licensed to perform the same plan review and building inspections as a building department normally would. Private Providers essentially operate as supplemental, private building departments alongside local municipal building departments.</p>
                    </div>
                </div><!-- .login_auth_slider_itm -->

            </div><!-- #login-auth-slider -->

            <div class="section_blog_post_sl_tbtn_itm">
                <div class="section_blog_post_sl_tabnav_dt section_blog_post_sl_cont_tabnav"></div>
            </div>
        </div><!-- .login_auth_slider -->
    </div><!-- .login_auth_slider_cn -->

</div><!-- #login-page -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js" integrity="sha512-WMEKGZ7L5LWgaPeJtw9MBM4i5w5OSBlSjTjCtSnvFJGSVD26gE5+Td12qN5pvWXhuWaWcVwF++F7aqu9cvqP0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/additional-methods.min.js" integrity="sha512-TiQST7x/0aMjgVTcep29gi+q5Lk5gVTUPE9XgN0g96rwtjEjLpod4mlBRKWHeBcvGBAEvJBmfDqh2hfMMmg+5A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js" integrity="sha512-HGOnQO9+SP1V92SrtZfjqxxtLmVzqZpjFFekvzZVWoiASSQgSr4cw9Kqd2+l8Llp4Gm0G8GIFJ4ddwZilcdb8A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function() {
            $('#login-auth-slider').slick({
                cssEase: 'ease-in-out',
                autoplay:true,
                autoplaySpeed:8000,
                dots:true,
                infinite:true,
                touchThreshold:10,
                speed:300,
                adaptiveHeight:true,
                arrows: false,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    fade: true,
                    appendDots: $('.section_blog_post_sl_tabnav_dt')
            });

            $("#form-login-auth").validate({
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