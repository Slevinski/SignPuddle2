//common variables
cat = 0 
sym = 0
sbSymbol = 0
builds=1
buildPos=105
cSymbol= 0
colorize = 0
sOff=50
sOn=1000

//placement variables
signboxW = 250 
signboxH = 250
specialH = 290
//
keyW = 60
keyH = 60
keyPad = 5
//
cmdX = 70
padX = 0
padY = 0
wLane = 250
wLanes = 75
offsetX = 50 + 50 + wLane + 2*wLanes
offsetY = 100 
buildSpace = 50
//start main code
window.onresize = Resize
ResID=null

if (!Array.prototype.indexOf)
{
  Array.prototype.indexOf = function(elt /*, from*/)
  {
    var len = this.length >>> 0;

    var from = Number(arguments[1]) || 0;
    from = (from < 0)
         ? Math.ceil(from)
         : Math.floor(from);
    if (from < 0)
      from += len;

    for (; from < len; from++)
    {
      if (from in this &&
          this[from] === elt)
        return from;
    }
    return -1;
  };
}

var imgChecker = new imgChecking();


function imgChecking(){
  var imgood = new Array();//list of good chars
  var imbad = new Array();//list of bad chars
  var imload = new Array();//list of loading chars
  var imloaded = new Array();//list of loaded chars
  var imsize = new Array();//list of image sizes

  this.good = function(cSym){//called when a img is good
    imloaded.push(cSym);
    imgood.push(cSym);
    var limg = new Image();
    limg.src = imgSrc(cSym);
    imsize[cSym] = {w:limg.width,h:limg.height};
  }

  this.bad = function(cSym){//called when a img is bad
    imloaded.push(cSym);
    imbad.push(cSym);
  }

  this.check = function(list){//called to check iswa images
    var iswa = new Array();

    list=list.replace(/\r/g,"")
    var buildList=list.split('\n')
    var build_num = 0;
    while(build_num<buildList.length) {
      if (buildList[build_num]!=""){
        build=buildList[build_num]
        sign=build.split(",")
        cnt=sign.length-sign.length%3
        for (j=0;j<cnt;j++){
          cSym=sign[j];
          iswa.push(cSym);
          j++;
          j++;
        }
      }
      build_num++
    }
    for (var i=0;i<iswa.length;i++){
      cSym = iswa[i];
      //skip if good, bad, or loading
      if (imgood.indexOf(cSym)>-1){continue;}
      if (imbad.indexOf(cSym)>-1){continue;}
      if (imload.indexOf(cSym)>-1){continue;}
      imload.push(cSym);
      new loadImg(cSym);
    }
  }

  this.loading = function(){//loading check for any sym
    if (imload.length>imloaded.length) {return true;} else {return false;}
  }

  this.isGood = function(cSym){//loading check
    if (imgood.indexOf(cSym)==-1) {
      return false;
    } else {
      return true;
    }
  }

  this.isBad = function(cSym){//loading check
    if (imbad.indexOf(cSym)==-1) {return false;} else {return true;}
  }

  this.size = function(cSym){//return size of image
    return imsize[cSym];
  }

}

//load image function with good and bad checking
function loadImg(cSym){
  this.good = function(){
    imgChecker.good(cSym);
  }
  this.bad = function(){
    imgChecker.bad(cSym);
  }
  var limg = new Image();
  limg.onload=this.good;
  limg.onerror=this.bad;
  limg.src = imgSrc(cSym);
}

function imgSrc(cSym){
  return swis_glyph + "?sss=" + cSym;
}

function Resize(){
    if  (ResID) clearTimeout(ResID);
    ResID = setTimeout('DoResize();',200);
}

function DoResize(){
//    resizecode.
  repositionLayers()
}
ns4 = (document.layers)? true:false
ie4 = (document.all)? true:false

DynAPI.onLoad = function() {
  create = new DynLayer(null,padX,padY,720+offsetX,1400)
  DynAPI.document.addChild (create)
  signBox = new box()
  create.addChild (signBox)
  for (var i=0; i<6;i++){
    for (var j=0; j<16;j++){
      var keyX=new key(i,j)
      create.addChild (keyX)
      keyX.refresh()
      DragEvent.setDragBoundary(keyX)
      DragEvent.enableDragEvents(keyX)
    }
  }
  if (isSmall()) {
    control = new DynLayer(null,offsetX + signboxW + 30,offsetY,220,320) 
  } else {
    control = new DynLayer(null,offsetX,offsetY + signboxH + 20,220,320)
  }
  create.addChild (control)
  updateKeys(0)
  floatLayer()
  dOutput = new DynLayer(null,0,100,offsetX,1100)
  create.addChild(dOutput)
  dTitle= new DynLayer(null,250,0,820,100)
  create.addChild(dTitle)
  buildControl() 
  buildOutput() 
  buildTitle()
  selectedBlink()
  if (getBuildString()) {prepBuildString()}
}

function isSmall(){
  pageY=is.ie?document.body.scrollTop:window.pageYOffset
winH = (!ie4)? window.innerHeight-16 : document.body.offsetHeight-20
if (winH<590) {
  return true 
} else {
  return false 
}
}

