function dodaj_respect(id) {
    var dugme = $(this);
    $.post("../ajax/respect.php", {
            id_track: id
        },
        function (odgovor) {
            if (odgovor != 1) {
                $('header').append("<div class='alert alert-danger'>Nije uspleo dodavanje respecta sorry</div>");
                return
            }
            var respectButton = $('#' + id)
            var respectAction = respectButton.html().trim()
            var respectSize = respectButton.siblings('.' + id).html().split(": ")[1];

            respectSize = Number(respectSize);

            if (respectAction === "respect") {
                respectButton.html('diss-respect');
                respectSize++;
            } else {
                respectButton.html('respect');
                respectSize--;
            }
            respectButton.siblings('.' + id).html('respect: ' + respectSize);
        }
    );
}

function obrisi_diss(obrisi_id) {
    var id = obrisi_id.substr(7);
    id = parseInt(id);
    var roditelj = $('.beef-parent-' + id);
    $.post("../ajax/obrisi.php", {
            track_id: id
        },
        function (odgovor) {
            if (odgovor == 1) {
                roditelj.before('<div class ="alert alert-success">Uspesno obrisan diss</div>');
                roditelj.remove();
            } else {
                roditelj.append('<div class ="alert alert-warning">Diss nije obrisan uspesno</div>');
            }
        });
}