/* Copyright (c) MEN AT WORK 2011 :: LGPL license */  
window.addEvent("domready",function(){$$('img.ping').each(function(item){var url=item.getSiblings('span').getElement('span').getProperty('html')+'GPL.txt';var req=new Request.HTML({method:'get',url:'ping.php',data:{hostIP:url},evalScripts:false,evalResponse:false,onSuccess:function(responseTree,responseElements,response,js){if(response=='true'){item.setProperty('src','system/modules/syncCto/html/missing.png');var req2=new Request.HTML({method:'get',url:'ping.php',data:{hostIP:url.replace(/GPL.txt/g,"syncCto.php")},evalScripts:false,evalResponse:false,onSuccess:function(responseTree,responseElements,response,js){if(response=='true'){item.setProperty('src','system/modules/syncCto/html/online.png');}}}).send();}else{item.setProperty('src','system/modules/syncCto/html/offline.png');}},onFailure:function(responseTree,responseElements,response,js){item.setProperty('src','system/modules/syncCto/html/offline.png');}}).send();});});