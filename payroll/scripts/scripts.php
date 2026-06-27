<script type="text/javascript" src="scripts/lib/prototype.js"></script>
<script type="text/javascript" src="scripts/src/builder.js"></script>
<script type="text/javascript" src="scripts/src/controls.js"></script>
<script type="text/javascript" src="scripts/src/dragdrop.js"></script>
<script type="text/javascript" src="scripts/src/effects.js"></script>
<script type="text/javascript" src="scripts/src/scriptaculous.js"></script>
<script type="text/javascript" src="scripts/src/slider.js"></script>
<script type="text/javascript" src="scripts/src/sound.js"></script>
<script type="text/javascript" src="scripts/src/shortcuts.js"></script>
<script>
    function dbcon(act,dev,myform){
	mynodes=myform.serialize();	
	new Ajax.Updater(dev,'ajax.php?acts='+act,{
		method: 'post',
                onComplete: function(){dbcon('afterdeptselectforunit','unitdiv',employeefrm);},
		parameters: mynodes
	});	
    }
    function report(page){
	mynodes=frmrpt.serialize();	
	new Ajax.Updater('main_content_wrap','reports/'+page+'.php',{
		method: 'get',
		parameters: mynodes
	});	
    }
</script>

