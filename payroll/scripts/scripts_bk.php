<?php include('my_pagina_class.php');?>
<script type="text/javascript" src="scripts/lib/prototype.js"></script>
<script type="text/javascript" src="scripts/src/builder.js"></script>
<script type="text/javascript" src="scripts/src/controls.js"></script>
<script type="text/javascript" src="scripts/src/dragdrop.js"></script>
<script type="text/javascript" src="scripts/src/effects.js"></script>
<script type="text/javascript" src="scripts/src/scriptaculous.js"></script>
<script type="text/javascript" src="scripts/src/slider.js"></script>
<script type="text/javascript" src="scripts/src/sound.js"></script>
<script type="text/javascript" src="scripts/src/shortcuts.js"></script>
<script type="text/javascript" src="scripts/src/unittest.js"></script>
<script type="text/javascript" src="scripts/src/supernote_commented.js"></script>
<script type="text/javascript" src="scripts/src/supernote.js"></script>
<script>
function dbcon(act,dev,myform){
	mynodes=myform.serialize();	
	new Ajax.Updater(dev,'actions.php?acts='+act,{
		method: 'get',
		onLoading: function(){$(dev).innerHTML='<img src="images/ajax.gif">'},
		onComplete: function(){Effect.BlindUp('bady');},
		parameters: mynodes
	});	
}
function searchproducts(syt,act,dev,myform){
	var nxtid=$(snxtid).value;
	if($('sfor').value!='waley'){
		$('sforhid').value=$('sfor').value;
		$('sfor').disabled=true;
		mynodes=myform.serialize();	
		new Ajax.Updater(dev,'actions.php?acts='+act+'&syt='+syt+'&nxtid='+nxtid,{
		method: 'get',
		onLoading: function(){$(dev).innerHTML='<img src="images/ajax.gif">'},
		parameters: mynodes
		});
	}
	else{
		alert('You need to select the FROM site first before you can add product!');
	}
}
function inserttotemp(act,dev,myform){
	mynodes=myform.serialize();	
	new Ajax.Updater(dev,'actions.php?acts='+act,{
		method: 'get',
		onLoading: function(){$(dev).innerHTML='<img src="images/ajax.gif">'},
		onSuccess: function(){
					var nxtid=$(snxtid).value;
					mynodes=myform.serialize();	
					new Ajax.Updater('selectedproducts','actions.php?acts=getFromTemp&nxtid='+nxtid,{
					method: 'get',
					parameters: mynodes
				});
				},
		parameters: mynodes
	});
}
function getDataFromTemp(){
	var nxtid=$(snxtid).value;
	mynodes=myform.serialize();	
	new Ajax.Updater('selectedproducts','actions.php?acts=getFromTemp&nxtid='+nxtid,{
		method: 'get',
		onLoading: function(){$(dev).innerHTML='<img src="images/ajax.gif">'},
		parameters: mynodes
	});
}

///start PO
function searchproductsforpo(act,dev,myform){
	var nxtid=$(snxtid).value;
	var nxtid=$(snxtid).value;
		$('sforhid').value=$('sfor').value;
		$('sfor').disabled=true;
		mynodes=myform.serialize();	
		new Ajax.Updater(dev,'actions.php?acts='+act+'&nxtid='+nxtid,{
		method: 'get',
		onLoading: function(){$(dev).innerHTML='<img src="images/ajax.gif">'},
		parameters: mynodes
		});
	
}
function inserttotemppo(act,dev,myform){
	mynodes=myform.serialize();	
	new Ajax.Updater(dev,'actions.php?acts='+act,{
		method: 'get',
		onLoading: function(){$(dev).innerHTML='<img src="images/ajax.gif">'},
		onSuccess: function(){
					var sups=$(sfor).value;
					var nxtid=$(snxtid).value;
					mynodes=myform.serialize();	
					new Ajax.Updater('selectedproducts','actions.php?acts=getFromTemppo&nxtid='+nxtid+'&sups='+sups,{
					method: 'get',
					parameters: mynodes
				});
				},
		parameters: mynodes
	});
}
function dbconpo(act,dev,myform){
	
	mynodes=myform.serialize();	
	new Ajax.Updater(dev,'actions.php?acts='+act,{
		method: 'get',
		onLoading: function(){$(dev).innerHTML='<img src="images/ajax.gif">'},
		onComplete: function(){Effect.BlindUp('bady');},
		parameters: mynodes
	});	
}