function repositionLayers(){
  iY = offsetY + 20
  if (isSmall()) { iY = iY + specialH + 20;}
  iKey = 1;
  for (var i=0; i<6;i++){
    for (var j=0; j<16;j++){
      create.children[iKey].moveTo(null,iY+j*keyW)
      create.children[iKey].yp=iY+j*keyW
      iKey++
    }
  }

  if (isSmall()) {
    control.moveTo(offsetX + signboxW + 30,offsetY)
  } else {
    control.moveTo(offsetX,signBox.y + signboxH + 10)
  }

}

function selectedBlink(){
  if (sbSymbol>0) {
    id=sbSymbol-1
    selectedOff(id)
    setTimeout('selectedOn(id)',sOff)
  }
  setTimeout('selectedBlink()',sOn)
}

function selectedOff(id){
if (signBox.pic.children.length>id){
  signBox.pic.children[id].setVisible(false)
}
}

function selectedOn(id){
if (signBox.pic.children.length>id){
  signBox.pic.children[id].setVisible(true)
}
}

function floatLayer(){
  topY = 100
  pageY=is.ie?document.body.scrollTop:window.pageYOffset
  topY = topY-pageY;
  if (topY<25) {topY=25;}
  signBox.moveBy(null,((topY+pageY)-signBox.y)/2)
  if (!isSmall()) {
    control.moveBy(null,((topY+signboxH + 10+pageY)-control.y)/2)
  }
  setTimeout('floatLayer()',25)
}

function goTop(){
cat=0
sym=0
updateKeys(0)
}

function goUp(){
  if ((sym==0) && (cat>0)) {
    cat=0
    sym=0
    updateKeys(0)
  } else if ((sym>0) && (cat>0)) {
    temp=cat
    cat=0
    sym=0
    updateKeys(temp)
  }
}

function clearAll(){
  for (var i=signBox.pic.children.length-1; i>=0;i--){
    signBox.pic.children[i].deleteFromParent()
  }
}

function buildTitle(){
    sTitle= "<table>"
    sTitle += "<tr>"
    sTitle += "    <td rowspan=2>"
    sTitle += "      <img src=\"library/icons/SignTextGraphic.png\">"
    sTitle += "      &nbsp; &nbsp; &nbsp;"
    sTitle += "      <img src=\"library/icons/SignTextSW.png\">"
    sTitle += "      &nbsp; &nbsp; &nbsp;"
    sTitle += "    </td>"
    sTitle += "    <td>"
    sTitle += "      <font size=\"6\" face=\"Arial, Helvetica, sans-serif\">SignText</font>"
    sTitle += "      <font size=\"2\">&#8482;</font>"
    sTitle += "    </td>"
    sTitle += "  </tr>"
    sTitle += "  <tr>"
    sTitle += "    <td>"
    sTitle += "      &nbsp; &nbsp; &nbsp;"
    sTitle += "      <font color=\"#CC3300\" size=\"3\" face=\"Arial, Helvetica, sans-serif\">"
    sTitle += "      <strong>"
    sTitle += "      Editor"
    sTitle += "      </strong></font>"
    sTitle += "    </td>"
    sTitle += "  </tr>"
    sTitle += "</table>"
  dTitle.setHTML(sTitle)
}

function buildControl() {
  //buildOutput()
  build=""
  for (var i=0; i<signBox.pic.children.length;i++){
    build=build + signBox.pic.children[i].sss + "," + Math.floor(signBox.pic.children[i].getX()) + "," + Math.floor(signBox.pic.children[i].getY()) + ","; 
  }
  iGL = "<a href=\"#\" onclick=\"goTop();return false;\" >" + iGLi + "</a>";
  iPR = "<a href=\"#\" onclick=\"goUp();return false;\">" + iPRi + "</a>";
  iSN = "<a href=\"#\" onclick=\"symbolNext();return false;\" >" + iSNi + "</a>";

  iCS = "<a href=\"#\" onclick=\"symbolCopy();return false;\" >" + iCSi + "</a>";
  iDS = "<a href=\"#\" onclick=\"symbolDelete();return false;\" >" + iDSi + "</a>";
  iCA = "<a href=\"#\" onclick=\"clearAll();return false;\" >" + iCAi + "</a>";

  iVR = "<a href=\"#\" onclick=\"symbolVar();return false;\" >" + iVRi + "</a>";
  iMS = "<a href=\"#\" onclick=\"symbolFlip();return false;\" >" + iMSi + "</a>";
  iFS = "<a href=\"#\" onclick=\"symbolFill();return false;\" >" + iFSi + "</a>";

  iPO = "<a href=\"#\" onclick=\"symbolTop();return false;\" >" + iPOi + "</a>";
  iRCC = "<a href=\"#\" onclick=\"symbolRotate(1);return false;\" >" + iRCCi + "</a>";
  iRC = "<a href=\"#\" onclick=\"symbolRotate(-1);return false;\" >" + iRCi + "</a>";
  

  iLL = "<a href=\"\" onclick=\"addOutput(-1);return false;\" >" + iLLi + "</a>";
  iLC = "<a href=\"\" onclick=\"addOutput(0);return false;\" >" + iLCi + "</a>";
  iLR = "<a href=\"\" onclick=\"addOutput(1);return false;\" >" + iLRi + "</a>";
  
  atab="<table cellpadding=2 border=0><tr>";
  atab=atab + "<tr><td>" +iGL+ "</td><td>" +iPR+ "</td><td>" +iSN+ "</td></tr>";
  atab=atab + "<tr><td>" +iCS+ "</td><td>" +iDS+ "</td><td>" +iCA+ "</td></tr>";
  atab=atab + "<tr><td>" +iVR+ "</td><td>" +iMS+ "</td><td>" +iFS+ "</td></tr>";
  atab=atab + "<tr><td>" +iPO+ "</td><td>" +iRCC+ "</td><td>" +iRC+ "</td></tr>";
  atab=atab + "<tr><td>" +iLL+ "</td><td>" +iLC+ "</td><td>" +iLR+ "</td></tr>";
  atab=atab + "</table>";
  control.setHTML(atab)
}

