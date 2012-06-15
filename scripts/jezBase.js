function jezAddEvent(element,type,handler){if(element.addEventListener){element.addEventListener(type,handler,false);}else{if(!handler.$$guid)handler.$$guid=jezAddEvent.guid++;if(!element.events)element.events={};var handlers=element.events[type];if(!handlers){handlers=element.events[type]={};if(element["on"+type]){handlers[0]=element["on"+type];}}
handlers[handler.$$guid]=handler;element["on"+type]=jezHandleEvent;}};jezAddEvent.guid=1;function jezRemoveEvent(element,type,handler){if(element.removeEventListener){element.removeEventListener(type,handler,false);}else{if(element.events&&element.events[type]){delete element.events[type][handler.$$guid];}}};function jezHandleEvent(event){var returnValue=true;event=event||jezFixEvent(((this.ownerDocument||this.document||this).parentWindow||window).event);var handlers=this.events[event.type];for(var i in handlers){this.$$handleEvent=handlers[i];if(this.$$handleEvent(event)===false){returnValue=false;}}
return returnValue;};function jezFixEvent(event){event.preventDefault=jezFixEvent.preventDefault;event.stopPropagation=jezFixEvent.stopPropagation;return event;};jezFixEvent.preventDefault=function(){this.returnValue=false;};jezFixEvent.stopPropagation=function(){this.cancelBubble=true;};var jezIEVer=navigator.appVersion.split("MSIE");jezIEVer=parseFloat(jezIEVer[1]);jezIEVer=!parseInt(jezIEVer)?7:jezIEVer;function jezCreateCookie(name,value,days){if(days){var date=new Date();date.setTime(date.getTime()+(days*24*60*60*1000));var expires="; expires="+date.toGMTString();}else expires="";document.cookie=name+"="+value+expires+"; path=/";}
function jezReadCookie(name){name=name+"=";var ca=document.cookie.split(';');for(var i=0;i<ca.length;i++){var c=ca[i];while(c.charAt(0)==' ')c=c.substring(1,c.length);if(c.indexOf(name)==0)
return c.substring(name.length,c.length);}
return null;}
function jezSwitchState(element,childTags,hoverClass,focusClass){element=typeof element=='object'?element:(document.getElementById?document.getElementById(element):document.all[element]);var elements=[],tmp;if(childTags){childTags=typeof childTags=='string'?[childTags]:childTags;for(var i=0;i<childTags.length;i++){tmp=element.getElementsByTagName(childTags[i]);if(tmp.length>0){for(var j=0;j<tmp.length;j++){if(childTags[i].toLowerCase()!='input'||tmp[j].type!='hidden')
elements.push(tmp[j]);}}}}
if(elements.length>0){for(var i=0;i<elements.length;i++){if(hoverClass){jezAddEvent(elements[i],'mouseover',function(){this.className+=" hover";});jezAddEvent(elements[i],'mouseout',function(){this.className=this.className.replace(/ hover\b/,'');});}
if(focusClass){jezAddEvent(elements[i],'focus',function(){this.className+=" focus";});jezAddEvent(elements[i],'blur',function(){this.className=this.className.replace(/ focus\b/,'');});}}}}
function jezGetVertRhythm(element){var original=element=typeof element=='object'?element:(document.getElementById?document.getElementById(element):document.all[element]);var fontSize,curFontSize,lineHeight;while(element.tagName.toLowerCase()!='html'){if(document.defaultView)
curFontSize=document.defaultView.getComputedStyle(element,null).getPropertyValue("font-size");else if(element.currentStyle)
curFontSize=element.currentStyle['fontSize'];if(!fontSize){if(/\%$/.test(curFontSize))
fontSize=(curFontSize.replace(/[^\d^\.^,]+$/,'')/100)+'em';else
fontSize=curFontSize;}else if(element.parentNode.tagName.toLowerCase()!='html'){if(/\%$/.test(curFontSize))
fontSize=(fontSize.replace(/[^\d^\.^,]+$/,'')*curFontSize.replace(/[^\d^\.^,]+$/,'')/100)+'em';else if(/em$/.test(curFontSize))
fontSize=(fontSize.replace(/[^\d^\.^,]+$/,'')*curFontSize.replace(/[^\d^\.^,]+$/,''))+'em';else
fontSize=(curFontSize.replace(/[^\d^\.^,]+$/,'')*fontSize.replace(/[^\d^\.^,]+$/,''))+'px';}
if(/px$/.test(fontSize))
break;else
element=element.parentNode;}
if(!(/px$/.test(fontSize))){if(/\%$/.test(curFontSize))
fontSize=fontSize.replace(/[^\d^\.^,]+$/,'')*(16*curFontSize.replace(/[^\d^\.^,]+$/,'')/100);else if(/em$/.test(curFontSize))
fontSize=fontSize.replace(/[^\d^\.^,]+$/,'')*(16*curFontSize.replace(/[^\d^\.^,]+$/,''));else
fontSize=12;}else
fontSize=fontSize.replace(/[^\d^\.^,]+$/,'');if(document.defaultView)
lineHeight=document.defaultView.getComputedStyle(original,null).getPropertyValue("line-height");else if(original.currentStyle)
lineHeight=original.currentStyle['lineHeight'];if(/(\%|em)$/.test(lineHeight))
lineHeight=/\%$/.test(lineHeight)?(fontSize*lineHeight.replace(/[^\d^\.^,]+$/,'')/100):(fontSize*lineHeight.replace(/[^\d^\.^,]+$/,''));else if(!(/px$/.test(lineHeight)))
lineHeight=fontSize*1.5;else
lineHeight=lineHeight.replace(/[^\d^\.^,]+$/,'');return{'fontSize':fontSize,'lineHeight':lineHeight};}
function jezKeepVertRhythm(elements,matching){elements=typeof elements=='object'?(typeof elements.length!='undefined'?elements:[elements]):document.getElementsByTagName(elements);var eT,eI,eC,pT,pI,pC,ppC,vR,elHeight,elWidth;for(var i=0;i<elements.length;i++){if(!matching||(new RegExp('\s*'+matching+'\s*')).test(elements[i].className)){eT=elements[i].tagName.toLowerCase();eI=elements[i].id;eC=elements[i].className;pT=elements[i].parentNode.tagName.toLowerCase();pI=elements[i].parentNode.id;pC=elements[i].parentNode.className;ppC=elements[i].parentNode.parentNode.className;if(eT!='img'||(eT=='img'&&((!eI||!(/^recaptcha_/.test(eI)))&&(pI!='recaptcha_image')&&(!eC||!(/\s*(logo|calendar)\s*/.test(eC)))&&!(/\s*button\s*/.test(pT))&&(pT!="a"||(pT=="a"&&pC!="button"&&!(/\s*(buttonheading|banneritem)\s*/.test(ppC))))))){vR=jezGetVertRhythm(elements[i]);elHeight=elements[i].style.height?parseInt(elements[i].style.height):(elements[i].height?elements[i].height:elements[i].offsetHeight);if(elHeight>vR.lineHeight&&(parseInt(elHeight/vR.lineHeight)*vR.lineHeight)!=elHeight){elements[i].style.marginBottom=((((parseInt(elHeight/vR.lineHeight)+1)*vR.lineHeight)-elHeight)/vR.fontSize)+'em';}}}}}