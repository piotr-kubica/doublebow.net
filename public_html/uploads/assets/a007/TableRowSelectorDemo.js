var trs1 = null,
    trs2 = null,
    trs3 = null,
    trs4 = null;

function showSelection(elem, i) {
    $(elem).addClass('selected');
}

function hideSelection(elem, i) {
    $(elem).removeClass('selected');
}

function showRowIndexes(elem, i) {
    showSelection(elem, i);
    $("#demo-2-output-last").html(trs2.lastRowIndex());
    $("#demo-2-output-current").html(trs2.rowIndex());
};

function showContent() {
    var demoForm = $('#demo-3-form');
    var row = demoForm.find('#rowIdx').val();
    var col = demoForm.find('#colIdx').val();

    try {
        var rowContent = trs3.rowContent(row).toString();
        $('#demo-3-output-row').text(rowContent);
    } catch(err) {
        $('#demo-3-output-row').text(err);
    }
    try {
        var colContent = trs3.columnContent(col).toString();
        $('#demo-3-output-col').text(colContent);
    } catch(err) {
        $('#demo-3-output-col').text(err);
    }
    try {
        var cellContent = trs3.content(row, col);
        $('#demo-3-output-cell').text(cellContent);
    } catch(err) {
        $('#demo-3-output-cell').text(err);
    }
};

function searchRow() {
    var rowc = $('#demo-4-form').find('#rowc').val();
    var rowi = $('#demo-4-form').find('#rowi').val();

    try {
        var colIndex = trs4.columnIndexByRowContent(rowi, rowc);
        $('#demo-4-output-col').text(colIndex);
    } catch(err) {
        $('#demo-4-output-col').text(err);
    }
};

function searchCol() {
    var colc = $('#demo-4-form').find('#colc').val();
    var coli = $('#demo-4-form').find('#coli').val();

    try {
        var rowIndex = trs4.rowIndexByColumnContent(coli, colc);
        $('#demo-4-output-row').text(rowIndex);
    } catch(err) {
        $('#demo-4-output-row').text(err);
    }
};

$(function(){
    // DEMO 1
    var t1 = $('table#demo-1-tab tbody').find('tr');
    trs1 = new TableRowSelector(t1, 'mouseover', showSelection, hideSelection, true);
    $("#demo-1-output-rows").html(trs1.length());
    $("#demo-1-output-columns").html(trs1.columnCount());

    // DEMO 2
    var t2 = $('table#demo-2-tab tbody').find('tr');
    trs2 = new TableRowSelector(t2, 'mouseover', showRowIndexes, hideSelection);
    trs2.triggerRowSelection();

    // DEMO 3
    var t3 = $('table#demo-3-tab tbody').find('tr');
    trs3 = new TableRowSelector(t3);

    // DEMO 4
    var t4 = $('table#demo-4-tab tbody').find('tr');
    trs4 = new TableRowSelector(t4);
});