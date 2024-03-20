// let pass1 = $('#pass1').val();
// let pass2 = $('#pass2').val();

// if (pass1 != pass2) {
//   $('.box-p').html(`
//     <input type="submit" class="btn btn-lg btn-primary" name="ubah" value="Ubah"><br>

//     `);
// }

$('#loader').show();
$('.container').hide();
// $('.navbar').hide();
// $('.search').hide();
$(document).ready(function () {
  // $('#cari').on('keyup', function () {
  //memanggil ajax menggunakan load

  //$('#box-s').load('cari.php?cari=' + $('#cari').val());

  //menggunakan $.get

  //   $.get('cari.php?cari=' + $('#cari').val(), function (data) {
  //     $('#box-s').html(data);
  //   });
  // });

  $('#loader').hide();
  $('.container').show();
  // $('.navbar').show();
  // $('.search').show();
});