function buildOutput(){
 aOut = "<center>"
 aOut = aOut + "<table cellpadding=4><tr><td>"
 if (sid){
   aOut=aOut+"<form name='options' action=\"canvas.php\" method=\"POST\"><input type=\"hidden\"  name=\"action\" value=\"Save\"><button type=submit>" + iSAVEi + "</button><input type=hidden name=list><input type=hidden name=ui value=\"" + uiVal + "\"><input type=hidden name=sgn value=\"" + sgnVal + "\"><input type=hidden name=sid value=\"" + sid + "\"></form>"
  } else {
   aOut=aOut+"<form name='options' action=\"signtextsave.php\" method=\"POST\"><input type=\"hidden\"  name=\"action\" value=\"Save\"><button type=submit>" + iSAVEi + "</button><input type=hidden name=list><input type=hidden name=ui value=\"" + uiVal + "\"><input type=hidden name=sgn value=\"" + sgnVal + "\"><input type=hidden name=sid value=\"" + sid + "\"></form>"
  }
 aOut = aOut + "</td></tr></table>"
  gloss="sign-"+builds;
  if (document.info) {
    if (document.info.gloss.value!="") {
      gloss=document.info.gloss.value
    }
  }
 aOut += "<form name='info'>"
 aOut += "<input size=30 type='text' name='gloss' value='" + gloss +"'>"
 aOut += "</form></center>"
 
  dOutput.setHTML(aOut)
}

function getBuildString()
{
    return bLoad
}

var iLoadCnt = 0;
var dLoad = new DynLayer()
function prepBuildString(){
  imgChecker.check(getBuildString());
  setTimeout('loadBuildString();',100);
  dOutput.addChild(dLoad);
}

function loadBuildString(){
  if (imgChecker.loading()){
    setTimeout("loadBuildString();",100);
    iLoadCnt++;
    itest = 1+ parseInt(iLoadCnt/10);
    if (itest==4){itest=1;iLoadCnt=0;}
    var iHTML = "Loading";
    for (var i=1; i<=itest; i++) {
      iHTML += '.';
    }
    dLoad.setHTML(iHTML);
    return;
  }
  dOutput.removeChild(dLoad);
  str = getBuildString()
  str=str.replace(/\r/g,"")
  var buildList=str.split("\n")
  var build_num=0;
  while(build_num<buildList.length)
  {
    if (buildList[build_num]!=""){
      build=buildList[build_num]
      addOutputBuild(build)
    }
    build_num++
  }
}

function readOutput (){
  strRO = document.options.list.value
  strRO=strRO.replace(/\r/g,"")
  return strRO
  
}

function readOutputSign (signNum){
  str = readOutput()
  str = str.split("\n")
  return str[signNum]
}

function appendOutput(signBld){
  listOut = readOutput()  
  if (listOut!="") {listOut=listOut+"\n"}
  listOut=listOut + signBld
  writeOutput (listOut)
}

function writeOutput (strOut){
  document.options.list.value = strOut
}

function writeOutputSign (signNum,signBld){
  str = readOutput()
  str = str.split("\n")
  var listOut="";
  var build_num=0;
  while(build_num<str.length)
  {
    if (listOut!="") {listOut=listOut+"\n"}
    if (build_num==signNum){
      listOut = listOut + signBld
    } else {
      listOut = listOut + str[build_num]
    }
  }
  writeOutput(listOut)
}

function deleteOutput(x){
  for (var i=0;i<x;i++){
    numSigns =dOutput.children.length 
    xHeight = dOutput.children[numSigns-2].getHeight()
    builds--
    buildPos = buildPos - buildSpace - xHeight
    dOutput.children[numSigns-1].deleteFromParent()
    dOutput.children[numSigns-2].deleteFromParent()
  }

  str = readOutput()
  str = str.split("\n")
  var listOut="";
  var build_num=0;
  while(build_num<str.length-x)
  {
    if (listOut!="") {listOut=listOut+"\n"}
    listOut = listOut + str[build_num]
    build_num++
  }
  writeOutput(listOut)
}

