/**
 * @author Piotr Kubica
 * http://www.doublebow.net
 * June 5, 2011
 * 
 * @class TableRowSelector
 * @version 1.0
 * TableRowSelector allows selecting rows by definig custom selecting event,
 * getting data from table or searching for givent index by row/column content. 
 * The selectable JQuery elements are restricted to HTML row tag tr
 * and are checked after passing to the TableRowSelector constructor
 *
 * @constructor
 * @param {JQuery} selectableObjects rows that should be selectable
 * @param {string} eventName name of JQuery event that will be used for selecting rows
 * @param {function(selectedElem, selectedIndex)} selFun function taking 2 parameters used as a callback after selection event is fired. Should implement the 'select' behavior
 * @param {function(deselectedElem, deselectedIndex)} deselFun function taking 2 parameters used as a callback after selection event is fired. Should implement the 'deselect' behavior
 *
 * @throws {string} thrown if selectableObjects contain tags other than TR or no elements selected
 */
function TableRowSelector(selectableObjects, eventName, selFun, deselFun, triggerOnCreate) {

    // PRIVATE members
    // triggering selection is disabled by default
    triggerOnCreate = triggerOnCreate == null ? false : triggerOnCreate;
    var _objs = selectableObjects;              // JQuery object with selected elements
    var _selEvent = eventName || null;          // JQuery event that triggers selection
    var _selFun = selFun || null;               // function triggered on new selected object after changing selection
    var _deselFun = deselFun || null;           // function triggered on prevoius selected obiject after changing selection
    var _lastElem = null;                       // last element that has been selected
    var _currIdx = -1;                          // index of current selected row

    /**
     * @private
     * function called from bottom (scroll down)
     */
    function init() { 
        if(_objs.not('tr').size() > 0) { // checking if elements are row tags TR
            throw "InvalidArgument [class TableRowSelector] selectableObjects contain tags other than TR!"
        }
        if(_objs.size() <= 0) {
            throw "InvalidArgument [class TableRowSelector] selectableObjects contains no elements";
        }
        if(_selEvent != null && _selFun != null && _deselFun != null) {
            this.bindSelectEvent(_selEvent, _selFun, _deselFun);

            if(triggerOnCreate) {
                this.triggerRowSelection(0);
            }
        }
    };
    
    // PUBLIC interface
    /**
     * Returns number of rows
     *
     * @return {integer} row count
     */
    this.length = function(){
        return _objs.length;
    };

    /**
     * Returns number of columns
     *
     * @return {integer} column count
     */
    this.columnCount = function(){
        return _objs.first().children('td').length;
    };

    /**
     * Returns row index (starting with 0) of the previous selected row
     * or -1 if no rows were selected
     *
     * @returns {integer} last selected row index or -1 if previously not selected
     */
    this.lastRowIndex = function(){
        return _lastElem != null ? _lastElem.index() : -1;
    };

    /**
     * Returns current row index (starting with 0) of the selected row
     * or -1 if no row is selected
     *
     * @returns {integer} current selected row index or -1 if no rows selected
     */
    this.rowIndex = function(){
        return _currIdx;
    };

    /**
     * Searches each row in given column for given content and returns index of row or
     * negative value (-1) if matching content was not found
     *
     * @param {integer} columnIdx index of column which rows are searched for content
     * @param {string} content the content in column you are looking for
     * @returns {integer} index of row (starting with 0) or -1 if content in column was not found
     *
     * @throws {string} thrown if columnIdx if out of range or arguments are not appropriate
     */
    this.rowIndexByColumnContent = function(columnIdx, content){
        columnIdx = parseInt(columnIdx);

        if(isNaN(columnIdx) || content == null) {
            throw "InvalidArgument [class TableRowSelector, method rowIndexByColumnContent]";
        }
        if(columnIdx < 0 || columnIdx >= this.columnCount()) {
            throw "IndexOutOfBounds [class TableRowSelector, method rowIndexByColumnContent] columnIdx out of range";
        }
        var val = -1;
        
        _objs.each(function(i){
            $(this).children('td').eq(columnIdx).each(function(j) {
                if($(this).text() == content) {
                    val = i;
                    return;
                }
            });

            if(val >= 0) {
                return;
            }
        });
        return val;
    };

    /**
     * Searches each column in given row for given content and returns index of column or
     * negative value (-1) if matching content was not found
     * 
     * @param {integer} rowIdx index of row which columns are searched for content
     * @param {string} content the content in row you are looking for
     * @returns {integer} index of column (starting with 0) or -1 if content in row was not found
     *
     * @throws {string} thrown if rowIdx if out of range or arguments are not appropriate
     */
    this.columnIndexByRowContent = function(rowIdx, content){
        rowIdx = parseInt(rowIdx);
        
        if(isNaN(rowIdx) || content == null) {
            throw "InvalidArgument [class TableRowSelector, method columnIndexByRowContent]";
        }
        if(rowIdx < 0 || rowIdx >= this.length()) {
            throw "IndexOutOfBounds [class TableRowSelector, method columnIndexByRowContent] rowIdx out of range";
        }
        var val = -1;

        _objs.eq(rowIdx).children('td').each(function(i){
            if($(this).text() == content.toString()) {
                val = i;
                return;
            }
        });
        return val;
    };

    /**
     * Returns content of a given row as an array
     *
     * @param {integer} idx index of row
     * @returns {array(string)} an array containing content of row
     *
     * @throws {string} thrown if idx is invalid or is out of range
     */
    this.rowContent = function(idx){
        idx = parseInt(idx);

        if(isNaN(idx)) {
            throw "InvalidArgument [class TableRowSelector, method rowContent]";
        }
        if(idx < 0 || idx >= this.length()) {
            throw "IndexOutOfBounds [class TableRowSelector, method rowContent] idx out of range";
        }
        var a = new Array();

        _objs.eq(idx).children('td').each(function(i){
            a.push($(this).text());
        });
        return a;
    };

    /**
     * Returns content of a given column as an array
     *
     * @param {integer} idx index of column
     * @returns {array(string)} an array containing content of column
     *
     * @throws {string} thrown if idx is invalid or is out of range
     */
    this.columnContent = function(idx){
        idx = parseInt(idx);

        if(isNaN(idx)) {
            throw "InvalidArgument [class TableRowSelector, method columnContent]";
        }
        if(idx < 0 || idx >= this.columnCount()) {
            throw "IndexOutOfBounds [class TableRowSelector, method columnContent] idx out of range";
        }
        var a = new Array();

        _objs.each(function(i){
            a.push($(this).children('td').eq(idx).text());
        });
        return a;
    };

    /**
     * Returns table cell content for given row and column indexes
     *
     * @param {integer} rowIdx row index of table (starting with 0)
     * @param {integer} colIdx column index of table (starting with 0)
     * @returns {string} content of the cell
     *
     * @throws {string} thrown if rowIdx or colIdx is invalid or is out of range
     */
    this.content = function(rowIdx, colIdx) {
        rowIdx = parseInt(rowIdx);
        colIdx = parseInt(colIdx);

        if(isNaN(rowIdx) || isNaN(colIdx)) {
            throw "InvalidArgument [class TableRowSelector, method content] invalid argument";
        }
        if(colIdx < 0 || colIdx >= this.columnCount()) {
            throw "IndexOutOfBounds [class TableRowSelector, method content] colIdx out of range";
        }
        if(rowIdx < 0 || rowIdx >= this.length()) {
            throw "IndexOutOfBounds [class TableRowSelector, method content] rowIdx out of range";
        }
        return _objs.eq(rowIdx).children('td').eq(colIdx).text();
    };

    /**
     * Triggers the selection of a given row
     *
     * @param {integer} idx row index
     * @returns {void}
     *
     * @throws {string} thrown if idx is invalid or is out of range
     */
    this.triggerRowSelection = function(idx){
        idx = parseInt(idx == null ? 0 : idx);
        
        if(isNaN(idx)) {
            throw "InvalidArgument [class TableRowSelector, method triggerRowSelection] invalid argument";
        }
        if(idx < 0 || idx >= this.length()) {
            throw "IndexOutOfBounds [class TableRowSelector, method triggerRowSelection] idx out of range";
        }
        _objs.eq(idx).trigger(_selEvent);
    };

    /**
     * Binds an JQuery event (event types: http://api.jquery.com/bind/) that
     * will trigger function selFun with parameters containing new selected
     * element and it's index and deselFun with parameters containing previously
     * selected element and it's index if givent event is fired up
     *
     * @param {string} eventName the eventType in JQuery
     * @param {function} selFun function triggered with parameters containing new selected element and it's index
     * @param {function} deselFun function triggered with parameters containing previously selected element and it's index
     * @returns {void}
     *
     * @throws {string} thrown if all arguments not specified or valid
     */
    this.bindSelectEvent = function(eventName, selFun, deselFun){
        if(eventName == null || selFun == null || deselFun == null) {
            throw "InvalidArgument [class TableRowSelector, method bindSelectEvent]";
        }
        _selFun = selFun;
        _deselFun = deselFun;
        _objs.unbind(eventName);
        _selEvent = eventName;
        
        _objs.bind(_selEvent, function(e) {
            if(_lastElem != null && $(this).index() != _lastElem.index()) {
                _deselFun(_lastElem, $(this).index());
            }
            if(_lastElem == null || $(this).index() != _lastElem.index()) {
                _currIdx = $(this).index();
                _selFun($(this), _currIdx);
            }
            _lastElem = $(this);
        });
    };

    /**
     * Unbinds given event from objects. The eventName is a JQuery eventType
     * (http://api.jquery.com/bind/)
     *
     * @param {string} eventName event that should be unbound
     * @returns {void}
     *
     * @throws {string} thrown if eventName not specified or valid
     */
    this.unbindSelectEvent = function(eventName){
        if(eventName == null) {
            throw "InvalidArgument [class TableRowSelector, method unbindSelectEvent]";
        }
        _objs.unbind(eventName);
    };

    // init
    init.call(this);
}