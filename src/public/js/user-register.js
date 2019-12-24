$(document).ready(function () {
    let dataTable = new DTable('#users-table');
    let orderedByColumns = [1, 2];
    let fixedColumns = [0, 9, 10];

    dataTable.render(orderedByColumns, fixedColumns);
});