function buildCurrent(lane){
  build=""
  if (signBox.pic.children.length){
    for (var i=0; i<signBox.pic.children.length;i++){
      build=build + signBox.pic.children[i].sss + "," + Math.round(signBox.pic.children[i].getX()) + "," + Math.round(signBox.pic.children[i].getY()) + ","
    }
    build = build + document.info.gloss.value + "," + lane;
  }
  return build
}

function addOutput(lane){
if (signBox.pic.children.length){
  builds++
  build=buildCurrent(lane)
  appendOutput(build);
  document.info.gloss.value="sign-"+builds
  addOutputSign(build)
  buildControl()
  clearAll()
}
}

function addOutputBuild(build){
  builds++
  appendOutput(build);
  document.info.gloss.value="sign-"+builds
  addOutputSign(build)
  buildControl()
}

function addOutputSign(build){
  sign=build.split(",")
  cnt=sign.length-sign.length%3
  var lane=0
  if ((cnt+1)<sign.length) {lane=parseInt(sign[cnt+1])}  
  cLane = wLane + wLanes*lane-50
  cxMin = 0
  cxMax = 0

  minX = Math.round(sign[1])
  maxX = minX + 2
  minY = Math.round(sign[2])
  maxY = minY + 2
  for (j=0;j<cnt;j++){
    sssymbol=sign[j];
    j++;
    x=sign[j];
    j++;
    y=sign[j];

    iS = new Image()
    iS.src = swis_glyph + "?sss=" + sssymbol
    iW = iS.width || 50;
    iH = iS.height || 50;
    iX = Math.round(x)
    iY = Math.round(y)
    if (iX<minX) { minX = iX}
    if (iY<minY) { minY = iY}
    if ( (iX+iW) > maxX ) { maxX = iX+iW}
    if ( (iY+iH) > maxY ) { maxY = iY+iH}
    testCat = sssymbol.substr(0,2)
    testGrp = sssymbol.substr(0,5)
    if ((testCat=="04") || (testGrp=="05-01")) {
      cx = iX +  iW/2
      if (cxMin==0) {
        cxMin = cx
        cxMax = cx
      }
      if (cx<cxMin) { cxMin = cx}
      if (cx>cxMax) { cxMax = cx}

    }
  }

  // average head center
  cx = (cxMin + cxMax)/2

  if (cx) {
    adjX = cLane - cx 
  } else {
    adjX = (wLane -(maxX-minX))/2 - minX
    adjX = adjX + (wLanes*(lane+1))
  }
  var dlyr = new DynLayer(null,cmdX,buildPos,offsetX, maxY-minY);

  //second pass through to build sign
  for (j=0;j<cnt;j++){
    sssymbol=sign[j];
    j++;
    x=sign[j];
    j++;
    y=sign[j];

    var dSym = new DynLayer(null, Math.round(x)+adjX,Math.round(y)-minY,100,100)
    dSym.setHTML("<img alt='" + sssymbol + "' src='" + swis_glyph + "?sss=" + sssymbol + "'>")
    dlyr.addChild(dSym)
  }

  dOutput.addChild(dlyr);

  var dlyrCMD = new DynLayer(null,0,buildPos,60, 60);
  iSL = "<a href=\"\" onclick=\"signShift(" + (builds-2) + ",-1);return false;\" ><img border=0 alt=\"Sign Left\" src=\"library/icons/left.png\"></a>";
  iSU = "<a href=\"\" onclick=\"signDown(" + (builds-3) + ");return false;\" ><img border=0 alt=\"Sign Up\" src=\"library/icons/up.png\"></a>";
  iSR = "<a href=\"\" onclick=\"signShift(" + (builds-2) + ",1);return false;\" ><img border=0 alt=\"Sign Right\" src=\"library/icons/right.png\"></a>";
  
  
  iLS = "<a href=\"\" onclick=\"signLoad(" + (builds-2) + ");return false;\" ><img border=0 alt=\"Load Sign\" src=\"library/icons/load.png\"></a>";
  iSD = "<a href=\"\" onclick=\"signDown(" + (builds-2) + ");return false;\" ><img border=0 alt=\"Sign Down\" src=\"library/icons/down.png\"></a>";
  iDS = "<a href=\"\" onclick=\"signDelete(" + (builds-2) + ");return false;\" ><img border=0 alt=\"Delete Sign\" src=\"library/icons/delete.png\"></a>";

  iRS = "<a href=\"\" onclick=\"signReplace(" + (builds-2) + ");return false;\" ><img border=0 alt=\"Replace Sign\" src=\"library/icons/replace.png\"></a>";
  iAS = "<a href=\"\" onclick=\"signAt(" + (builds-3) + ");return false;\" ><img border=0 alt=\"Above Sign\" src=\"library/icons/above.png\"></a>";
  iBS = "<a href=\"\" onclick=\"signAt(" + (builds-2) + ");return false;\" ><img border=0 alt=\"Below Sign\" src=\"library/icons/below.png\"></a>";
  
  atab="<table cellpadding=2 border=0><tr>";
  atab=atab + "<tr><td>" +iLS+ "</td><td>" +iSU+ "</td><td>" +iDS+ "</td></tr>";
  atab=atab + "<tr><td>" +iSL+ "</td><td>" +iRS+ "</td><td>" +iSR+ "</td></tr>";
  atab=atab + "<tr><td>" +iAS+ "</td><td>" +iSD+ "</td><td>" +iBS+ "</td></tr>";
  atab=atab + "</table>";
  dlyrCMD.setHTML(atab)

  dOutput.addChild(dlyrCMD);

  buildPos+=maxY-minY+buildSpace
  if (buildPos>dOutput.getHeight()) {
    newH = buildPos+250
    create.setHeight(newH + 100)
    dOutput.setHeight(newH)
  }
}

