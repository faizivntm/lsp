$(document).ready(function () {
    $('#togglePassword').click(function () {
        var passwordField = $('#password');
        var passwordFieldType = passwordField.attr('type');
        if (passwordFieldType === 'password') {
            passwordField.attr('type', 'text');
            $(this).text('Hide');
        } else {
            passwordField.attr('type', 'password');
            $(this).text('Show');
        }
    });
});


