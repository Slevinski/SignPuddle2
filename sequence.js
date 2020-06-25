/*
//@+leo-ver=4-thin
//@+node:ses.20070127171438:@thin W:/www/sequence.js
//@@first
//@delims /* */ 
/*@@language java*/

//common variables
var sequenceNum=0
symbolNum=0

//start code
DynAPI.onLoad=function(){
  var symbols = bBuild.split(",");
  symbolNum=Math.floor(symbols.length/3)
  var xMax = 100 * (symbolNum)

  sign = new DynLayer(null,200,120,250,250)
  sign.setHTML("<img border=1 src='img.php?build=" + bBuild + "'>")
  DynAPI.document.addChild(sign)

  sequenceSrc = new DynLayer(null,220,370,150,100)
  sequenceSrc.setHTML("<b>Spelling Symbols</b>")
  DynAPI.document.addChild(sequenceSrc)

  sequenceSrc = new DynLayer(null,420,370,150,100)
  sequenceSrc.setHTML("<b>Sequence</b>")
  DynAPI.document.addChild(sequenceSrc)

  sequence = new DynLayer(null,200,350,700,xMax+350)
  sequence.setHTML("<hr>")
  DynAPI.document.addChild(sequence)

  var i=0;
  var part_num=0;
  while ( part_num < symbols.length-1)
   {
    var sourceX = new sourceSymbol(i,symbols[part_num])
    sequence.addChild(sourceX)
    sourceX.refresh()
    part_num+=3;
    i+=1;
  }
  
  addSign = new DynLayer(null,600,420,250,250)
  DynAPI.document.addChild(addSign)

  //now check out sequence and load what you can!
  var symbols = bSequence.split(",");
  for (var i=0;i<symbols.length;i++){
    for (var j=0;j<symbolNum;j++){
      if (symbols[i]==sequence.children[j].symbol) {
        sequence.children[j].select()
        j = symbolNum
      }
    }
  }
  build_addSign()

}

function build_addSign(){
  spl = "";
  for (var i=symbolNum; i<=sequence.children.length-1;i++){
    spl = spl + sequence.children[i].symbol + ","
  }
  link = ""
  if (spl){
    if (bSign) {spl = spl + "&sid=" + bSign}
    link = "<a href=\"canvas.php?action=sequence&ui=" + uiVal + "&sgn=" + sgnVal + "&sequence=" + spl + "\"><img border=0 alt=\"Add to Dictionary\" src=\"library/icons/AddDictionary.png\"></a>";
  }
  addSign.setHTML(link)
}

function sourceSymbol(i,symbol) {
  this.i=i
  this.symbol=symbol
  this.enabled=1
  this.dynlayer=DynLayer
  this.dynlayer(null,20,50 + i*100,100,100,null)
  this.reset=resetSource
  this.select=selectSource
  this.refresh=refreshSource
  this.addEventListener(sourceSymbol.listener)
  return this
}
sourceSymbol.prototype=new DynLayer()
sourceSymbol.listener = new EventListener()
sourceSymbol.listener.onmouseup=function(e){
  var src=e.getSource()
  if (src.enabled==1) {
    src.select()
    build_addSign()
  }
}

function selectSource(){
  this.enabled=0
  var sequenceX= new sequenceSymbol(this.i,this.symbol)
  sequence.addChild(sequenceX)
  sequenceX.refresh()
  this.refresh()
}  

function resetSource(){
  this.enabled=1
  this.refresh()
}  

function refreshSource(){
  var sTab = "<table width=100% height=100% border=1><tr><td align=middle valign=middle><center>"
  var eTab = "</center></td></tr></table>"
  if (this.enabled) {
    color="000000"
  } else {
    color="999999"
  }
  this.setHTML(sTab + "<img atl='" + this.symbol+ "' src='" + swis_glyph + "?sss=" + this.symbol+ "&color=" + color + "'>" + eTab)

}


function sequenceSymbol(source,symbol) {
  this.source=source
  this.i=sequenceNum
  sequenceNum++
  this.symbol=symbol
  this.dynlayer=DynLayer
  this.dynlayer(null,220,50 + this.i*100,100,100,null)
  this.select=selectSpelling
  this.refresh=refreshSpelling
  this.addEventListener(sequenceSymbol.listener)
  return this
}
sequenceSymbol.prototype=new DynLayer()
sequenceSymbol.listener = new EventListener()
sequenceSymbol.listener.onmouseup=function(e){
  var src=e.getSource()
  src.select()
  build_addSign()
}

function selectSpelling(){
  iMax = symbolNum + this.i 
  for (var i=sequence.children.length-1; i>=iMax;i--){
    sequence.children[sequence.children[i].source].reset()
    sequence.children[i].deleteFromParent()
  }
  sequenceNum=this.i
}  

function refreshSpelling(){
  var sTab = "<table width=100% height=100% border=1><tr><td align=middle valign=middle><center>"
  var eTab = "</center></td></tr></table>"
  color="000000"
  this.setHTML(sTab + "<img atl='" + this.symbol+ "' src='" + swis_glyph + "?sss=" + this.symbol+ "&color=" + color + "'>" + eTab)

}


/*@-node:ses.20070127171438:@thin W:/www/sequence.js*/
/*@-leo*/