//start issuance
function dbconcc(act,dev,myform){
	mynodes=myform.serialize();	
	new Ajax.Updater(dev,'actions.php?acts='+act,{
		method: 'get',
		onLoading: function(){$(dev).innerHTML='<img src="images/ajax.gif">'},
		onComplete: function(){Effect.BlindUp('bady');},
		parameters: mynodes
	});	
}
function searchproductscc(syt,act,dev,myform){
	var nxtid=$(snxtid).value;
	if($('sfor').value!='waley'){
		$('sforhid').value=$('sfor').value;
		$('sfor').disabled=true;
		mynodes=myform.serialize();	
		new Ajax.Updater(dev,'actions.php?acts='+act+'&syt='+syt+'&nxtid='+nxtid,{
		method: 'get',
		onLoading: function(){$(dev).innerHTML='<img src="images/ajax.gif">'},
		parameters: mynodes
		});
	}
	else{
		alert('You need to select the FROM site first before you can add product!');
	}
}
function inserttotempcc(act,dev,myform){
	mynodes=myform.serialize();	
	new Ajax.Updater(dev,'actions.php?acts='+act,{
		method: 'get',
		onLoading: function(){$(dev).innerHTML='<img src="images/ajax.gif">'},
		onSuccess: function(){
					var nxtid=$(snxtid).value;
					mynodes=myform.serialize();	
					new Ajax.Updater('selectedproducts','actions.php?acts=getFromTempcc&nxtid='+nxtid,{
					method: 'get',
					parameters: mynodes
				});
				},
		parameters: mynodes
	});
}
//end issuance
//start manual rr
function dbconmrr(act,dev,myform){
	mynodes=myform.serialize();	
	new Ajax.Updater(dev,'actions.php?acts='+act,{
		method: 'get',
		onLoading: function(){$(dev).innerHTML='<img src="images/ajax.gif">'},
		onComplete: function(){Effect.BlindUp('bady');},
		parameters: mynodes
	});	
}
function searchproductsmrr(syt,act,dev,myform){
	var nxtid=$(snxtid).value;
	if($('sfor').value!='waley'){
		$('sforhid').value=$('sfor').value;
		$('sfor').disabled=true;
		mynodes=myform.serialize();	
		new Ajax.Updater(dev,'actions.php?acts='+act+'&nxtid='+nxtid,{
		method: 'get',
		onLoading: function(){$(dev).innerHTML='<img src="images/ajax.gif">'},
		parameters: mynodes
		});
	}
	else{
		alert('You need to select supplier first before you can add product!');
	}
}
function inserttotempmrr(act,dev,myform){
	mynodes=myform.serialize();	
	new Ajax.Updater(dev,'actions.php?acts='+act,{
		method: 'get',
		onLoading: function(){$(dev).innerHTML='<img src="images/ajax.gif">'},
		onSuccess: function(){
					var nxtid=$(snxtid).value;
					mynodes=myform.serialize();	
					new Ajax.Updater('selectedproducts','actions.php?acts=getFromTempmrr&nxtid='+nxtid,{
					method: 'get',
					parameters: mynodes
				});
				},
		parameters: mynodes
	});
}
//end manual rr
//start general
function dbcong(act,dev,myform){
	mynodes=myform.serialize();
	taypss=$(tayps).value;	
	new Ajax.Updater(dev,'actions.php?acts='+act,{
		method: 'get',
		onLoading: function(){$(dev).innerHTML='<img src="images/ajax.gif">'},
		onComplete: function(){Effect.BlindUp('bady');},
		parameters: mynodes
	});	
}
function searchproductsg(syt,act,dev,myform){
	var nxtid=$(snxtid).value;
	if($('sfor').value!='waley'){
		$('sforhid').value=$('sfor').value;
		$('sfor').disabled=true;
		mynodes=myform.serialize();	
		new Ajax.Updater(dev,'actions.php?acts='+act+'&nxtid='+nxtid+'&syt='+syt,{
		method: 'get',
		onLoading: function(){$(dev).innerHTML='<img src="images/ajax.gif">'},
		parameters: mynodes
		});
	}
	else{
		alert('You need to select site first before you can add product!');
	}
}
function inserttotempg(act,dev,myform){
	mynodes=myform.serialize();	
	new Ajax.Updater(dev,'actions.php?acts='+act,{
		method: 'get',
		onLoading: function(){$(dev).innerHTML='<img src="images/ajax.gif">'},
		onSuccess: function(){
					var nxtid=$(snxtid).value;
					mynodes=myform.serialize();	
					new Ajax.Updater('selectedproducts','actions.php?acts=getFromTempg&nxtid='+nxtid,{
					method: 'get',
					parameters: mynodes
				});
				},
		parameters: mynodes
	});
}
//end general
function comgross(gross,net,numb){
	$(numb).value=gross/1.12;
}
function comnet(net,gross,numb){
	$(numb).value=net * 1.12;
}
function warn(inv,inp,ting){
	if(parseInt(inp)>parseInt(inv)){
		var r=confirm("You had entered a value that is more than the current onhand qty, This will cause a negative inventory. Are you sure you want to continue?");
		if (r==true)
		  {
		  	return true;
		  }
		else
		  {
			$(ting).value=0;
		  }
	}	
}

//add item to transactions
function searchprod(act,dev,myform){
		mynodes=myform.serialize();	
		new Ajax.Updater(dev,'actadditem.php?acts='+act,{
		method: 'get',
		onLoading: function(){$(dev).innerHTML='<img src="images/ajax.gif">'},
		parameters: mynodes
		});
}
function searchprodstdr(act,dev,myform){
		var frm=$(frmid).value;
		mynodes=myform.serialize();	
		new Ajax.Updater(dev,'actadditem.php?acts='+act+'&frm='+frm,{
		method: 'get',
		onLoading: function(){$(dev).innerHTML='<img src="images/ajax.gif">'},
		parameters: mynodes
		});
}
</script>

