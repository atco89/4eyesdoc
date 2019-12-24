var app = new App;
var cardboard = new Cardboard;

/* ========== contact person ========== */

$(document)

    .ready(function () {
        app.renderDatePicker('#date-of-birth');
        app.loadAssociateField('#recommendation-type');
    })

    .on('click', '#add-contact-person', function () {
        app.showModal('partial/contact-person/modal', function () {
            $('.selectpicker').selectpicker('render');
        });
    })

    .on('click', '#store-contact-person', function () {
        cardboard.name = $('#name').val();
        cardboard.surname = $('#surname').val();
        cardboard.dialNumber = $('#dial_number').val();
        cardboard.phoneNumber = $('#phone_number').val();
        cardboard.storeContactPerson();
    })

    /* ========== profession ========== */

    .on('click', '#add-profession', function () {
        app.showModal('partial/profession/modal');
    })

    .on('click', '#store-profession', function () {
        cardboard.professionName = $('#profession').val().trim();
        cardboard.storeProfession();
    })

    /* ========== associate ========== */

    .on('click', '#add-associate', function () {
        app.showModal('partial/associate/modal', function () {
            $('.selectpicker').selectpicker('render');
        });
    })

    .on('click', '#store-associate', function () {
        cardboard.name = $('#name').val().trim();
        cardboard.email = $('#email').val().trim();
        cardboard.dialNumber = $('#dial-number').val().trim();
        cardboard.phoneNumber = $('#phone-number').val().trim();
        cardboard.storeAssociate();
    })

    /* ========== download examinations ========== */

    .on('click', '#download-examination', function () {
        $.post('api/examination/preview/save', {
            id: $(this).data('id')
        });
    })

    .on('changed.bs.select', '#recommendation-type', function () {
        app.loadAssociateField($(this));
    })

    .on('keyup', '[data-convert-letters]', function () {
        $(this).val(app.transliterate($(this).val()));
    })

    .on('keyup', '[data-to-lower]', function () {
        $(this).val($(this).val().toLowerCase());
    })

    .on('click', '#examinations-list li a', function () {
        $('#examinations-list').find(' li').find('a').each(function () {
            $(this).removeClass('active');
        });
        $(this).addClass('active');

        $('.pdf-viewer-container').html('<iframe src="' + $(this).data('href') + '" class="pdf-viewer"></iframe>');
        $('#download-examination').data('id', $(this).data('id')).attr('disabled', false);
    });