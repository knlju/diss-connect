var slajdIndeks = 0;

function slajder() {
  $(".slike").hide();
  if (slajdIndeks > 3)
    slajdIndeks = 0;
  $(".slike").each(function(index) {
    if (index === slajdIndeks) {
      $(this).show();
    }
  });
  slajdIndeks++;
  setTimeout(slajder, 3000);
}

function pripremi_i_posalji() {
  if ($('#sadrzaj').val()) {
    $('#sadrzaj').val($('#sadrzaj').val().split('\n').join('<br>'));
    $('#diss-track-forma').submit();
  }
}

function dodaj_lajnu() {
  $('.diss-lajne').append("<div class = 'alert alert-info lajna' color = 'white' onclick = 'ubaci_lajnu(this);'>" +
    ($('#dodaj-lajnu').val()).split('\n').join('<br>') +
    "</div>");
  $('#dodaj-lajnu').val('');
}

function ubaci_lajnu(elem) {
  $('#sadrzaj').val(function(i, text) {
    return text + '\n' + $(elem).html().split('<br>').join('\n');
  });
  $(elem).remove();
}

function proveri_sifre() {
  var sifra1 = $("#new-password");
  var sifra2 = $("#new-password-confirm");

  if ((!sifra1.val()) && (!sifra2.val())) {
    $("#poruka-register").html("");
    $("#poruka-register").removeClass("alert alert-danger alert-success");
    $("input[type=submit]").attr("disabled", true);
  } else if (sifra1.val() !== sifra2.val()) {
    $("#sifre").addClass("form-group");
    sifra1.addClass("is-invalid");
    sifra2.addClass("is-invalid");
    $("input[type=submit]").attr("disabled", true);
    $("#poruka-register").html("Sifre se ne poklapaju");
    $("#poruka-register").addClass("alert alert-danger").removeClass("alert-success");
  } else {
    $("#sifre").addClass("form-group");
    sifra1.addClass("is-valid").removeClass('is-invalid');
    sifra2.addClass("is-valid").removeClass('is-invalid');
    $("input[type=submit]").attr("disabled", false);
    $("#poruka-register").html("Sifre se poklapaju");
    $("#poruka-register").addClass("alert alert-success").removeClass("alert-danger");
  }

  if (sifra1.val().length < 6) {
    $("#poruka-register-1").html("Sifra mora imati barem 6 karaktera");
    $("#poruka-register-1").addClass("alert alert-danger").removeClass("alert-success");
    $("input[type=submit]").attr("disabled", true);
  } else {
    $("#poruka-register-1").html("");
    $("#poruka-register-1").removeClass("alert alert-danger");
    if (sifra1.val() === sifra2.val()) {
      $("input[type=submit]").attr("disabled", false);
    }
  }
}

function proveri_duzinu_sifre() {
  var sifra = $('#password');
  if (sifra.val().length < 6) {
    sifra.addClass("is-invalid").removeClass('is-valid');
    $("input[type=submit]").attr("disabled", true);
    $("#poruka-login").html("Sifra mora imati barem 6 karaktera");
    $("#poruka-login").addClass("alert alert-danger");
  } else {
    $("#poruka-login").html("");
    $("#poruka-login").removeClass("alert alert-danger");
    $("input[type=submit]").attr("disabled", false);
  }
}



$('document').ready(function() {
  slajder();
});