function signLoad (signNum){
  str = readOutput()
  str = str.split("\n")
  clearAll()
  loadSymbols(str[signNum])
}

function signDelete (signNum){
  str = readOutput()
  str = str.split("\n")
  var listOut="";
  var build_num=signNum+1;
  deleteOutput(str.length-signNum)
  while(build_num<str.length)
  {
    addOutputBuild(str[build_num])
    build_num++
  }
}

function signReplace(signNum){
  curSign = buildCurrent(0)
  if (curSign){
    str = readOutput()
    str = str.split("\n")
    var build_num=signNum+1;
    deleteOutput(str.length-signNum)
    addOutputBuild(curSign)
    clearAll()
    while(build_num<str.length)
    {
      addOutputBuild(str[build_num])
      build_num++
    }
  }
}

function signShift(signNum,shift){
  str = readOutput()
  str = str.split("\n")
  build = str[signNum]
  //now break apart and determine lane
  sign=build.split(",")
  cnt=sign.length-sign.length%3
  var lane=0
  var gloss=""
  if ((cnt+1)<sign.length) {lane=parseInt(sign[cnt+1])}  
  if (cnt<sign.length) {gloss=sign[cnt]}  
  lane = lane + shift
  //verify new lane is OK
  if ((lane>-2)&&(lane<2)){
    //rebuild current sign
    build=""
    for (j=0;j<cnt;j++){
      sssymbol=sign[j];
      j++;
      x=sign[j];
      j++;
      y=sign[j];
      build=build+sssymbol+","+x+","+y+","
    }
    build = build + gloss + "," + lane;
    var build_num=signNum+1;
    deleteOutput(str.length-signNum)
    addOutputBuild(build)
    while(build_num<str.length)
    {
      addOutputBuild(str[build_num])
      build_num++
    }
  }
}

function signDown(signNum){
  str = readOutput()
  str = str.split("\n")
  if ((signNum>=0) && (signNum+1<str.length)){
    tmp1 = str[signNum]
    tmp2 = str[signNum+1]
    var build_num=signNum+2;
    deleteOutput(str.length-signNum)
    addOutputBuild(tmp2)
    addOutputBuild(tmp1)
    while(build_num<str.length)
    {
      addOutputBuild(str[build_num])
      build_num++
    }
  }
}

function signAt(signNum){
  curSign = buildCurrent(0)
  if (curSign){
    str = readOutput()
    str = str.split("\n")
    var build_num=signNum+1;
    deleteOutput(str.length-signNum-1)
    addOutputBuild(curSign)
    clearAll()
    while(build_num<str.length)
    {
      addOutputBuild(str[build_num])
      build_num++
    }
  }
}

function addSymbol(sss,x,y,kcat,ksym){
  var symbolX=new symbol(sss,x-1,y-1,kcat,ksym)
  signBox.pic.addChild (symbolX)
  DragEvent.setDragBoundary(symbolX)
  DragEvent.enableDragEvents(symbolX)
  //sbSymbol=signBox.pic.children.length
  sbSymbol=0
  buildControl()
}

function loadSymbols(loading){
  loading=loading.split(",");
  for (var i=0; i<loading.length-2;i++){
    sss= loading[i]
    i++ 
    x = loading[i] 
    i++ 
    y = loading[i]
    for (var j=0;j<keys.length;j++){
      for (var k=0;k<keys[j].length;k++){
        sssS = sss.substr(0,12)
        if (keys[j][k]==sssS) {
          tCat=j+1
          tSym=k+1
        } 
      } 
    }
    addSymbol(sss,x,y,tCat,tSym)
    pickColor("#" + color) 
    //signBox.pic.children[i].updateSymbol(sss) 
  }
}

function symbolTop(){
  if (sbSymbol>0) {
    isss = signBox.pic.children[sbSymbol-1].sss
    ix = signBox.pic.children[sbSymbol-1].getX()+1
    iy = signBox.pic.children[sbSymbol-1].getY()+1
    icat = signBox.pic.children[sbSymbol-1].cat
    isym = signBox.pic.children[sbSymbol-1].sym
    icolor = signBox.pic.children[sbSymbol-1].color
    symbolDelete()
    addSymbol(isss,ix,iy,icat,isym)
    pickColor("#" + icolor)
  }
}

