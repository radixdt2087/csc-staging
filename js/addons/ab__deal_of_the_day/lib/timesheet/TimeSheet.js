
(function ($) {

var CSheetCell = function(opt){

var cellPrivate = $.extend({
state : 0,
toggleCallback : false,
settingCallback : false
},opt);

this.toggle = function(){
cellPrivate.state = cellPrivate.state>0 ? cellPrivate.state-1 : cellPrivate.state+1;
if(cellPrivate.toggleCallback){
cellPrivate.toggleCallback(cellPrivate.state);
}
}

this.set = function(state){
cellPrivate.state = state==0 ? 0 : 1;
if(cellPrivate.settingCallback){
cellPrivate.settingCallback();
}
}

this.get = function(){
return cellPrivate.state;
}
}

var CSheet = function(opt){

var sheetPrivate = $.extend({
dimensions : undefined,
sheetData : undefined,
toggleCallback : false,
settingCallback : false
},opt);
sheetPrivate.cells = [];

sheetPrivate.initCells = function(){
var rowNum = sheetPrivate.dimensions[0];
var colNum = sheetPrivate.dimensions[1];
if(sheetPrivate.dimensions.length==2 && rowNum>0 && colNum>0){
for(var row= 0,curRow = []; row<rowNum; ++row){
curRow = [];
for(var col=0; col<colNum; ++col){
curRow.push(new CSheetCell({
state : sheetPrivate.sheetData ? (sheetPrivate.sheetData[row]?parseInt(sheetPrivate.sheetData[row][col]):0) : 0
}));
}
sheetPrivate.cells.push(curRow);
}
}else{
throw new Error("CSheet : wrong dimensions");
}
}

sheetPrivate.areaOperate = function(area,opt){

var rowCount = sheetPrivate.cells.length;
var colCount = sheetPrivate.cells[0] ? sheetPrivate.cells[0].length : 0;
var operationArea = $.extend({
startCell : [0,0],
endCell : [rowCount-1,colCount-1]
},area);
var isSheetEmpty = rowCount==0 || colCount==0;
var isAreaValid = operationArea.startCell[0]>=0 && operationArea.endCell[0]<=rowCount-1 &&
operationArea.startCell[1]>=0 && operationArea.endCell[1]<=colCount-1 &&
operationArea.startCell[0]<=operationArea.endCell[0] && operationArea.startCell[1]<=operationArea.endCell[1];
if(!isAreaValid){
throw new Error("CSheet : operation area is invalid");
}else if(!isSheetEmpty){
for(var row=operationArea.startCell[0]; row<=operationArea.endCell[0]; ++row){
for(var col=operationArea.startCell[1]; col<=operationArea.endCell[1]; ++col){
if(opt.type=="toggle"){
sheetPrivate.cells[row][col].toggle();
}else if(opt.type=="set"){
sheetPrivate.cells[row][col].set(opt.state);
}
}
}
}
}
sheetPrivate.initCells();

this.toggle = function(toggleArea){
sheetPrivate.areaOperate(toggleArea,{type:"toggle"});
if(sheetPrivate.toggleCallback){
sheetPrivate.toggleCallback();
}
}

this.set = function(state,settingArea){
sheetPrivate.areaOperate(settingArea,{type:"set",state:state});
if(sheetPrivate.settingCallback){
sheetPrivate.settingCallback();
}
}

this.getCellState = function(cellIndex){
return sheetPrivate.cells[cellIndex[0]][cellIndex[1]].get();
}

this.getRowStates = function(row){
var rowStates = [];
for(var col=0; col<sheetPrivate.dimensions[1]; ++col){
rowStates.push(sheetPrivate.cells[row][col].get());
}
return rowStates;
}

this.getSheetStates = function(){
var sheetStates = [];
for(var row= 0,rowStates = []; row<sheetPrivate.dimensions[0]; ++row){
rowStates = [];
for(var col=0; col<sheetPrivate.dimensions[1]; ++col){
rowStates.push(sheetPrivate.cells[row][col].get());
}
sheetStates.push(rowStates);
}
return sheetStates;
}
}
$.fn.TimeSheet = function(opt){

var thisSheet = $(this);
if(!thisSheet.is("TBODY")){
throw new Error("TimeSheet needs to be bound on a TBODY element");
}
var sheetOption = $.extend({
data: {},
sheetClass: "",
start: false,
end : false,
remarks : null
}, opt);
if(!sheetOption.data.dimensions || sheetOption.data.dimensions.length!==2 || sheetOption.data.dimensions[0]<0 || sheetOption.data.dimensions[1]<0){
throw new Error("TimeSheet : wrong dimensions");
}
var operationArea = {
startCell : undefined,
endCell : undefined
};
var sheetModel = new CSheet({
dimensions : sheetOption.data.dimensions,
sheetData : sheetOption.data.sheetData ? sheetOption.data.sheetData : undefined
});

var initSheet = function(){
thisSheet.html("");
thisSheet.addClass("TimeSheet");
if(sheetOption.sheetClass){
thisSheet.addClass(sheetOption.sheetClass);
}
initColHeads();
initRows();
repaintSheet();
};

var initColHeads = function(){
var colHeadHtml = '<tr>';
for(var i=0,curColHead=''; i<=sheetOption.data.dimensions[1]; ++i){
if(i===0){
curColHead = '<td class="TimeSheet-head" style="'+(sheetOption.data.sheetHead.style?sheetOption.data.sheetHead.style:'')+'">'+sheetOption.data.sheetHead.name+'</td>';
}else{
curColHead = '<td title="'+(sheetOption.data.colHead[i-1].title ? sheetOption.data.colHead[i-1].title:"")+'" data-col="'+(i-1)+'" class="TimeSheet-colHead '+(i===sheetOption.data.dimensions[1]?'rightMost':'')+'" style="'+(sheetOption.data.colHead[i-1].style ? sheetOption.data.colHead[i-1].style : '')+'">'+sheetOption.data.colHead[i-1].name+'</td>';
}
colHeadHtml += curColHead;
}
if(sheetOption.remarks){
colHeadHtml += '<td class="TimeSheet-remarkHead">'+sheetOption.remarks.title+'</td>';
}
colHeadHtml += '</tr>';
thisSheet.append(colHeadHtml);
};

var initRows = function(){
for(var row=0,curRowHtml=''; row<sheetOption.data.dimensions[0]; ++row){
curRowHtml='<tr class="TimeSheet-row">'
for(var col= 0, curCell=''; col<=sheetOption.data.dimensions[1]; ++col){
if(col===0){
curCell = '<td title="'+(sheetOption.data.rowHead[row].title ? sheetOption.data.rowHead[row].title:"")+'"class="TimeSheet-rowHead '+(row===sheetOption.data.dimensions[0]-1?'bottomMost ':' ')+'" style="'+(sheetOption.data.rowHead[row].style ? sheetOption.data.rowHead[row].style : '')+'">'+sheetOption.data.rowHead[row].name+'</td>';
}else{
curCell = '<td class="TimeSheet-cell '+(row===sheetOption.data.dimensions[0]-1?'bottomMost ':' ')+(col===sheetOption.data.dimensions[1]?'rightMost':'')+'" data-row="'+row+'" data-col="'+(col-1)+'"></td>';
}
curRowHtml += curCell;
}
if(sheetOption.remarks){
curRowHtml += '<td class="TimeSheet-remark '+(row===sheetOption.data.dimensions[0]-1?'bottomMost ':' ')+'">'+sheetOption.remarks.default+'</td>';
}
curRowHtml += '</tr>';
thisSheet.append(curRowHtml);
}
};

var cellCompare = function(cell1,cell2){
var sum1 = cell1[0] + cell1[1];
var sum2 = cell2[0] + cell2[1];
if((cell1[0]-cell2[0])*(cell1[1]-cell2[1])<0){
return {
topLeft : cell1[0]<cell2[0] ? [cell1[0],cell2[1]] : [cell2[0],cell1[1]],
bottomRight : cell1[0]<cell2[0] ? [cell2[0],cell1[1]] : [cell1[0],cell2[1]]
};
}
return {
topLeft : sum1<=sum2 ? cell1 : cell2,
bottomRight : sum1>sum2 ? cell1 : cell2
};
};

var repaintSheet = function(){
var sheetStates = sheetModel.getSheetStates();
thisSheet.find(".TimeSheet-row").each(function(row,rowDom){
var curRow = $(rowDom);
curRow.find(".TimeSheet-cell").each(function(col,cellDom){
var curCell = $(cellDom);
if(sheetStates[row][col]===1){
curCell.addClass("TimeSheet-cell-selected");
}else if(sheetStates[row][col]===0){
curCell.removeClass("TimeSheet-cell-selected");
}
});
});
};

var removeSelecting = function(){
thisSheet.find(".TimeSheet-cell-selecting").removeClass("TimeSheet-cell-selecting");
};

var cleanRemark = function(){
thisSheet.find(".TimeSheet-remark").each(function(idx,ele){
var curDom = $(ele);
curDom.prop("title","");
curDom.html(sheetOption.remarks.default);
});
};

var startSelecting = function(ev,startCel){
operationArea.startCell = startCel;
if(sheetOption.start){
sheetOption.start(ev);
}
};

var duringSelecting = function(ev,topLeftCell,bottomRightCell){
var curDom = $(ev.currentTarget);
if(isSelecting && curDom.hasClass("TimeSheet-cell") || isColSelecting && curDom.hasClass("TimeSheet-colHead")){
removeSelecting();
for(var row=topLeftCell[0]; row<=bottomRightCell[0]; ++row){
for(var col=topLeftCell[1]; col<=bottomRightCell[1]; ++col){
$($(thisSheet.find(".TimeSheet-row")[row]).find(".TimeSheet-cell")[col]).addClass("TimeSheet-cell-selecting");
}
}
}
};

var afterSelecting = function(ev,targetArea){
var curDom = $(ev.currentTarget);
var key = $(ev.which);
var targetState = undefined;
if(key[0]===1){ targetState = 1;}
else if(key[0]===3){ targetState = 0;}
if(isSelecting && curDom.hasClass("TimeSheet-cell") || isColSelecting && curDom.hasClass("TimeSheet-colHead")){
sheetModel.set(targetState,{
startCell : targetArea.topLeft,
endCell : targetArea.bottomRight
});
removeSelecting();
repaintSheet();
if(sheetOption.end){
sheetOption.end(ev,targetArea);
}
}else{
removeSelecting();
}
isSelecting = false;
isColSelecting = false;
operationArea = {
startCell : undefined,
endCell : undefined
}
};
var isSelecting = false; 
var isColSelecting = false; 
var eventBinding = function(){

thisSheet.undelegate(".umsSheetEvent");

thisSheet.delegate(".TimeSheet-cell","mousedown.umsSheetEvent",function(ev){
var curCell = $(ev.currentTarget);
var startCell = [curCell.data("row"),curCell.data("col")];
isSelecting = true;
startSelecting(ev,startCell);
});

thisSheet.delegate(".TimeSheet-cell","mouseup.umsSheetEvent",function(ev){
if(!operationArea.startCell){
return;
}
var curCell = $(ev.currentTarget);
var endCell = [curCell.data("row"),curCell.data("col")];
var correctedCells = cellCompare(operationArea.startCell,endCell);
afterSelecting(ev,correctedCells);
});

thisSheet.delegate(".TimeSheet-cell","mouseover.umsSheetEvent",function(ev){
if(!isSelecting){
return;
}
var curCell = $(ev.currentTarget);
var curCellIndex = [curCell.data("row"),curCell.data("col")];
var correctedCells = cellCompare(operationArea.startCell,curCellIndex);
var topLeftCell = correctedCells.topLeft;
var bottomRightCell = correctedCells.bottomRight;
duringSelecting(ev,topLeftCell,bottomRightCell);
});

thisSheet.delegate(".TimeSheet-colHead","mousedown.umsSheetEvent",function(ev){
var curColHead = $(ev.currentTarget);
var startCell = [0,curColHead.data("col")];
isColSelecting = true;
startSelecting(ev,startCell);
});

thisSheet.delegate(".TimeSheet-colHead","mouseup.umsSheetEvent",function(ev){
if(!operationArea.startCell){
return;
}
var curColHead = $(ev.currentTarget);
var endCell = [sheetOption.data.dimensions[0]-1,curColHead.data("col")];
var correctedCells = cellCompare(operationArea.startCell,endCell);
afterSelecting(ev,correctedCells);
});

thisSheet.delegate(".TimeSheet-colHead","mouseover.umsSheetEvent",function(ev){
if(!isColSelecting){
return;
}
var curColHead = $(ev.currentTarget);
var curCellIndex = [sheetOption.data.dimensions[0]-1,curColHead.data("col")];
var correctedCells = cellCompare(operationArea.startCell,curCellIndex);
var topLeftCell = correctedCells.topLeft;
var bottomRightCell = correctedCells.bottomRight;
duringSelecting(ev,topLeftCell,bottomRightCell);
});

thisSheet.delegate("td","contextmenu.umsSheetEvent",function(ev){
return false;
});
};
initSheet();
eventBinding();
var publicAPI = {

getCellState : function(cellIndex){
return sheetModel.getCellState(cellIndex);
},

getRowStates : function(row){
return sheetModel.getRowStates(row);
},

getSheetStates : function(){
return sheetModel.getSheetStates();
},

setRemark : function(row,html){
if($.trim(html)!==''){
$(thisSheet.find(".TimeSheet-row")[row]).find(".TimeSheet-remark").prop("title",html).html(html);
}
},

clean : function(){
sheetModel.set(0,{});
repaintSheet();
cleanRemark();
},

getDefaultRemark : function(){
return sheetOption.remarks.default;
},

disable : function(){
thisSheet.undelegate(".umsSheetEvent");
},

enable : function(){
eventBinding();
},

isFull : function(){
for(var row=0; row<sheetOption.data.dimensions[0]; ++row){
for(var col=0; col<sheetOption.data.dimensions[1]; ++col){
if(sheetModel.getCellState([row,col])===0){
return false;
}
}
}
return true;
}
};
return publicAPI;
}
})(jQuery);
