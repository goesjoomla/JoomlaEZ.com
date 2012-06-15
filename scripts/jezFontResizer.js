var rememberUserOpts=true;var userOptsLoaded=false;var cookieLifetime=0;var fontSizeUnit='%';var fontSizeDefault=75;var fontSizeMin=50;var fontSizeMax=100;var fontSizeCurrent=fontSizeDefault;function jezChangeFontSize(sizeDiff){fontSizeCurrent=parseInt(fontSizeCurrent)+parseInt(sizeDiff);if(fontSizeCurrent>fontSizeMax)
fontSizeCurrent=fontSizeMax;else if(fontSizeCurrent<fontSizeMin)
fontSizeCurrent=fontSizeMin;jezSetFontSize();}
function jezRevertFontSize(){fontSizeCurrent=fontSizeDefault;jezSetFontSize();}
function jezSetFontSize(){document.body.style.fontSize=fontSizeCurrent+fontSizeUnit;}
function jezSetUserOpts(){if(!userOptsLoaded){var userFontSize=jezReadCookie("jezUserFontSize");if(userFontSize!=null)
fontSizeCurrent=parseInt(userFontSize);if(fontSizeCurrent!=fontSizeDefault)
jezChangeFontSize(0);userOptsLoaded=true;}}
function jezSaveUserOpts(){jezCreateCookie("jezUserFontSize",fontSizeCurrent,cookieLifetime);}
function jezRemoveUserOpts(){if(jezReadCookie("jezUserFontSize")!=null)
jezCreateCookie("jezUserFontSize",null,0-cookieLifetime);}
if(rememberUserOpts){jezAddEvent(window,'load',function(){jezSetUserOpts();});jezAddEvent(window,'unload',function(){jezSaveUserOpts();});}else{jezAddEvent(window,'load',function(){jezRemoveUserOpts();});jezAddEvent(window,'unload',function(){jezRemoveUserOpts();});}