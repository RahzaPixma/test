
function check_login()
{
    //Mengambil value dari input username & Password
    var username = $('#txt_username').val();
    var password = $('#txt_password').val();
    //Ubah alamat url berikut, sesuaikan dengan alamat script pada komputer anda
    var url_login    = 'login.php';
    var url_admin    = '../index.php';
     
    //Ubah tulisan pada button saat click login
    $('#btnLogin').attr('value','Processing ...');
     
    //Gunakan jquery AJAX
    $.ajax({
        url     : url_login,
        //mengirimkan username dan password ke script login.php
        data    : 'var_usn='+username+'&var_pwd='+password, 
        //Method pengiriman
        type    : 'POST',
        //Data yang akan diambil dari script pemroses
        dataType: 'html',
        //Respon jika data berhasil dikirim
        success : function(pesan){
            if(pesan=='ptime2015'){
                //Arahkan ke halaman admin jika script pemroses mencetak kata ok
               window.location = url_admin;
            }
            else{
                //Cetak peringatan untuk username & password salah
                //alert(pesan);
				 window.location = 'login_index.php?error=1';
//			   document.getElementById('id_error').style.visibility = 'visible';
 //               $('#btnLogin').attr('value','Cuba lagi ...');
            }
        },
    });
}
