var selective=false;function jezFixPNGs(){var images=document.images;if(window.attachEvent&&jezIEVer<=6){for(var i=0;i<images.length;i++)
jezFixPNG(images[i]);setTimeout("jezShowPNGs()",200);}else{for(var i=0;i<images.length;i++)
images[i].style.visibility="visible";}}
function jezFixPNG(element){var imgName=element.src.toUpperCase();if((!selective&&imgName.substring(imgName.length-3,imgName.length)=="PNG")||(selective&&(new RegExp('\s*'+pngClass+'\s*')).test(element.className))){if(element.style.visibility=="hidden")
element.style.filter="progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+element.src+"',sizingMethod='scale') "+"revealTrans(transition=12,duration=0.5)";else
element.style.filter="progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+element.src+"',sizingMethod='scale')";if(!element.style.width||element.style.width=='auto')
element.style.width=element.offsetWidth+'px';if(!element.style.height||element.style.height=='auto')
element.style.height=element.offsetHeight+'px';element.src=SPACER;}}
function jezShowPNGs(){var images=document.images;for(var i=0;i<images.length;i++)
jezShowPNG(images[i]);}
function jezShowPNG(element){var imgName=element.src.toUpperCase();if((!selective&&imgName.substring(imgName.length-3,imgName.length)=="PNG")||(selective&&(new RegExp('\s*'+pngClass+'\s*')).test(element.className))){if(element.style.visibility=="hidden"){element.filters[1].Apply();element.style.visibility="visible";element.filters[1].Play();}}}