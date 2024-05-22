$(document).ready(function() {
    /*$("#form-login-auth").validate({
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
            industry_type: {
                required: true,
                maxlength: 80
            }
        },
        submitHandler: function(form) {
            $(form).find('button[type="submit"] span').html("Submitting ...");
            $(form).find('button[type="submit"]').prop("disabled", true);
            form.submit();
        }
    });*/

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
});