function symbolCopy(){
  if (sbSymbol>0) {
    isss = signBox.pic.children[sbSymbol-1].sss
    ix = signBox.pic.children[sbSymbol-1].getX()+1
    iy = signBox.pic.children[sbSymbol-1].getY()+1
    icat = signBox.pic.children[sbSymbol-1].cat
    isym = signBox.pic.children[sbSymbol-1].sym
    icolor = signBox.pic.children[sbSymbol-1].color
    addSymbol(isss,ix+5,iy+5,icat,isym)
    pickColor("#" + icolor)
  }
}

function symbolDelete() {
  if (sbSymbol>0) {
    signBox.pic.children[sbSymbol-1].deleteFromParent()
    sbSymbol=0
    buildControl()
  }
}

function symbolNext() {
  if (sbSymbol>0) {
    sbSymbol++
    if (sbSymbol>signBox.pic.children.length){sbSymbol=1}
  }
  if(signBox.pic.children.length>0 && sbSymbol==0){sbSymbol=signBox.pic.children.length}

}

function symbolFlip() {
  if(signBox.pic.children.length>0 && sbSymbol==0){sbSymbol=signBox.pic.children.length}
  if (sbSymbol>0) {
    sss = signBox.pic.children[sbSymbol-1].sss
    sssS = sss.substr(0,16)
    flip = sss.substr(16,2)
    flip = parseFloat(flip) 

    //check for valid rotations for flip 
    fcat = signBox.pic.children[sbSymbol-1].cat
    fsym = signBox.pic.children[sbSymbol-1].sym
    fTot = keyRot[fcat-1][fsym-1]
    fPow=0
    if (fTot>255) {rAdd=8} else {
      if ((flip==1) || (flip==5)) {rAdd=0} 
      if ((flip==2) || (flip==6)) {rAdd=6} 
      if ((flip==3) || (flip==7)) {rAdd=4} 
      if ((flip==4) || (flip==8)) {rAdd=2} 
    }
    while ((fTot & fPow) == 0) {
      flip += rAdd 
      if ((flip>8) && (rAdd<8)) { flip = flip -8}
      if (flip>16) { flip = flip -16}
      fPow=Math.pow(2,flip-1)
    }
    
    if (flip<10) { flip = "0" + flip}
    signBox.pic.children[sbSymbol-1].update(sssS + flip)
    buildControl()
  }
}

function symbolFill() {
  if(signBox.pic.children.length>0 && sbSymbol==0){sbSymbol=signBox.pic.children.length}
  if (sbSymbol>0) {
    sss = signBox.pic.children[sbSymbol-1].sss
    sssS = sss.substr(0,13)
    sssE = sss.substr(15,3)
    fill = sss.substr(13,2)
    fill = parseFloat(fill) 
    
    //loop through fills and check for valid value
    fcat = signBox.pic.children[sbSymbol-1].cat
    fsym = signBox.pic.children[sbSymbol-1].sym
    fTot = keyFil[fcat-1][fsym-1] 
    fPow = 0
    while ((fTot & fPow) == 0) {
      fill++     
      if (fill==7) { fill = 1}
      fPow=Math.pow(2,fill-1)
    }
    fill = "0" + fill 
    signBox.pic.children[sbSymbol-1].update(sssS + fill + sssE)
    buildControl()
  }
}

function symbolVar() {
  if(signBox.pic.children.length>0 && sbSymbol==0){sbSymbol=signBox.pic.children.length}
  if (sbSymbol>0) {
    sss = signBox.pic.children[sbSymbol-1].sss
    sssS = sss.substr(0,10)
    sssE = sss.substr(12,6)
    variation = sss.substr(11,2)
    variation = parseFloat(variation) + 1
    if (variation>signBox.pic.children[sbSymbol-1].vars) { variation = 1}
    if (variation<10) { variation= "0" + variation}
    signBox.pic.children[sbSymbol-1].update(sssS + variation + sssE)
    buildControl()
  }
}

function pickColor(color) {
  if(signBox.pic.children.length>0 && sbSymbol==0){sbSymbol=signBox.pic.children.length}
  if (sbSymbol>0) {
    color = color.substr(1,6)
    signBox.pic.children[sbSymbol-1].updateColor(color)
    buildControl()
  }
}

