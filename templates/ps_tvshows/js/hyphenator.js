var Hyphenator;Hyphenator=(function(window){'use strict';var contextWindow=window;var supportedLangs=(function(){var r={},o=function(code,file,script,prompt){r[code]={'file':file,'script':script,'prompt':prompt}};o('be','be.js',1,'Мова гэтага сайта не можа быць вызначаны аўтаматычна. Калі ласка пакажыце мову:');o('ca','ca.js',0,'');o('cs','cs.js',0,'Jazyk teto internetove stranky nebyl automaticky rozpoznan. Urcete prosim jeji jazyk:');o('da','da.js',0,'Denne websides sprog kunne ikke bestemmes. Angiv venligst sprog:');o('bn','bn.js',4,'');o('de','de.js',0,'Die Sprache dieser Webseite konnte nicht automatisch bestimmt werden. Bitte Sprache angeben:');o('el','el-monoton.js',6,'');o('el-monoton','el-monoton.js',6,'');o('el-polyton','el-polyton.js',6,'');o('en','en-us.js',0,'The language of this website could not be determined automatically. Please indicate the main language:');o('en-gb','en-gb.js',0,'The language of this website could not be determined automatically. Please indicate the main language:');o('en-us','en-us.js',0,'The language of this website could not be determined automatically. Please indicate the main language:');o('eo','eo.js',0,'La lingvo de ci tiu retpago ne rekoneblas automate. Bonvolu indiki gian ceflingvon:');o('es','es.js',0,'El idioma del sitio no pudo determinarse autom%E1ticamente. Por favor, indique el idioma principal:');o('et','et.js',0,'Veebilehe keele tuvastamine ebaonnestus, palun valige kasutatud keel:');o('fi','fi.js',0,'Sivun kielt%E4 ei tunnistettu automaattisesti. M%E4%E4rit%E4 sivun p%E4%E4kieli:');o('fr','fr.js',0,'La langue de ce site n%u2019a pas pu %EAtre d%E9termin%E9e automatiquement. Veuillez indiquer une langue, s.v.p.%A0:');o('ga','ga.js',0,'Niorbh fheidir teanga an tsuimh a fhail go huathoibrioch. Cuir isteach priomhtheanga an tsuimh:');o('grc','grc.js',6,'');o('gu','gu.js',7,'');o('hi','hi.js',5,'');o('hu','hu.js',0,'A weboldal nyelvet nem sikerult automatikusan megallapitani. Kerem adja meg a nyelvet:');o('hy','hy.js',3,'????????? ??????????? ??? ????? ??????? ??????? ??? ???? ???????? ???????');o('it','it.js',0,'Lingua del sito sconosciuta. Indicare una lingua, per favore:');o('kn','kn.js',8,'??? ???? ????????? ?????????? ????????????????. ???????? ????? ????????? ??????:');o('la','la.js',0,'');o('lt','lt.js',0,'Nepavyko automatiskai nustatyti sios svetaines kalbos. Prasome ivesti kalba:');o('lv','lv.js',0,'Sis lapas valodu nevareja noteikt automatiski. Ludzu noradiet pamata valodu:');o('ml','ml.js',10,'? ??%u0D2C%u0D4D%u200C?????????? ??? ???????????????%u0D28%u0D4D%u200D ??????????. ??? ????????? ??????????????:');o('nb','nb-no.js',0,'Nettstedets sprak kunne ikke finnes automatisk. Vennligst oppgi sprak:');o('no','nb-no.js',0,'Nettstedets sprak kunne ikke finnes automatisk. Vennligst oppgi sprak:');o('nb-no','nb-no.js',0,'Nettstedets sprak kunne ikke finnes automatisk. Vennligst oppgi sprak:');o('nl','nl.js',0,'De taal van deze website kan niet automatisch worden bepaald. Geef de hoofdtaal op:');o('or','or.js',11,'');o('pa','pa.js',13,'');o('pl','pl.js',0,'Jezyka tej strony nie mozna ustalic automatycznie. Prosze wskazac jezyk:');o('pt','pt.js',0,'A lingua deste site nao pode ser determinada automaticamente. Por favor indique a lingua principal:');o('ru','ru.js',1,'Язык этого сайта не может быть определен автоматически. Пожалуйста укажите язык:');o('sk','sk.js',0,'');o('sl','sl.js',0,'Jezika te spletne strani ni bilo mogoce samodejno dolociti. Prosim navedite jezik:');o('sr-cyrl','sr-cyrl.js',1,'Језик овог сајта није детектован аутоматски. Молим вас наведите језик:');o('sr-latn','sr-latn.js',0,'Jezika te spletne strani ni bilo mogoce samodejno dolociti. Prosim navedite jezik:');o('sv','sv.js',0,'Spr%E5ket p%E5 den h%E4r webbplatsen kunde inte avg%F6ras automatiskt. V%E4nligen ange:');o('ta','ta.js',14,'');o('te','te.js',15,'');o('tr','tr.js',0,'Bu web sitesinin dili otomatik olarak tespit edilememistir. Lutfen dokuman?n dilini seciniz%A0:');o('uk','uk.js',1,'Мова цього веб-сайту не може бути визначена автоматично. Будь ласка, вкажіть головну мову:');o('ro','ro.js',0,'Limba acestui sit nu a putut fi determinata automat. Alege limba principala:');return r}());var locality=(function getLocality(){var r={isBookmarklet:!1,basePath:"//mnater.github.io/Hyphenator/",isLocal:!1},scripts=contextWindow.document.getElementsByTagName('script'),i=0,src,len=scripts.length,p,currScript;while(i<len){currScript=scripts[i];if(currScript.hasAttribute("src")){src=currScript.src;p=src.indexOf("Hyphenator.js");if(p!==-1){r.basePath=src.substring(0,p);if(src.indexOf("Hyphenator.js?bm=true")!==-1){r.isBookmarklet=!0}
if(window.location.href.indexOf(r.basePath)!==-1){r.isLocal=!0}
break}}
i+=1}
return r}());var basePath=locality.basePath;var isLocal=locality.isLocal;var documentLoaded=!1;var persistentConfig=!1;var doFrames=!1;var dontHyphenate={'video':!0,'audio':!0,'script':!0,'code':!0,'pre':!0,'img':!0,'br':!0,'samp':!0,'kbd':!0,'var':!0,'abbr':!0,'acronym':!0,'sub':!0,'sup':!0,'button':!0,'option':!0,'label':!0,'textarea':!0,'input':!0,'math':!0,'svg':!0,'style':!0};var enableCache=!0;var storageType='local';var storage;var enableReducedPatternSet=!1;var enableRemoteLoading=!0;var displayToggleBox=!1;var onError=function(e){window.alert("Hyphenator.js says:\n\nAn Error occurred:\n"+e.message)};var onWarning=function(e){window.console.log(e.message)};function createElem(tagname,context){context=context||contextWindow;var el;if(window.document.createElementNS){el=context.document.createElementNS('http://www.w3.org/1999/xhtml',tagname)}else if(window.document.createElement){el=context.document.createElement(tagname)}
return el}
function forEachKey(o,f){var k;if(Object.hasOwnProperty("keys")){Object.keys(o).forEach(f)}else{for(k in o){if(o.hasOwnProperty(k)){f(k)}}}}
var css3=!1;function css3_gethsupport(){var support=!1,supportedBrowserLangs={},property='',checkLangSupport,createLangSupportChecker=function(prefix){var testStrings=['aabbccddeeffgghhiijjkkllmmnnooppqqrrssttuuvvwwxxyyzz','абвгдеёжзийклмнопрстуфхцчшщъыьэюя','????????????????????????????','??????????????????????????????????????','??????????????????????????????????????????????????????????????????????','????????????????????????????????????????????????????????????????????','?????????????????????????','???????????????????????????????????????????????????????????','???????????????????????????????????????????????????????????????????????','?????????????????????????????????????????????????','??????????????????????????????????????????????????????????????????????????','???????????????????????????????????????????????????????????????','????????????????????????????','??????????????????????????????????????????????????????????','????????????????????????????????????????????????','??????????????????????????????????????????????????????????????????????'],f=function(lang){var shadow,computedHeight,bdy,r=!1;if(supportedBrowserLangs.hasOwnProperty(lang)){r=supportedBrowserLangs[lang]}else if(supportedLangs.hasOwnProperty(lang)){bdy=window.document.getElementsByTagName('body')[0];shadow=createElem('div',window);shadow.id='Hyphenator_LanguageChecker';shadow.style.width='5em';shadow.style.padding='0';shadow.style.border='none';shadow.style[prefix]='auto';shadow.style.hyphens='auto';shadow.style.fontSize='12px';shadow.style.lineHeight='12px';shadow.style.wordWrap='normal';shadow.style.visibility='hidden';shadow.lang=lang;shadow.style['-webkit-locale']="'"+lang+"'";shadow.innerHTML=testStrings[supportedLangs[lang].script];bdy.appendChild(shadow);computedHeight=shadow.offsetHeight;bdy.removeChild(shadow);r=!!(computedHeight>12);supportedBrowserLangs[lang]=r}else{r=!1}
return r};return f},s;if(window.getComputedStyle){s=window.getComputedStyle(window.document.getElementsByTagName('body')[0],null);if(s.hyphens!==undefined){support=!0;property='hyphens';checkLangSupport=createLangSupportChecker('hyphens')}else if(s['-webkit-hyphens']!==undefined){support=!0;property='-webkit-hyphens';checkLangSupport=createLangSupportChecker('-webkit-hyphens')}else if(s.MozHyphens!==undefined){support=!0;property='-moz-hyphens';checkLangSupport=createLangSupportChecker('MozHyphens')}else if(s['-ms-hyphens']!==undefined){support=!0;property='-ms-hyphens';checkLangSupport=createLangSupportChecker('-ms-hyphens')}}
return{support:support,property:property,supportedBrowserLangs:supportedBrowserLangs,checkLangSupport:checkLangSupport}}
var css3_h9n;var hyphenateClass='hyphenate';var urlHyphenateClass='urlhyphenate';var classPrefix='Hyphenator'+Math.round(Math.random()*1000);var hideClass=classPrefix+'hide';var hideClassRegExp=new RegExp("\\s?\\b"+hideClass+"\\b","g");var unhideClass=classPrefix+'unhide';var unhideClassRegExp=new RegExp("\\s?\\b"+unhideClass+"\\b","g");var css3hyphenateClass=classPrefix+'css3hyphenate';var css3hyphenateClassHandle;var dontHyphenateClass='donthyphenate';var min=6;var leftmin=0;var rightmin=0;var compound="auto";var orphanControl=1;var isBookmarklet=locality.isBookmarklet;var mainLanguage=null;var defaultLanguage='';var elements=(function(){var makeElement=function(element){return{element:element,hyphenated:!1,treated:!1}},makeElementCollection=function(){var counters=[0,0],list={},add=function(el,lang){var elo=makeElement(el);if(!list.hasOwnProperty(lang)){list[lang]=[]}
list[lang].push(elo);counters[0]+=1;return elo},each=function(fn){forEachKey(list,function(k){if(fn.length===2){fn(k,list[k])}else{fn(list[k])}})};return{counters:counters,list:list,add:add,each:each}};return makeElementCollection()}());var exceptions={};var docLanguages={};var url='(?:\\w*:\/\/)?(?:(?:\\w*:)?(?:\\w*)@)?(?:(?:(?:[\\d]{1,3}\\.){3}(?:[\\d]{1,3}))|(?:(?:www\\.|[a-zA-Z]\\.)?[a-zA-Z0-9\\-\\.]+\\.(?:[a-z]{2,4})))(?::\\d*)?(?:\/[\\w#!:\\.?\\+=&%@!\\-]*)*';var mail='[\\w-\\.]+@[\\w\\.]+';var zeroWidthSpace=(function(){var zws,ua=window.navigator.userAgent.toLowerCase();zws=String.fromCharCode(8203);if(ua.indexOf('msie 6')!==-1){zws=''}
if(ua.indexOf('opera')!==-1&&ua.indexOf('version/10.00')!==-1){zws=''}
return zws}());var onBeforeWordHyphenation=function(word){return word};var onAfterWordHyphenation=function(word){return word};var onHyphenationDone=function(context){return context};var selectorFunction=!1;function flattenNodeList(nl){var parentElements=[],i=1,j=0,isParent=!0;parentElements.push(nl[0]);while(i<nl.length){while(j<parentElements.length){if(parentElements[j].contains(nl[i])){isParent=!1;break}
j+=1}
if(isParent){parentElements.push(nl[i])}
isParent=!0;i+=1}
return parentElements}
function mySelectorFunction(hyphenateClass){var tmp,el=[],i=0;if(window.document.getElementsByClassName){el=contextWindow.document.getElementsByClassName(hyphenateClass)}else if(window.document.querySelectorAll){el=contextWindow.document.querySelectorAll('.'+hyphenateClass)}else{tmp=contextWindow.document.getElementsByTagName('*');while(i<tmp.length){if(tmp[i].className.indexOf(hyphenateClass)!==-1&&tmp[i].className.indexOf(dontHyphenateClass)===-1){el.push(tmp[i])}
i+=1}}
return el}
function selectElements(){var elems;if(selectorFunction){elems=selectorFunction()}else{elems=mySelectorFunction(hyphenateClass)}
if(elems.length!==0){elems=flattenNodeList(elems)}
return elems}
var intermediateState='hidden';var unhide='wait';var CSSEditors=[];function makeCSSEdit(w){w=w||window;var doc=w.document,sheet=(function(){var i=0,l=doc.styleSheets.length,s,element,r=!1;while(i<l){s=doc.styleSheets[i];try{if(!!s.cssRules){r=s;break}}catch(ignore){}
i+=1}
if(r===!1){element=doc.createElement('style');element.type='text/css';doc.getElementsByTagName('head')[0].appendChild(element);r=doc.styleSheets[doc.styleSheets.length-1]}
return r}()),changes=[],findRule=function(sel){var s,rule,sheets=w.document.styleSheets,rules,i=0,j=0,r=!1;while(i<sheets.length){s=sheets[i];try{if(!!s.cssRules){rules=s.cssRules}else if(!!s.rules){rules=s.rules}}catch(ignore){}
if(!!rules&&!!rules.length){while(j<rules.length){rule=rules[j];if(rule.selectorText===sel){r={index:j,rule:rule}}
j+=1}}
i+=1}
return r},addRule=function(sel,rulesStr){var i,r;if(!!sheet.insertRule){if(!!sheet.cssRules){i=sheet.cssRules.length}else{i=0}
r=sheet.insertRule(sel+'{'+rulesStr+'}',i)}else if(!!sheet.addRule){if(!!sheet.rules){i=sheet.rules.length}else{i=0}
sheet.addRule(sel,rulesStr,i);r=i}
return r},removeRule=function(sheet,index){if(sheet.deleteRule){sheet.deleteRule(index)}else{sheet.removeRule(index)}};return{setRule:function(sel,rulesString){var i,existingRule,cssText;existingRule=findRule(sel);if(!!existingRule){if(!!existingRule.rule.cssText){cssText=existingRule.rule.cssText}else{cssText=existingRule.rule.style.cssText.toLowerCase()}
if(cssText!==sel+' { '+rulesString+' }'){if(cssText.indexOf(rulesString)!==-1){existingRule.rule.style.visibility=''}
i=addRule(sel,rulesString);changes.push({sheet:sheet,index:i})}}else{i=addRule(sel,rulesString);changes.push({sheet:sheet,index:i})}},clearChanges:function(){var change=changes.pop();while(!!change){removeRule(change.sheet,change.index);change=changes.pop()}}}}
var hyphen=String.fromCharCode(173);var urlhyphen=zeroWidthSpace;function hyphenateURL(url){var tmp=url.replace(/([:\/\.\?#&\-_,;!@]+)/gi,'$&'+urlhyphen),parts=tmp.split(urlhyphen),i=0;while(i<parts.length){if(parts[i].length>(2*min)){parts[i]=parts[i].replace(/(\w{3})(\w)/gi,"$1"+urlhyphen+"$2")}
i+=1}
if(parts[parts.length-1]===""){parts.pop()}
return parts.join(urlhyphen)}
var safeCopy=!0;var zeroTimeOut=(function(){if(window.postMessage&&window.addEventListener){return(function(){var timeouts=[],msg="Hyphenator_zeroTimeOut_message",setZeroTimeOut=function(fn){timeouts.push(fn);window.postMessage(msg,"*")},handleMessage=function(event){if(event.source===window&&event.data===msg){event.stopPropagation();if(timeouts.length>0){timeouts.shift()()}}};window.addEventListener("message",handleMessage,!0);return setZeroTimeOut}())}
return function(fn){window.setTimeout(fn,0)}}());var hyphRunFor={};function runWhenLoaded(w,f){var toplevel,add=window.document.addEventListener?'addEventListener':'attachEvent',rem=window.document.addEventListener?'removeEventListener':'detachEvent',pre=window.document.addEventListener?'':'on';function init(context){if(hyphRunFor[context.location.href]){onWarning(new Error("Warning: multiple execution of Hyphenator.run() – This may slow down the script!"))}
contextWindow=context||window;f();hyphRunFor[contextWindow.location.href]=!0}
function doScrollCheck(){try{w.document.documentElement.doScroll("left")}catch(ignore){window.setTimeout(doScrollCheck,1);return}
if(!hyphRunFor[w.location.href]){documentLoaded=!0;init(w)}}
function doOnEvent(e){var i=0,fl,haveAccess;if(!!e&&e.type==='readystatechange'&&w.document.readyState!=='interactive'&&w.document.readyState!=='complete'){return}
w.document[rem](pre+'DOMContentLoaded',doOnEvent,!1);w.document[rem](pre+'readystatechange',doOnEvent,!1);fl=w.frames.length;if(fl===0||!doFrames){w[rem](pre+'load',doOnEvent,!1);documentLoaded=!0;init(w)}else if(doFrames&&fl>0){if(!!e&&e.type==='load'){w[rem](pre+'load',doOnEvent,!1);while(i<fl){haveAccess=undefined;try{haveAccess=w.frames[i].document.toString()}catch(ignore){haveAccess=undefined}
if(!!haveAccess){runWhenLoaded(w.frames[i],f)}
i+=1}
init(w)}}}
if(documentLoaded||w.document.readyState==='complete'){documentLoaded=!0;doOnEvent({type:'load'})}else{w.document[add](pre+'DOMContentLoaded',doOnEvent,!1);w.document[add](pre+'readystatechange',doOnEvent,!1);w[add](pre+'load',doOnEvent,!1);toplevel=!1;try{toplevel=!window.frameElement}catch(ignore){}
if(toplevel&&w.document.documentElement.doScroll){doScrollCheck()}}}
function getLang(el,fallback){try{return!!el.getAttribute('lang')?el.getAttribute('lang').toLowerCase():!!el.getAttribute('xml:lang')?el.getAttribute('xml:lang').toLowerCase():el.tagName.toLowerCase()!=='html'?getLang(el.parentNode,fallback):fallback?mainLanguage:null}catch(ignore){}}
function autoSetMainLanguage(w){w=w||contextWindow;var el=w.document.getElementsByTagName('html')[0],m=w.document.getElementsByTagName('meta'),i=0,getLangFromUser=function(){var ml,text='',dH=300,dW=450,dX=Math.floor((w.outerWidth-dW)/2)+window.screenX,dY=Math.floor((w.outerHeight-dH)/2)+window.screenY,ul='',languageHint;if(!!window.showModalDialog&&(w.location.href.indexOf(basePath)!==-1)){ml=window.showModalDialog(basePath+'modalLangDialog.html',supportedLangs,"dialogWidth: "+dW+"px; dialogHeight: "+dH+"px; dialogtop: "+dY+"; dialogleft: "+dX+"; center: on; resizable: off; scroll: off;")}else{languageHint=(function(){var r='';forEachKey(supportedLangs,function(k){r+=k+', '});r=r.substring(0,r.length-2);return r}());ul=window.navigator.language||window.navigator.userLanguage;ul=ul.substring(0,2);if(!!supportedLangs[ul]&&supportedLangs[ul].prompt!==''){text=supportedLangs[ul].prompt}else{text=supportedLangs.en.prompt}
text+=' (ISO 639-1)\n\n'+languageHint;ml=window.prompt(window.unescape(text),ul).toLowerCase()}
return ml};mainLanguage=getLang(el,!1);if(!mainLanguage){while(i<m.length){if(!!m[i].getAttribute('http-equiv')&&(m[i].getAttribute('http-equiv').toLowerCase()==='content-language')){mainLanguage=m[i].getAttribute('content').toLowerCase()}
if(!!m[i].getAttribute('name')&&(m[i].getAttribute('name').toLowerCase()==='dc.language')){mainLanguage=m[i].getAttribute('content').toLowerCase()}
if(!!m[i].getAttribute('name')&&(m[i].getAttribute('name').toLowerCase()==='language')){mainLanguage=m[i].getAttribute('content').toLowerCase()}
i+=1}}
if(!mainLanguage&&doFrames&&(!!contextWindow.frameElement)){autoSetMainLanguage(window.parent)}
if(!mainLanguage&&defaultLanguage!==''){mainLanguage=defaultLanguage}
if(!mainLanguage){mainLanguage=getLangFromUser()}
el.lang=mainLanguage}
function gatherDocumentInfos(){var elToProcess,urlhyphenEls,tmp,i=0;function process(el,pLang,isChild){var n,j=0,hyphenate=!0,eLang,useCSS3=function(){css3hyphenateClassHandle=makeCSSEdit(contextWindow);css3hyphenateClassHandle.setRule('.'+css3hyphenateClass,css3_h9n.property+': auto;');css3hyphenateClassHandle.setRule('.'+dontHyphenateClass,css3_h9n.property+': manual;');if((eLang!==pLang)&&css3_h9n.property.indexOf('webkit')!==-1){css3hyphenateClassHandle.setRule('.'+css3hyphenateClass,'-webkit-locale : '+eLang+';')}
el.className=el.className+' '+css3hyphenateClass},useHyphenator=function(){if(isBookmarklet&&eLang!==mainLanguage){return}
if(supportedLangs.hasOwnProperty(eLang)){docLanguages[eLang]=!0}else{if(supportedLangs.hasOwnProperty(eLang.split('-')[0])){eLang=eLang.split('-')[0];docLanguages[eLang]=!0}else if(!isBookmarklet){hyphenate=!1;onError(new Error('Language "'+eLang+'" is not yet supported.'))}}
if(hyphenate){if(intermediateState==='hidden'){el.className=el.className+' '+hideClass}
elements.add(el,eLang)}};isChild=isChild||!1;if(el.lang&&typeof el.lang==='string'){eLang=el.lang.toLowerCase()}else if(!!pLang&&pLang!==''){eLang=pLang.toLowerCase()}else{eLang=getLang(el,!0)}
if(!isChild){if(css3&&css3_h9n.support&&!!css3_h9n.checkLangSupport(eLang)){useCSS3()}else{useHyphenator()}}else{if(eLang!==pLang){if(css3&&css3_h9n.support&&!!css3_h9n.checkLangSupport(eLang)){useCSS3()}else{useHyphenator()}}else{if(!css3||!css3_h9n.support||!css3_h9n.checkLangSupport(eLang)){useHyphenator()}}}
n=el.childNodes[j];while(!!n){if(n.nodeType===1&&!dontHyphenate[n.nodeName.toLowerCase()]&&n.className.indexOf(dontHyphenateClass)===-1&&n.className.indexOf(urlHyphenateClass)===-1&&!elToProcess[n]){process(n,eLang,!0)}
j+=1;n=el.childNodes[j]}}
function processUrlStyled(el){var n,j=0;n=el.childNodes[j];while(!!n){if(n.nodeType===1&&!dontHyphenate[n.nodeName.toLowerCase()]&&n.className.indexOf(dontHyphenateClass)===-1&&n.className.indexOf(hyphenateClass)===-1&&!urlhyphenEls[n]){processUrlStyled(n)}else if(n.nodeType===3){n.data=hyphenateURL(n.data)}
j+=1;n=el.childNodes[j]}}
if(css3){css3_h9n=css3_gethsupport()}
if(isBookmarklet){elToProcess=contextWindow.document.getElementsByTagName('body')[0];process(elToProcess,mainLanguage,!1)}else{if(!css3&&intermediateState==='hidden'){CSSEditors.push(makeCSSEdit(contextWindow));CSSEditors[CSSEditors.length-1].setRule('.'+hyphenateClass,'visibility: hidden;');CSSEditors[CSSEditors.length-1].setRule('.'+hideClass,'visibility: hidden;');CSSEditors[CSSEditors.length-1].setRule('.'+unhideClass,'visibility: visible;')}
elToProcess=selectElements();tmp=elToProcess[i];while(!!tmp){process(tmp,'',!1);i+=1;tmp=elToProcess[i]}
urlhyphenEls=mySelectorFunction(urlHyphenateClass);i=0;tmp=urlhyphenEls[i];while(!!tmp){processUrlStyled(tmp);i+=1;tmp=urlhyphenEls[i]}}
if(elements.counters[0]===0){i=0;while(i<CSSEditors.length){CSSEditors[i].clearChanges();i+=1}
onHyphenationDone(contextWindow.location.href)}}
function makeCharMap(){var int2code=[],code2int={},add=function(newValue){if(!code2int[newValue]){int2code.push(newValue);code2int[newValue]=int2code.length-1}};return{int2code:int2code,code2int:code2int,add:add}}
function makeValueStore(len){var indexes=(function(){var arr;if(Object.prototype.hasOwnProperty.call(window,"Uint32Array")){arr=new window.Uint32Array(3);arr[0]=1;arr[1]=1;arr[2]=1}else{arr=[1,1,1]}
return arr}()),keys=(function(){var i,r;if(Object.prototype.hasOwnProperty.call(window,"Uint8Array")){return new window.Uint8Array(len)}
r=[];r.length=len;i=r.length-1;while(i>=0){r[i]=0;i-=1}
return r}()),add=function(p){keys[indexes[1]]=p;indexes[2]=indexes[1];indexes[1]+=1},add0=function(){indexes[1]+=1},finalize=function(){var start=indexes[0];keys[indexes[2]+1]=255;indexes[0]=indexes[2]+2;indexes[1]=indexes[0];return start};return{keys:keys,add:add,add0:add0,finalize:finalize}}
function convertPatternsToArray(lo){var trieNextEmptyRow=0,i,charMapc2i,valueStore,indexedTrie,trieRowLength,extract=function(patternSizeInt,patterns){var charPos=0,charCode=0,mappedCharCode=0,rowStart=0,nextRowStart=0,prevWasDigit=!1;while(charPos<patterns.length){charCode=patterns.charCodeAt(charPos);if((charPos+1)%patternSizeInt!==0){if(charCode<=57&&charCode>=49){valueStore.add(charCode-48);prevWasDigit=!0}else{if(!prevWasDigit){valueStore.add0()}
prevWasDigit=!1;if(nextRowStart===-1){nextRowStart=trieNextEmptyRow+trieRowLength;trieNextEmptyRow=nextRowStart;indexedTrie[rowStart+mappedCharCode*2]=nextRowStart}
mappedCharCode=charMapc2i[charCode];rowStart=nextRowStart;nextRowStart=indexedTrie[rowStart+mappedCharCode*2];if(nextRowStart===0){indexedTrie[rowStart+mappedCharCode*2]=-1;nextRowStart=-1}}}else{if(charCode<=57&&charCode>=49){valueStore.add(charCode-48);indexedTrie[rowStart+mappedCharCode*2+1]=valueStore.finalize()}else{if(!prevWasDigit){valueStore.add0()}
valueStore.add0();if(nextRowStart===-1){nextRowStart=trieNextEmptyRow+trieRowLength;trieNextEmptyRow=nextRowStart;indexedTrie[rowStart+mappedCharCode*2]=nextRowStart}
mappedCharCode=charMapc2i[charCode];rowStart=nextRowStart;if(indexedTrie[rowStart+mappedCharCode*2]===0){indexedTrie[rowStart+mappedCharCode*2]=-1}
indexedTrie[rowStart+mappedCharCode*2+1]=valueStore.finalize()}
rowStart=0;nextRowStart=0;prevWasDigit=!1}
charPos+=1}};lo.charMap=makeCharMap();i=0;while(i<lo.patternChars.length){lo.charMap.add(lo.patternChars.charCodeAt(i));i+=1}
charMapc2i=lo.charMap.code2int;valueStore=makeValueStore(lo.valueStoreLength);lo.valueStore=valueStore;if(Object.prototype.hasOwnProperty.call(window,"Int32Array")){lo.indexedTrie=new window.Int32Array(lo.patternArrayLength*2)}else{lo.indexedTrie=[];lo.indexedTrie.length=lo.patternArrayLength*2;i=lo.indexedTrie.length-1;while(i>=0){lo.indexedTrie[i]=0;i-=1}}
indexedTrie=lo.indexedTrie;trieRowLength=lo.charMap.int2code.length*2;forEachKey(lo.patterns,function(i){extract(parseInt(i,10),lo.patterns[i])})}
function recreatePattern(pattern,nodePoints){var r=[],c=pattern.split(''),i=0;while(i<=c.length){if(nodePoints[i]&&nodePoints[i]!==0){r.push(nodePoints[i])}
if(c[i]){r.push(c[i])}
i+=1}
return r.join('')}
function convertExceptionsToObject(exc){var w=exc.split(', '),r={},i=0,l=w.length,key;while(i<l){key=w[i].replace(/-/g,'');if(!r.hasOwnProperty(key)){r[key]=w[i]}
i+=1}
return r}
function loadPatterns(lang,cb){var location,xhr,head,script,done=!1;if(supportedLangs.hasOwnProperty(lang)&&!Hyphenator.languages[lang]){location=basePath+'patterns/'+supportedLangs[lang].file}else{return}
if(isLocal&&!isBookmarklet){xhr=null;try{xhr=new window.XMLHttpRequest()}catch(ignore){try{xhr=new window.ActiveXObject("Microsoft.XMLHTTP")}catch(ignore){try{xhr=new window.ActiveXObject("Msxml2.XMLHTTP")}catch(ignore){xhr=null}}}
if(xhr){xhr.open('HEAD',location,!0);xhr.setRequestHeader('Cache-Control','no-cache');xhr.onreadystatechange=function(){if(xhr.readyState===2){if(xhr.status>=400){onError(new Error('Could not load\n'+location));delete docLanguages[lang];return}
xhr.abort()}};xhr.send(null)}}
if(createElem){head=window.document.getElementsByTagName('head').item(0);script=createElem('script',window);script.src=location;script.type='text/javascript';script.charset='utf8';script.onreadystatechange=function(){if(!done&&(!script.readyState||script.readyState==="loaded"||script.readyState==="complete")){done=!0;cb();script.onreadystatechange=null;script.onload=null;if(head&&script.parentNode){head.removeChild(script)}}};script.onload=script.onreadystatechange;head.appendChild(script)}}
function prepareLanguagesObj(lang){var lo=Hyphenator.languages[lang],wrd;if(!lo.prepared){if(enableCache){lo.cache={}}
if(enableReducedPatternSet){lo.redPatSet={}}
if(leftmin>lo.leftmin){lo.leftmin=leftmin}
if(rightmin>lo.rightmin){lo.rightmin=rightmin}
if(lo.hasOwnProperty('exceptions')){Hyphenator.addExceptions(lang,lo.exceptions);delete lo.exceptions}
if(exceptions.hasOwnProperty('global')){if(exceptions.hasOwnProperty(lang)){exceptions[lang]+=', '+exceptions.global}else{exceptions[lang]=exceptions.global}}
if(exceptions.hasOwnProperty(lang)){lo.exceptions=convertExceptionsToObject(exceptions[lang]);delete exceptions[lang]}else{lo.exceptions={}}
convertPatternsToArray(lo);if(String.prototype.normalize){wrd='[\\w'+lo.specialChars+lo.specialChars.normalize("NFD")+String.fromCharCode(173)+String.fromCharCode(8204)+'-]{'+min+',}'}else{wrd='[\\w'+lo.specialChars+String.fromCharCode(173)+String.fromCharCode(8204)+'-]{'+min+',}'}
lo.genRegExp=new RegExp('('+wrd+')|('+url+')|('+mail+')','gi');lo.prepared=!0}}
function prepare(callback){var tmp1;function languagesLoaded(){forEachKey(docLanguages,function(l){if(Hyphenator.languages.hasOwnProperty(l)){delete docLanguages[l];if(!!storage){storage.setItem(l,window.JSON.stringify(Hyphenator.languages[l]))}
prepareLanguagesObj(l);callback(l)}})}
if(!enableRemoteLoading){forEachKey(Hyphenator.languages,function(lang){prepareLanguagesObj(lang)});callback('*');return}
forEachKey(docLanguages,function(lang){if(!!storage&&storage.test(lang)){Hyphenator.languages[lang]=window.JSON.parse(storage.getItem(lang));prepareLanguagesObj(lang);if(exceptions.hasOwnProperty('global')){tmp1=convertExceptionsToObject(exceptions.global);forEachKey(tmp1,function(tmp2){Hyphenator.languages[lang].exceptions[tmp2]=tmp1[tmp2]})}
if(exceptions.hasOwnProperty(lang)){tmp1=convertExceptionsToObject(exceptions[lang]);forEachKey(tmp1,function(tmp2){Hyphenator.languages[lang].exceptions[tmp2]=tmp1[tmp2]});delete exceptions[lang]}
if(String.prototype.normalize){tmp1='[\\w'+Hyphenator.languages[lang].specialChars+Hyphenator.languages[lang].specialChars.normalize("NFD")+String.fromCharCode(173)+String.fromCharCode(8204)+'-]{'+min+',}'}else{tmp1='[\\w'+Hyphenator.languages[lang].specialChars+String.fromCharCode(173)+String.fromCharCode(8204)+'-]{'+min+',}'}
Hyphenator.languages[lang].genRegExp=new RegExp('('+tmp1+')|('+url+')|('+mail+')','gi');if(enableCache){if(!Hyphenator.languages[lang].cache){Hyphenator.languages[lang].cache={}}}
delete docLanguages[lang];callback(lang)}else{loadPatterns(lang,languagesLoaded)}});languagesLoaded()}
var toggleBox=function(){var bdy,myTextNode,text=(Hyphenator.doHyphenation?'Hy-phen-a-tion':'Hyphenation'),myBox=contextWindow.document.getElementById('HyphenatorToggleBox');if(!!myBox){myBox.firstChild.data=text}else{bdy=contextWindow.document.getElementsByTagName('body')[0];myBox=createElem('div',contextWindow);myBox.setAttribute('id','HyphenatorToggleBox');myBox.setAttribute('class',dontHyphenateClass);myTextNode=contextWindow.document.createTextNode(text);myBox.appendChild(myTextNode);myBox.onclick=Hyphenator.toggleHyphenation;myBox.style.position='absolute';myBox.style.top='0px';myBox.style.right='0px';myBox.style.zIndex='1000';myBox.style.margin='0';myBox.style.backgroundColor='#AAAAAA';myBox.style.color='#FFFFFF';myBox.style.font='6pt Arial';myBox.style.letterSpacing='0.2em';myBox.style.padding='3px';myBox.style.cursor='pointer';myBox.style.WebkitBorderBottomLeftRadius='4px';myBox.style.MozBorderRadiusBottomleft='4px';myBox.style.borderBottomLeftRadius='4px';bdy.appendChild(myBox)}};function doCharSubst(loCharSubst,w){var r=w;forEachKey(loCharSubst,function(subst){r=r.replace(new RegExp(subst,'g'),loCharSubst[subst])});return r}
var wwAsMappedCharCodeStore=(function(){if(Object.prototype.hasOwnProperty.call(window,"Int32Array")){return new window.Int32Array(64)}
return[]}());var wwhpStore=(function(){var r;if(Object.prototype.hasOwnProperty.call(window,"Uint8Array")){r=new window.Uint8Array(64)}else{r=[]}
return r}());function hyphenateCompound(lo,lang,word){var hw,parts,i=0;switch(compound){case "auto":parts=word.split('-');while(i<parts.length){parts[i]=hyphenateWord(lo,lang,parts[i]);i+=1}
hw=parts.join('-');break;case "all":parts=word.split('-');while(i<parts.length){parts[i]=hyphenateWord(lo,lang,parts[i]);i+=1}
hw=parts.join('-'+zeroWidthSpace);break;case "hyphen":hw=word.replace('-','-'+zeroWidthSpace);break;default:onError(new Error('Hyphenator.settings: compound setting "'+compound+'" not known.'))}
return hw}
function hyphenateWord(lo,lang,word){var pattern="",ww,wwlen,wwhp=wwhpStore,pstart=0,plen,hp,hpc,wordLength=word.length,hw='',charMap=lo.charMap.code2int,charCode,mappedCharCode,row=0,link=0,value=0,values,indexedTrie=lo.indexedTrie,valueStore=lo.valueStore.keys,wwAsMappedCharCode=wwAsMappedCharCodeStore;word=onBeforeWordHyphenation(word,lang);if(word===''){hw=''}else if(enableCache&&lo.cache&&lo.cache.hasOwnProperty(word)){hw=lo.cache[word]}else if(word.indexOf(hyphen)!==-1){hw=word}else if(lo.exceptions.hasOwnProperty(word)){hw=lo.exceptions[word].replace(/-/g,hyphen)}else if(word.indexOf('-')!==-1){hw=hyphenateCompound(lo,lang,word)}else{ww=word.toLowerCase();if(String.prototype.normalize){ww=ww.normalize("NFC")}
if(lo.hasOwnProperty("charSubstitution")){ww=doCharSubst(lo.charSubstitution,ww)}
if(word.indexOf("'")!==-1){ww=ww.replace(/'/g,"’")}
ww='_'+ww+'_';wwlen=ww.length;while(pstart<wwlen){wwhp[pstart]=0;charCode=ww.charCodeAt(pstart);wwAsMappedCharCode[pstart]=charMap.hasOwnProperty(charCode)?charMap[charCode]:-1;pstart+=1}
pstart=0;while(pstart<wwlen){row=0;pattern='';plen=pstart;while(plen<wwlen){mappedCharCode=wwAsMappedCharCode[plen];if(mappedCharCode===-1){break}
if(enableReducedPatternSet){pattern+=ww.charAt(plen)}
link=indexedTrie[row+mappedCharCode*2];value=indexedTrie[row+mappedCharCode*2+1];if(value>0){hpc=0;hp=valueStore[value+hpc];while(hp!==255){if(hp>wwhp[pstart+hpc]){wwhp[pstart+hpc]=hp}
hpc+=1;hp=valueStore[value+hpc]}
if(enableReducedPatternSet){if(!lo.redPatSet){lo.redPatSet={}}
if(valueStore.subarray){values=valueStore.subarray(value,value+hpc)}else{values=valueStore.slice(value,value+hpc)}
lo.redPatSet[pattern]=recreatePattern(pattern,values)}}
if(link>0){row=link}else{break}
plen+=1}
pstart+=1}
hp=0;while(hp<wordLength){if(hp>=lo.leftmin&&hp<=(wordLength-lo.rightmin)&&(wwhp[hp+1]%2)!==0){hw+=hyphen+word.charAt(hp)}else{hw+=word.charAt(hp)}
hp+=1}}
hw=onAfterWordHyphenation(hw,lang);if(enableCache){lo.cache[word]=hw}
return hw}
function removeHyphenationFromElement(el){var h,u,i=0,n;switch(hyphen){case '|':h='\\|';break;case '+':h='\\+';break;case '*':h='\\*';break;default:h=hyphen}
switch(urlhyphen){case '|':u='\\|';break;case '+':u='\\+';break;case '*':u='\\*';break;default:u=urlhyphen}
n=el.childNodes[i];while(!!n){if(n.nodeType===3){n.data=n.data.replace(new RegExp(h,'g'),'');n.data=n.data.replace(new RegExp(u,'g'),'')}else if(n.nodeType===1){removeHyphenationFromElement(n)}
i+=1;n=el.childNodes[i]}}
var copy=(function(){var makeCopy=function(){var oncopyHandler=function(e){e=e||window.event;var shadow,selection,range,rangeShadow,restore,target=e.target||e.srcElement,currDoc=target.ownerDocument,bdy=currDoc.getElementsByTagName('body')[0],targetWindow=currDoc.defaultView||currDoc.parentWindow;if(target.tagName&&dontHyphenate[target.tagName.toLowerCase()]){return}
shadow=currDoc.createElement('div');shadow.style.color=window.getComputedStyle?targetWindow.getComputedStyle(bdy,null).backgroundColor:'#FFFFFF';shadow.style.fontSize='0px';bdy.appendChild(shadow);if(!!window.getSelection){e.stopPropagation();selection=targetWindow.getSelection();range=selection.getRangeAt(0);shadow.appendChild(range.cloneContents());removeHyphenationFromElement(shadow);selection.selectAllChildren(shadow);restore=function(){shadow.parentNode.removeChild(shadow);selection.removeAllRanges();selection.addRange(range)}}else{e.cancelBubble=!0;selection=targetWindow.document.selection;range=selection.createRange();shadow.innerHTML=range.htmlText;removeHyphenationFromElement(shadow);rangeShadow=bdy.createTextRange();rangeShadow.moveToElementText(shadow);rangeShadow.select();restore=function(){shadow.parentNode.removeChild(shadow);if(range.text!==""){range.select()}}}
zeroTimeOut(restore)},removeOnCopy=function(el){var body=el.ownerDocument.getElementsByTagName('body')[0];if(!body){return}
el=el||body;if(window.removeEventListener){el.removeEventListener("copy",oncopyHandler,!0)}else{el.detachEvent("oncopy",oncopyHandler)}},registerOnCopy=function(el){var body=el.ownerDocument.getElementsByTagName('body')[0];if(!body){return}
el=el||body;if(window.addEventListener){el.addEventListener("copy",oncopyHandler,!0)}else{el.attachEvent("oncopy",oncopyHandler)}};return{oncopyHandler:oncopyHandler,removeOnCopy:removeOnCopy,registerOnCopy:registerOnCopy}};return(safeCopy?makeCopy():!1)}());function checkIfAllDone(){var allDone=!0,i=0,doclist={};elements.each(function(ellist){var j=0,l=ellist.length;while(j<l){allDone=allDone&&ellist[j].hyphenated;if(!doclist.hasOwnProperty(ellist[j].element.baseURI)){doclist[ellist[j].element.ownerDocument.location.href]=!0}
doclist[ellist[j].element.ownerDocument.location.href]=doclist[ellist[j].element.ownerDocument.location.href]&&ellist[j].hyphenated;j+=1}});if(allDone){if(intermediateState==='hidden'&&unhide==='progressive'){elements.each(function(ellist){var j=0,l=ellist.length,el;while(j<l){el=ellist[j].element;el.className=el.className.replace(unhideClassRegExp,'');if(el.className===''){el.removeAttribute('class')}
j+=1}})}
while(i<CSSEditors.length){CSSEditors[i].clearChanges();i+=1}
forEachKey(doclist,function(doc){onHyphenationDone(doc)});if(!!storage&&storage.deferred.length>0){i=0;while(i<storage.deferred.length){storage.deferred[i].call();i+=1}
storage.deferred=[]}}}
function controlOrphans(part){var h,r;switch(hyphen){case '|':h='\\|';break;case '+':h='\\+';break;case '*':h='\\*';break;default:h=hyphen}
part=part.replace(/[\s]*$/,'');if(orphanControl>=2){r=part.split(' ');r[1]=r[1].replace(new RegExp(h,'g'),'');r[1]=r[1].replace(new RegExp(zeroWidthSpace,'g'),'');r=r.join(' ')}
if(orphanControl===3){r=r.replace(/[\ ]+/g,String.fromCharCode(160))}
return r}
function hyphenateElement(lang,elo){var el=elo.element,hyphenate,n,i,lo;if(Hyphenator.languages.hasOwnProperty(lang)&&Hyphenator.doHyphenation){lo=Hyphenator.languages[lang];hyphenate=function(match,word,url,mail){var r;if(!!url||!!mail){r=hyphenateURL(match)}else{r=hyphenateWord(lo,lang,word)}
return r};if(safeCopy&&(el.tagName.toLowerCase()!=='body')){copy.registerOnCopy(el)}
i=0;n=el.childNodes[i];while(!!n){if(n.nodeType===3&&(/\S/).test(n.data)&&n.data.length>=min){n.data=n.data.replace(lo.genRegExp,hyphenate);if(orphanControl!==1){n.data=n.data.replace(/[\S]+\ [\S]+[\s]*$/,controlOrphans)}}
i+=1;n=el.childNodes[i]}}
if(intermediateState==='hidden'&&unhide==='wait'){el.className=el.className.replace(hideClassRegExp,'');if(el.className===''){el.removeAttribute('class')}}
if(intermediateState==='hidden'&&unhide==='progressive'){el.className=el.className.replace(hideClassRegExp,' '+unhideClass)}
elo.hyphenated=!0;elements.counters[1]+=1;if(elements.counters[0]<=elements.counters[1]){checkIfAllDone()}}
function hyphenateLanguageElements(lang){var i=0,l;if(lang==='*'){elements.each(function(lang,ellist){var j=0,le=ellist.length;while(j<le){hyphenateElement(lang,ellist[j]);j+=1}})}else{if(elements.list.hasOwnProperty(lang)){l=elements.list[lang].length;while(i<l){hyphenateElement(lang,elements.list[lang][i]);i+=1}}}}
function removeHyphenationFromDocument(){elements.each(function(ellist){var i=0,l=ellist.length;while(i<l){removeHyphenationFromElement(ellist[i].element);if(safeCopy){copy.removeOnCopy(ellist[i].element)}
ellist[i].hyphenated=!1;i+=1}})}
function createStorage(){var s;function makeStorage(s){var store=s,prefix='Hyphenator_'+Hyphenator.version+'_',deferred=[],test=function(name){var val=store.getItem(prefix+name);return!!val},getItem=function(name){return store.getItem(prefix+name)},setItem=function(name,value){try{store.setItem(prefix+name,value)}catch(e){onError(e)}};return{deferred:deferred,test:test,getItem:getItem,setItem:setItem}}
try{if(storageType!=='none'&&window.JSON!==undefined&&window.localStorage!==undefined&&window.sessionStorage!==undefined&&window.JSON.stringify!==undefined&&window.JSON.parse!==undefined){switch(storageType){case 'session':s=window.sessionStorage;break;case 'local':s=window.localStorage;break;default:s=undefined;break}
s.setItem('storageTest','1');s.removeItem('storageTest')}}catch(ignore){s=undefined}
if(s){storage=makeStorage(s)}else{storage=undefined}}
function storeConfiguration(){if(!storage){return}
var settings={'STORED':!0,'classname':hyphenateClass,'urlclassname':urlHyphenateClass,'donthyphenateclassname':dontHyphenateClass,'minwordlength':min,'hyphenchar':hyphen,'urlhyphenchar':urlhyphen,'togglebox':toggleBox,'displaytogglebox':displayToggleBox,'remoteloading':enableRemoteLoading,'enablecache':enableCache,'enablereducedpatternset':enableReducedPatternSet,'onhyphenationdonecallback':onHyphenationDone,'onerrorhandler':onError,'onwarninghandler':onWarning,'intermediatestate':intermediateState,'selectorfunction':selectorFunction||mySelectorFunction,'safecopy':safeCopy,'doframes':doFrames,'storagetype':storageType,'orphancontrol':orphanControl,'dohyphenation':Hyphenator.doHyphenation,'persistentconfig':persistentConfig,'defaultlanguage':defaultLanguage,'useCSS3hyphenation':css3,'unhide':unhide,'onbeforewordhyphenation':onBeforeWordHyphenation,'onafterwordhyphenation':onAfterWordHyphenation,'leftmin':leftmin,'rightmin':rightmin,'compound':compound};storage.setItem('config',window.JSON.stringify(settings))}
function restoreConfiguration(){var settings;if(storage.test('config')){settings=window.JSON.parse(storage.getItem('config'));Hyphenator.config(settings)}}
var version='5.2.0(devel)';var doHyphenation=!0;var languages={};function config(obj){var assert=function(name,type){var r,t;t=typeof obj[name];if(t===type){r=!0}else{onError(new Error('Config onError: '+name+' must be of type '+type));r=!1}
return r};if(obj.hasOwnProperty('storagetype')){if(assert('storagetype','string')){storageType=obj.storagetype}
if(!storage){createStorage()}}
if(!obj.hasOwnProperty('STORED')&&storage&&obj.hasOwnProperty('persistentconfig')&&obj.persistentconfig===!0){restoreConfiguration()}
forEachKey(obj,function(key){switch(key){case 'STORED':break;case 'classname':if(assert('classname','string')){hyphenateClass=obj[key]}
break;case 'urlclassname':if(assert('urlclassname','string')){urlHyphenateClass=obj[key]}
break;case 'donthyphenateclassname':if(assert('donthyphenateclassname','string')){dontHyphenateClass=obj[key]}
break;case 'minwordlength':if(assert('minwordlength','number')){min=obj[key]}
break;case 'hyphenchar':if(assert('hyphenchar','string')){if(obj.hyphenchar==='&shy;'){obj.hyphenchar=String.fromCharCode(173)}
hyphen=obj[key]}
break;case 'urlhyphenchar':if(obj.hasOwnProperty('urlhyphenchar')){if(assert('urlhyphenchar','string')){urlhyphen=obj[key]}}
break;case 'togglebox':if(assert('togglebox','function')){toggleBox=obj[key]}
break;case 'displaytogglebox':if(assert('displaytogglebox','boolean')){displayToggleBox=obj[key]}
break;case 'remoteloading':if(assert('remoteloading','boolean')){enableRemoteLoading=obj[key]}
break;case 'enablecache':if(assert('enablecache','boolean')){enableCache=obj[key]}
break;case 'enablereducedpatternset':if(assert('enablereducedpatternset','boolean')){enableReducedPatternSet=obj[key]}
break;case 'onhyphenationdonecallback':if(assert('onhyphenationdonecallback','function')){onHyphenationDone=obj[key]}
break;case 'onerrorhandler':if(assert('onerrorhandler','function')){onError=obj[key]}
break;case 'onwarninghandler':if(assert('onwarninghandler','function')){onWarning=obj[key]}
break;case 'intermediatestate':if(assert('intermediatestate','string')){intermediateState=obj[key]}
break;case 'selectorfunction':if(assert('selectorfunction','function')){selectorFunction=obj[key]}
break;case 'safecopy':if(assert('safecopy','boolean')){safeCopy=obj[key]}
break;case 'doframes':if(assert('doframes','boolean')){doFrames=obj[key]}
break;case 'storagetype':if(assert('storagetype','string')){storageType=obj[key]}
break;case 'orphancontrol':if(assert('orphancontrol','number')){orphanControl=obj[key]}
break;case 'dohyphenation':if(assert('dohyphenation','boolean')){Hyphenator.doHyphenation=obj[key]}
break;case 'persistentconfig':if(assert('persistentconfig','boolean')){persistentConfig=obj[key]}
break;case 'defaultlanguage':if(assert('defaultlanguage','string')){defaultLanguage=obj[key]}
break;case 'useCSS3hyphenation':if(assert('useCSS3hyphenation','boolean')){css3=obj[key]}
break;case 'unhide':if(assert('unhide','string')){unhide=obj[key]}
break;case 'onbeforewordhyphenation':if(assert('onbeforewordhyphenation','function')){onBeforeWordHyphenation=obj[key]}
break;case 'onafterwordhyphenation':if(assert('onafterwordhyphenation','function')){onAfterWordHyphenation=obj[key]}
break;case 'leftmin':if(assert('leftmin','number')){leftmin=obj[key]}
break;case 'rightmin':if(assert('rightmin','number')){rightmin=obj[key]}
break;case 'compound':if(assert('compound','string')){compound=obj[key]}
break;default:onError(new Error('Hyphenator.config: property '+key+' not known.'))}});if(storage&&persistentConfig){storeConfiguration()}}
function run(){var process=function(){try{if(contextWindow.document.getElementsByTagName('frameset').length>0){return}
autoSetMainLanguage(undefined);gatherDocumentInfos();if(displayToggleBox){toggleBox()}
prepare(hyphenateLanguageElements)}catch(e){onError(e)}};if(!storage){createStorage()}
runWhenLoaded(window,process)}
function addExceptions(lang,words){if(lang===''){lang='global'}
if(exceptions.hasOwnProperty(lang)){exceptions[lang]+=", "+words}else{exceptions[lang]=words}}
function hyphenate(target,lang){var turnout,n,i,lo;lo=Hyphenator.languages[lang];if(Hyphenator.languages.hasOwnProperty(lang)){if(!lo.prepared){prepareLanguagesObj(lang)}
turnout=function(match,word,url,mail){var r;if(!!url||!!mail){r=hyphenateURL(match)}else{r=hyphenateWord(lo,lang,word)}
return r};if(typeof target==='object'&&!(typeof target==='string'||target.constructor===String)){i=0;n=target.childNodes[i];while(!!n){if(n.nodeType===3&&(/\S/).test(n.data)&&n.data.length>=min){n.data=n.data.replace(lo.genRegExp,turnout)}else if(n.nodeType===1){if(n.lang!==''){Hyphenator.hyphenate(n,n.lang)}else{Hyphenator.hyphenate(n,lang)}}
i+=1;n=target.childNodes[i]}}else if(typeof target==='string'||target.constructor===String){return target.replace(lo.genRegExp,turnout)}}else{onError(new Error('Language "'+lang+'" is not loaded.'))}}
function getRedPatternSet(lang){return Hyphenator.languages[lang].redPatSet}
function getConfigFromURI(){var loc=null,re={},jsArray=contextWindow.document.getElementsByTagName('script'),i=0,j=0,l=jsArray.length,s,gp,option;while(i<l){if(!!jsArray[i].getAttribute('src')){loc=jsArray[i].getAttribute('src')}
if(loc&&(loc.indexOf('Hyphenator.js?')!==-1)){s=loc.indexOf('Hyphenator.js?');gp=loc.substring(s+14).split('&');while(j<gp.length){option=gp[j].split('=');if(option[0]!=='bm'){if(option[1]==='true'){option[1]=!0}else if(option[1]==='false'){option[1]=!1}else if(isFinite(option[1])){option[1]=parseInt(option[1],10)}
if(option[0]==='togglebox'||option[0]==='onhyphenationdonecallback'||option[0]==='onerrorhandler'||option[0]==='selectorfunction'||option[0]==='onbeforewordhyphenation'||option[0]==='onafterwordhyphenation'){option[1]=new Function('',option[1])}
re[option[0]]=option[1]}
j+=1}
break}
i+=1}
return re}
function toggleHyphenation(){if(Hyphenator.doHyphenation){if(!!css3hyphenateClassHandle){css3hyphenateClassHandle.setRule('.'+css3hyphenateClass,css3_h9n.property+': none;')}
removeHyphenationFromDocument();Hyphenator.doHyphenation=!1;storeConfiguration();toggleBox()}else{if(!!css3hyphenateClassHandle){css3hyphenateClassHandle.setRule('.'+css3hyphenateClass,css3_h9n.property+': auto;')}
Hyphenator.doHyphenation=!0;hyphenateLanguageElements('*');storeConfiguration();toggleBox()}}
return{version:version,doHyphenation:doHyphenation,languages:languages,config:config,run:run,addExceptions:addExceptions,hyphenate:hyphenate,getRedPatternSet:getRedPatternSet,isBookmarklet:isBookmarklet,getConfigFromURI:getConfigFromURI,toggleHyphenation:toggleHyphenation}}(window));if(Hyphenator.isBookmarklet){Hyphenator.config({displaytogglebox:!0,intermediatestate:'visible',storagetype:'local',doframes:!0,useCSS3hyphenation:!0});Hyphenator.config(Hyphenator.getConfigFromURI());Hyphenator.run()}
0