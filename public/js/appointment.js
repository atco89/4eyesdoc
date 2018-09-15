var app = new App;
var fullCalendar = new FullCalendar(app);

$(document)

    .ready(function () {
        fullCalendar.examination = $('#examination').val();
        fullCalendar.startDateTime = $('#start-date-time').val();
        fullCalendar.availableDoctors();
        fullCalendar.render('#calendar');
    })

    .on('click', '#store-event', function () {
        fullCalendar.patient = $('#patient').val();
        fullCalendar.examination = $('#examination').val();
        fullCalendar.doctor = $('#doctor').val();
        fullCalendar.startDateTime = $('#start-date-time').val();
        fullCalendar.storeEvent();
    })

    .on('click', '#save-changes', function () {
        fullCalendar.examination = $('#examination').val();
        fullCalendar.doctor = $('#doctor').val();
        fullCalendar.examinationStatus = $('#examination-status').val();
        fullCalendar.startDateTime = $('#start-date-time').val();
        fullCalendar.endDateTime = $('#end-date-time').val();
        fullCalendar.updateEvent();
    })

    .on('changed.bs.select', '#examination', function () {
        fullCalendar.examination = $(this).val();
        fullCalendar.startDateTime = $('#start-date-time').val();
        fullCalendar.availableDoctors();

        let name = $(this).find('option:selected').text();
        let description = $(this).find('option:selected').data('description');
        let price = $(this).find('option:selected').data('price');

        if ($(this).val() !== '') {
            $('#examination-details')
                .html('<p class="title">' + name + '</p>')
                .append('<p>' + description + '</p>')
                .append('<p class="price">Cena pregleda: ' + price + ' RSD</p>');
        }
    })

    .on('click', '#show-exam-details', function () {
        let selector = $('#examination');
        let selected = selector.find('option:selected');
        let name = selected.text();
        let description = selected.data('description');
        let price = selected.data('price');

        if (selector.val() !== '') {
            $('#examination-details')
                .html('<p class="title">' + name + '</p>')
                .append('<p>' + description + '</p>')
                .append('<p class="price">Cena pregleda: ' + price + ' RSD</p>');
        }
    });