function symbolRotate(i) {
  if(signBox.pic.children.length>0 && sbSymbol==0){sbSymbol=signBox.pic.children.length}
  if (sbSymbol>0) {
    sss = signBox.pic.children[sbSymbol-1].sss
    sssS = sss.substr(0,16)
    rot = parseFloat(sss.substr(16,2))

    //loop through fills and check for valid value
    rcat = signBox.pic.children[sbSymbol-1].cat
    rsym = signBox.pic.children[sbSymbol-1].sym
    rTot = keyRot[rcat-1][rsym-1]
    rPow = 0
    while ((rTot & rPow) == 0) {

      if ((i>0)&&(rot<9)) {
       rot++ 
       if (rot==9) { rot = rot-8}
      } else 
      if ((i>0)&&(rot>8)) {
       rot-- 
       if (rot==8) { rot = rot+8}
      } else 
      if ((i<0)&&(rot<9)) {
       rot-- 
       if (rot==0) { rot = rot+8}
      } else 
      if ((i<0)&&(rot>8)) {
       rot++ 
       if (rot==17) { rot = rot-8}
      }
      rPow=Math.pow(2,rot-1)
    }    
    if (rot<10) { rot= "0" + rot}
    signBox.pic.children[sbSymbol-1].update(sssS + rot)
    buildControl()
  }
}

function box(){
  this.dynlayer=DynLayer
  this.dynlayer(null,offsetX,offsetY,signboxW,signboxH)
  this.pic =new DynLayer(null,1,1,this.w-2,this.h-2)
  this.addChild(this.pic)
  this.BorderL=new DynLayer(null,0,0,1,this.h,'#f0f0f0')
  this.BorderT=new DynLayer(null,0,0,this.w,1,'#f0f0f0')
  this.BorderR=new DynLayer(null,this.w-1,1,1,this.h-1,'#808080')
  this.BorderB=new DynLayer(null,1,this.h-1,this.w-1,1,'#808080')
  this.addChild(this.BorderL)
  this.addChild(this.BorderB)
  this.addChild(this.BorderT)
  this.addChild(this.BorderR)
  //this.selected=selectSymbol
  return this
}
box.prototype=new DynLayer()

function updateKeys(selected){
  //display top level
  if ((cat==0) && (selected==0)){ //top level display
    for (var i=0; i<6;i++){
      for (var j=0; j<16;j++){
        id = 1 + j + (i*16)
        create.children[id].cat=0
        create.children[id].sym=0
        create.children[id].reset()
        create.children[id].sss=""
        idSSS = 1 + j + (i*10)
        if(keys.length>=idSSS){
          if ((j<10) && (i<5)) {
            create.children[id].cat=idSSS
            create.children[id].sym=1
            create.children[id].sss=keys[idSSS-1][0] + "-01-01"
          }
        }
        create.children[id].refresh()
      }
    }
  }
  //display symbols and variations 
  if ((cat==0) && (selected>0)){
    cat=selected 
    display = keys[selected-1].length

    //shortcut if there is only one alternative 
    if (display==1) {
      updateKeys(1)
    } else {
 
    calCol = Math.floor(display/(6-.001)) + 1
    if (isSmall()) {
      inCol=6
    } else {
      inCol=10
    }
    if (calCol>inCol) {inCol=calCol}

    for (var i=0; i<6;i++){
      for (var j=0; j<16;j++){
        id = 1 + j + (i*16)
        idSSS = 1 + j + (i*inCol)
        create.children[id].cat=0
        create.children[id].sym=0
        create.children[id].reset()
        create.children[id].sss=""
        if ((j<inCol) && (idSSS<=display)) {
          create.children[id].cat=selected
          create.children[id].sym=idSSS
          if ((selected<11) && (idSSS>1)){
            create.children[id].sss=keys[selected-1][idSSS-1] + "-02-01"
          } else {
            create.children[id].sss=keys[selected-1][idSSS-1] + "-01-01"
          }
        }
        create.children[id].refresh()  
      }
    }
    }
  }
  //display fill and rotation 
  else if ((cat>0) && (selected>0)){
    sym=selected 
    RotKey = keyRot[cat-1][sym-1]
    FilKey = keyFil[cat-1][sym-1]
    for (var i=0; i<6;i++){
      for (var j=0; j<16;j++){
        fPow = Math.pow(2,i)
        rPow = Math.pow(2,j)
        id = 1 + j + (i*16)
        f="0" + (i+1)
        r="" + (j+1)
        if (j<9) {r="0"+r} 
        create.children[id].cat=cat
        create.children[id].sym=selected
        if ((RotKey & rPow) && (FilKey & fPow)){
          create.children[id].sss=keys[cat-1][selected-1] + "-" + f + "-" + r
        } else { 
          create.children[id].sss=""
        } 
        create.children[id].reset()
        create.children[id].refresh()  
      }
    }
  }
}

