var app = new App;
var deleteScheduleHref = null;

$(document)

    .ready(function () {
        /*===== render date picker =====*/
        app.renderDatePicker('#schedule-date', true);
        app.renderDatePicker('#period-start-date');
        app.renderDatePicker('#period-end-date');

        /*===== render time picker =====*/
        app.renderTimePicker('#start-time');
        app.renderTimePicker('#end-time');

        /*===== data table =====*/
        let dataTable = new DTable("#staff-schedule-table");
        let orderedByColumns = [
            [4, 'desc'],
            [5, 'desc']
        ];
        let fixedColumns = [0, 9];
        dataTable.render(orderedByColumns, fixedColumns);
    })

    .on('click', '.delete-scedule', function () {
        deleteScheduleHref = $(this).data('href');
        app.showModal('administration/user/schedule/modal');
    })

    .on('click', '#delete-schedule', function () {
        window.open(deleteScheduleHref, '_self');
    });