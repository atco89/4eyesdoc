$(document).ready(function () {
    let dataTable = new DTable('#patients-table');
    let orderedByColumns = [1, 2, 6];
    let fixedColumns = [0, 9];

    dataTable.render(orderedByColumns, fixedColumns);
});