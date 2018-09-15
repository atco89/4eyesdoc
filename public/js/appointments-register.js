var app = new App;
var templateId = null;
var examinationId = null;

$(document)

    .ready(function () {

        app.renderDatePicker('#datepicker');

        $('.examination-details').popover();

        let dataTable = new DTable('#appointments-table');
        let orderByColumns = [[8, 'desc']];
        let fixedColumns = [0, 9, 10];

        dataTable.render(orderByColumns, fixedColumns);
    })

    .on('click', '.choose-template', function () {
        examinationId = $(this).data('examination-id');
    })

    .on('click', '#select-template', function () {
        templateId = $('#template').val();
        window.open('examinations/examination/' + templateId + '/' + examinationId, '_self');
    });