function symbol(sss,x,y,kcat,ksym) {
  cSymbol++
  this.cID=cSymbol
  this.cat=kcat
  this.sym=ksym
  this.sss=sss
  if (colorize) {
    this.color=keyColor[this.cat-1]
  } else {
    this.color="000000"
  } 
  this.dynlayer=DynLayer
  this.dynlayer(null,x,y,60,50,null)
  this.setHTML("<img alt='" + this.sss + "' src='" + swis_glyph + "?sss=" + this.sss + "' onload='resizeSymbolX(" + this.cID + ");'>")
  this.addEventListener(symbol.listener)
  this.update = updateSymbol
  this.updateColor = updateColorSymbol
  this.resize=resizeSymbol
  this.resize()
  // now figure out how many variations there are!
  sssS = this.sss.substr(0,11)
  var count=0
  for (var i=0; i<keys[this.cat-1].length;i++){
    if (sssS == keys[this.cat-1][i].substr(0,11)) {count++}
  }
  this.vars=count
  return this
}
symbol.prototype=new DynLayer()
symbol.listener = new EventListener()
symbol.listener.onmousedown=function(e){
  var src=e.getSource()
  for (var i=0; i<signBox.pic.children.length;i++){
    if (src==signBox.pic.children[i]) {
      if (sbSymbol==i+1) {sbSymbol=0} else {sbSymbol=i+1}
    }
  }
}
symbol.listener.ondragend=function(e){
  buildControl()
}

function updateSymbol(sss){
  this.sss=sss
  this.setHTML("<img alt='" + this.sss + "' src='" + swis_glyph + "?sss=" + this.sss + "' onload='resizeSymbolX(" + this.cID + ");'>")
} 

function updateColorSymbol(color){
  this.color=color
  this.setHTML("<img alt='" + this.sss + "' src='" + swis_glyph + "?sss=" + this.sss + "' onload='resizeSymbolX(" + this.cID + ");'>")
} 

function resizeSymbol(){
  var iS = new Image()
  var self = this;
  iS.onload = function(){
    iW = iS.width;
    iH = iS.height;
    self.setWidth(iW)
    self.setHeight(iH)
  }
  iS.src = swis_glyph + "?sss=" + this.sss
}

function resizeSymbolX(cID){
  for (var i=0; i<signBox.pic.children.length;i++){
    if (cID==signBox.pic.children[i].cID) {
      signBox.pic.children[i].resize()
    }
  }
}

function key(i,j) {
  iY = 120
  if (isSmall()) { iY = iY + specialH + 20;}
  this.xp=offsetX + signboxW + 35 + i*keyW + keyPad
  this.yp=iY+j*keyH+keyPad
  this.i=i
  this.j=j
  this.cat="" 
  this.sym="" 
  this.sss="" 
  this.dynlayer=DynLayer
  this.dynlayer(null,this.xp,this.yp,keyW,keyH,null)
  this.reset=resetKey
  this.topLeft=topLeftKey
  this.refresh=refreshKey
  this.addEventListener(key.listener)
  return this
}
key.prototype=new DynLayer()
key.listener = new EventListener()
key.listener.onmousedown=function(e){
  var o=e.getSource()
  o.refresh()
}
key.listener.ondragstart=function(e){
  var o=e.getSource()
  o.topLeft()
}
key.listener.onmouseup=function(e){
  var src=e.getSource()
  var X = src.getX()
  var Y = src.getY()
  if ((X==src.xp) && (Y==src.yp)) {//key was clicked so select
    if (cat==0) {
      updateKeys(src.cat) 
    }
    else if ((cat>0)&&(sym==0)) {
      updateKeys(src.sym) 
    }
  } else { //check for drop on sign box
    var sX = signBox.getX()
    var sY = signBox.getY()
    var W = signBox.getWidth()
    var H = signBox.getHeight()
    X = X - sX
    Y = Y - sY
    if ((X>-30) && (Y>-30) && (X<W) && (Y<H)) {
      //signBox.pic.setHTML(src.sss + " @ " + X + "," + Y)
      addSymbol(src.sss,X,Y,src.cat,src.sym)
    }
    src.reset()
  }
}

function refreshKey() {
  if (colorize) {
    color=keyColor[this.cat-1]
  } else {
    color="000000"
  } 
  var sTab = "<table width=100% height=100% border=1><tr><td align=middle valign=middle><center>"
  var eTab = "</center></td></tr></table>" 
  if (this.sss) {
    this.setHTML(sTab + "<img atl='" + this.sss + "' src='" + swis_glyph + "?sss=" + this.sss + "'>" + eTab)
  } else {
    this.setHTML(sTab + eTab)
  }
}

function topLeftKey() {
  if (colorize) {
    color=keyColor[this.cat-1]
  } else {
    color="000000"
  } 
  this.setHTML("<img atl='" + this.sss + "' src='" + swis_glyph + "?sss=" + this.sss + "'>")
}

function resetKey() {
  this.setX(this.xp)
  this.setY(this.yp)
  this.refresh()
}

function standardColors(){
  if (colorize) {
    colorize=0
  } else {
    colorize=1
  }
//recolor keys
  for (var i=0; i<6;i++){
    for (var j=0; j<16;j++){
      id = 1 + j + (i*16)
      create.children[id].reset()
      create.children[id].refresh()  
    }
  }

//recolor symbols on SignBox
  for (var i=signBox.pic.children.length-1; i>=0;i--){
    if (colorize) {
      color = keyColor[signBox.pic.children[i].cat-1] 
    } else {
      color = "000000"
    }
    signBox.pic.children[i].updateColor(color) 
  }
  buildControl();
}
/*@-node:slevin.20070121151424.1:@thin W:/www/signtext.js*/
/*@-leo*/
