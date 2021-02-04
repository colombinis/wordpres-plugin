// (function ($) {
//     "use strict";
//     // javascript code here. i.e.: $(document).ready( function(){} );
// })(jQuery);

console.log('desde sacsilogin')

jQuery(document).ready(function ($) {


    // Perform AJAX login on form submit
    $('form#login').on('submit', function (e) {
        $('form#login p.status').show().text(ajax_login_object.loadingmessage);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_login_object.ajaxurl,
            data: {
                'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
                'username': $('form#login #username').val(),
                'password': $('form#login #password').val(),
                'security': $('form#login #security').val()
            },
            success: function (data) {
                $('form#login p.status').text(data.message);
                if (data.loggedin == true) {
                    console.log(ajax_login_object.extradata);
                    alert('Aca estamos logueados y redirigimos o actualizamos contexto.... Otra opcion es crear y personalizar un callback post login Success')
                }else{
                    alert('NO estamos logueados');
                }
            }
        });
        e.preventDefault();
    });

});