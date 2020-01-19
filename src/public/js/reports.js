$(document)

    .ready(function () {
        var order_by = [2, 1];
        var fix_columns = [0];

        let data_table = new DTable('#report-table');
        data_table.render(order_by, fix_columns);
    })

    .on('click', '#chart-button', function (e) {
        e.preventDefault();

        let reports_module = new ReportsModule();
        reports_module.chart($(this).attr('href'));
    })

    .on('click', '#apply-filters', function (e) {
        e.preventDefault();

        var start_date = $('#start-date').val()
            .toString()
            .trim();
        var end_date = $('#end-date').val()
            .toString()
            .trim();

        let reports_module = new ReportsModule();
        $.ajax(reports_module.filter($(this).attr('href'), start_date, end_date, function (response) {
            $('#report-table-container').html(response.responseText);
            let data_table = new DTable('#report-table');

            var order_by = [2, 1];
            var fix_columns = [0];

            data_table.render(order_by, fix_columns);
        }));
    });