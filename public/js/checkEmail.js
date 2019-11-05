$(document).ready(function () {

    console.debug('document ready');

    $('input#email').change(function (e) {
        let email = $('input#email').val().trim();

        $.ajax({
            url: '/checkEmail',
            data: {email: email},
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                console.debug(data);
                if (data.userExist === true) {
                    $('#emailAlreadyUsed').removeClass('d-none');
                    $('#submit').attr('disabled', true);
                } else {
                    $('#emailAlreadyUsed').addClass('d-none');
                    $('#submit').attr('disabled', false);
                }
            }
        });
    });
});