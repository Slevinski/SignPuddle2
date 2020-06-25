/*
//@+leo-ver=4-thin
//@+node:slevin.20070119161527.1:@thin W:/www/searchsymbol.js
//@@first
//@delims /* */ 
/*@@language java*/
//common variables

cat = 0
sym = 0
sbSymbol = 0
cSymbol= 0
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
padX = 150
padY = 0
offsetX = 50
offsetY = 100 

//start main code
window.onresize = Resize
ResID=null
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
  create = new DynLayer(null,padX,padY,720,1400)
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
  buildControl() 
  if (bLoad!="") {
    loadSymbols(bLoad)
  }
  updateKeys(0)
  floatLayer()
  selectedBlink()
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

function buildControl() {
  build=""
  for (var i=0; i<signBox.pic.children.length;i++){
    sssBase = signBox.pic.children[i].sss.substr(0,12)
    if (signBox.pic.children[i].fillMatch) {
      sssFill = signBox.pic.children[i].sss.substr(13,2)
    } else {
      sssFill = 0
    }
    if (signBox.pic.children[i].rotMatch) {
      sssRot = signBox.pic.children[i].sss.substr(16,2)
    } else {
      sssRot = 0
    }
    build=build + sssBase + "," + sssFill + "," + sssRot + "," + signBox.pic.children[i].getX() + "," + signBox.pic.children[i].getY() + ","
  }
  iGL = "<a href=\"#\" onclick=\"goTop();return false;\" >" + iGLi + "</a>";
  iPR = "<a href=\"#\" onclick=\"goUp();return false;\">" + iPRi + "</a>";
  iAD = "<a href=\"searchquery.php?ui=" + uiVal + "&sgn=" + sgnVal + "&bldSearch=" + build + "\">" + iADi + "</a>";

  iSN = "<a href=\"#\" onclick=\"symbolNext();return false;\" >" + iSNi + "</a>";
  iDS = "<a href=\"#\" onclick=\"symbolDelete();return false;\" >" + iDSi + "</a>";
  iCA = "<a href=\"#\" onclick=\"clearAll();return false;\" >" + iCAi + "</a>";

  iVR = "<a href=\"#\" onclick=\"symbolVar();return false;\" >" + iVRi + "</a>";
  iMS = "<a href=\"#\" onclick=\"symbolFlip();return false;\" >" + iMSi + "</a>";
  iFS = "<a href=\"#\" onclick=\"symbolFill();return false;\" >" + iFSi + "</a>";

  iEM = "<a href=\"#\" onclick=\"exactMatch();return false;\" >" + iEMi + "</a>";
  iRCC = "<a href=\"#\" onclick=\"symbolRotate(1);return false;\" >" + iRCCi + "</a>";
  iRC = "<a href=\"#\" onclick=\"symbolRotate(-1);return false;\" >" + iRCi + "</a>";

  iAM = "<a href=\"#\" onclick=\"anyMatch();return false;\" >" + iAMi + "</a>";
  iFM = "<a href=\"#\" onclick=\"fillMatch();return false;\" >" + iFMi + "</a>";
  iRM = "<a href=\"#\" onclick=\"rotMatch();return false;\" >" + iRMi + "</a>";
  
  atab="<table cellpadding=2 border=0><tr>";
  atab=atab + "<tr><td>" +iGL+ "</td><td>" +iPR+ "</td><td>" +iAD+ "</td></tr>";
  atab=atab + "<tr><td>" +iSN+ "</td><td>" +iDS+ "</td><td>" +iCA+ "</td></tr>";
  atab=atab + "<tr><td>" +iVR+ "</td><td>" +iMS+ "</td><td>" +iFS+ "</td></tr>";
  atab=atab + "<tr><td>" +iEM+ "</td><td>" +iRCC+ "</td><td>" +iRC+ "</td></tr>";
  atab=atab + "<tr><td>" +iAM+ "</td><td>" +iFM+ "</td><td>" +iRM+ "</td></tr>";
  atab=atab + "</table>";
  control.setHTML(atab)
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

function exactMatch() {
  if(signBox.pic.children.length>0 && sbSymbol==0){sbSymbol=signBox.pic.children.length}
  if (sbSymbol>0) {
    signBox.pic.children[sbSymbol-1].fillMatch=1
    signBox.pic.children[sbSymbol-1].rotMatch=1
    pickColor("#000000") 
  }
}

function anyMatch() {
  if(signBox.pic.children.length>0 && sbSymbol==0){sbSymbol=signBox.pic.children.length}
  if (sbSymbol>0) {
    signBox.pic.children[sbSymbol-1].fillMatch=0
    signBox.pic.children[sbSymbol-1].rotMatch=0
    pickColor("#999999") 
  }
}

function fillMatch() {
  if(signBox.pic.children.length>0 && sbSymbol==0){sbSymbol=signBox.pic.children.length}
  if (sbSymbol>0) {
    signBox.pic.children[sbSymbol-1].fillMatch=1
    signBox.pic.children[sbSymbol-1].rotMatch=0
    pickColor("#FF0000") 
  }
}

function rotMatch() {
  if(signBox.pic.children.length>0 && sbSymbol==0){sbSymbol=signBox.pic.children.length}
  if (sbSymbol>0) {
    signBox.pic.children[sbSymbol-1].fillMatch=0
    signBox.pic.children[sbSymbol-1].rotMatch=1
    pickColor("#00CC00") 
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
  this.crosshair = new DynLayer(null,101,101,48,48)
  this.crosshair.setHTML('<img src="glyphogram.php?ksw=M24x24S37a000xn24S37a06n24x0&line=aaaaaa">')
  this.addChild(this.crosshair)
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
  this.fillMatch=1
  this.rotMatch=1
  this.color="000000"
  this.dynlayer=DynLayer
  this.dynlayer(null,x,y,60,50,null)
  this.setHTML("<img alt='" + this.sss + "' src='" + swis_glyph + "?sss=" + this.sss + "&color=" + this.color + "' onload='resizeSymbolX(" + this.cID + ");'>")
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
  this.setHTML("<img alt='" + this.sss + "' src='" + swis_glyph + "?sss=" + this.sss + "&color=" + this.color + "' onload='resizeSymbolX(" + this.cID + ");'>")
} 

function updateColorSymbol(color){
  this.color=color
  this.setHTML("<img alt='" + this.sss + "' src='" + swis_glyph + "?sss=" + this.sss + "&color=" + this.color + "' onload='resizeSymbolX(" + this.cID + ");'>")
} 

function resizeSymbol(){
// determine size
  iS = new Image()
  iS.src = swis_glyph + "?sss=" + this.sss + "&color=" + this.color 
  iW = iS.width
  iH = iS.height
  this.setWidth(iW)
  this.setHeight(iH)
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
  color="000000"
  var sTab = "<table width=100% height=100% border=1><tr><td align=middle valign=middle><center>"
  var eTab = "</center></td></tr></table>" 
  if (this.sss) {
    this.setHTML(sTab + "<img atl='" + this.sss + "' src='" + swis_glyph + "?sss=" + this.sss + "&color=" + color + "'>" + eTab)
  } else {
    this.setHTML(sTab + eTab)
  }
}

function topLeftKey() {
  color="000000"
  this.setHTML("<img atl='" + this.sss + "' src='" + swis_glyph + "?sss=" + this.sss + "&color=" + color + "'>")
}

function resetKey() {
  this.setX(this.xp)
  this.setY(this.yp)
  this.refresh()
}


/*@-node:slevin.20070119161527.1:@thin W:/www/searchsymbol.js*/
/*@-leo*/
