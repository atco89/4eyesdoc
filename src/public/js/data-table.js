/**
 * @param selector
 * @constructor
 */
function DTable(selector) {

    this.app = new App;

    /**
     * @param fixed
     * @return array|null
     */
    this.loadFixedColumns = function (fixed) {
        let data = [];
        for (let i = 0; i < fixed.length; i++) {
            data.push({
                searchable: false,
                orderable: false,
                targets: fixed[i]
            });
        }
        return (data.length > 0) ? data : null;
    };

    /**
     * @param order
     * @return array|null
     */
    this.loadOrderColumns = function (order) {
        return (order.length > 0) ? order : null;
    };

    /**
     * @param order
     * @param fixed
     * @return void
     */
    this.render = function (order, fixed) {
        let that = this;
        let t = $(selector).DataTable({
            initComplete: that.app.showLoader(false),
            columnDefs: that.loadFixedColumns(fixed),
            pageLength: 10,
            language: {
                decimal: "",
                emptyTable: "Nema podataka",
                info: "Prikaz _START_ do _END_ od _TOTAL_ rekorda",
                infoEmpty: "Prikaz 0 do 0 od 0 rekorda",
                infoFiltered: "(pretraga primenjena na _MAX_ rekorda)",
                infoPostFix: "",
                thousands: ",",
                lengthMenu: "Prikaz _MENU_ rekorda",
                loadingRecords: "Ucitavanje...",
                processing: "Procesiranje...",
                search: "Pretraga:",
                zeroRecords: "Nema podataka",
                paginate: {
                    first: "Prva",
                    last: "Poslednja",
                    next: "Sledeca",
                    previous: "Prethodna"
                },
                aria: {
                    sortAscending: ": activate to sort column ascending",
                    sortDescending: ": activate to sort column descending"
                }
            },
            lengthMenu: [
                [5, 10, 20, 50, 100, 500, 2000, -1],
                [5, 10, 20, 50, 100, 500, 2000, "Sve"]
            ],
            order: that.loadOrderColumns(order)
        });

        t.on('order.dt search.dt', function () {
            t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();
    };

}