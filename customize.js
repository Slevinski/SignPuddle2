/*
//@+leo-ver=4-thin
//@+node:ses.20071029095935:@thin W:/www/customize.js
//@@first
//@delims /* */ 
/*@@language java*/
//common variables
vSize = 1
vColorR = 0
vColorG = 0
vColorB = 0
vColor = "000000"
vColorize = 0
vBackgroundR = 255
vBackgroundG = 255
vBackgroundB = 255
vBackground = "ffffff"
vTransparent = 0
vPixels = 0
vBounding = "t"
vColorFor = ""

//start code
DynAPI.onLoad=function(){
  main = new DynLayer(null,200,130,720,1400)
  DynAPI.document.addChild(main)
  setMain(0) 
}

function setMain(update){
  //check for variable updates
  if (update==1){
    vSize=document.options.size.value
//    vBounding=document.options.bounding.value
    vPixels=document.options.pixels.value
  }

  //set image 
  text = "<img border=0 src='glyphogram.php?text=" + kLoad + "&size=" + vSize + "&line=" + vColor + "&colorize=" + vColorize + "&back=" + vBackground + "&transparent=" + vTransparent + "&pad=" + vPixels + "'>"; // "&bounding=" + vBounding + "'>"

//set selection
  //header
  selection = "<hr><h1>Options</h1><form name='options'><table cellpadding = 10>"
  //size
  selection += "<tr><th>Size</th><td>"
  selection += "<select name='size' onChange=\"setMain(1);\">"

  for (i=1;i<101;i++){
    j=i/10
    selection += "<option value='" + j + "' "
    if (vSize==j)  {selection+="selected"}
    selection += ">" + j
  }
  selection += "</select>"
  selection += "</td></tr>"

  //Colorized
  selection += "<tr><th>Sign Color</th><td>"
  selection += "<input type=button onclick=\"vColorFor='sign';cp.show('pick');return false;\" name=\"pick\" id=\"pick\" value=\"Select\">"
  selection += " or Standard Colors<input type=checkbox name='colorize' "
  if (vColorize) { selection += "checked"} 
  selection += " onClick=\"clickColorize();\">"
  selection += "</td></tr>"
  
  //Transparent
  selection += "<tr><th>Background Color</th><td>"
  selection += "<input type=button onclick=\"vColorFor='background';cp.show('pick');return false;\" name=\"pick\" id=\"pick\" value=\"Select\">"
  selection += " or Transparent <input type=checkbox name='transparent' "
  if (vTransparent) { selection += "checked"} 
  selection += " onClick=\"clickTransparent();\">"
  selection += "</td></tr>"
  
  //centering
/*
  selection += "<tr><th>Bounding Box</th><td>"
  selection += "<select name='bounding' onChange=\"setMain(1);\">"
  selection += "<option value='t' "
  if (vBounding=="t")  {selection+="selected"}
  selection += ">Tight"
  selection += "<option value='c' "
  if (vBounding=="c")  {selection+="selected"}
  selection += ">Head Center"
  selection += "<option value='v' "
  if (vBounding=="v")  {selection+="selected"}
  selection += ">Head Verticle Center"
  selection += "<option value='h' "
  if (vBounding=="h")  {selection+="selected"}
  selection += ">Head Horizontal Center"
  selection += "</select>"
  selection += "</td></tr>"
*/

  //pixel border
  selection += "<tr><th>Pixel Border</th><td>"
  selection += "<select name='pixels' onChange=\"setMain(1);\">"
  for (i=0;i<41;i++){
    selection += "<option value=" + i
    if (vPixels==i)  {selection+=" selected"}
    selection += ">" + i
  }
  selection += "</select>"
  selection += "</td></tr>"

  //footer
  selection += "</table></form>"
 
  main.setHTML(text + selection) 
} 

function clickColorize() {
  if (vColorize) {
    vColorize=0 
  } else {
    vColorize=1 
  }
  setMain(1)
}

function clickTransparent() {
  if (vTransparent) {
    vTransparent=0 
  } else {
    vTransparent=1 
  }
  setMain(1)
}

function pickColor(color){
  color = color.substr(1,6)
  if (vColorFor=="sign") {
    vColor = color 
    vColorize=0
  } else {
    vBackground = color
    vTransparent=0
  }
  setMain(1)
}
/*@-node:ses.20071029095935:@thin W:/www/customize.js*/
/*@-leo*/
