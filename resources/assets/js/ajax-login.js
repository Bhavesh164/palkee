/*******Check parent login****************/
function check_login() {
   //console.log(root+'home');
  // return false ;
    $('#loading').show();
    $('#error').hide();
    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        
        type: 'POST',
        header:{
          'X-CSRF-TOKEN': token
        },
        dataType: 'json',
        url: 'admin/checklogin',
        data: $('form#LoginForm').serialize(),
       // data: "email=" + $('#email').val() + "&password=" + $('#password').val()+"&remember_me=" + $('#remember_me').val(),
        success: function (response) {
            $('#loading').hide();
            if (response.success == 1) {
              // console.log(root+'home/index');
                window.location.href = 'admin/home/';
            }
            else
            {
                $('#error').css({'display': 'block'}).html(response.msg);
            }
            
        },
        error: function (response) {
            $('#error').css({'display': 'block'});
            $('#loading').hide();
        }
    });
    return false;
}


/*******Check parent login****************/
//function check_register() {
//    $('#loading').show();
//    $('#error').hide();
//    if(($('#email').val()=='') || ($('#password').val()=='') || ($('#password').val()!=$('#cpassword').val()))
//    {
//         $('#error').css({'display': 'block'}).html("All fields are required");
//         $('#loading').hide();
//    }
//    else
//    {
//        $.ajax({
//            type: 'POST',
//            url: root + 'check_register.php',
//            data: "email=" + $('#email').val() + "&password=" + $('#password').val(),
//            success: function (response) {
//                if (response == '1') {
//                    window.location.href = 'home.php';
//                }
//                else
//                {
//                    $('#error').css({'display': 'block'}).html(response);
//                }
//                $('#loading').hide();
//            },
//            error: function (response) {
//                $('#error').css({'display': 'block'}).html('Error');
//                $('#loading').hide();
//            }
//        });
//    }
//}

function check_forgot() {
    $('#loading').show();
    $('#error').hide();
    $.ajax({
        type: 'POST',
        url: root + 'auth/checkforgot',
        data: "email=" + $('#email1').val(),
        success: function (response) {
            $('#error1').css({'display': 'block'}).html(response);
            $('#loading1').hide();
        },
        error: function (response) {
            $('#error1').css({'display': 'block'}).html('Error');
            $('#loading1').hide();
        }
    });
  
}

function check_resend() {
    $('#loading').show();
    $('#error').hide();
    $.ajax({
        type: 'POST',
        url: root + 'check_resend.php',
        data: "email=" + $('#email').val(),
        success: function (response) {
            $('#error').css({'display': 'block'}).html(response);
            $('#loading').hide();
        },
        error: function (response) {
            $('#error').css({'display': 'block'}).html('Error');
            $('#loading').hide();
        }
    });
}