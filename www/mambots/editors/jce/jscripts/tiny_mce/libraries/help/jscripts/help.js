Element.extend({	
	makeResizable: function(options){
		return new Drag.Base(this, 'width', '', options);
	}
});
function init(){
	$('helpTree').makeResizable({handle: $('handle'), xMin: '1', xMax: '300'});
}
function loadFrame(h){
	$('helpFrame').src = h;
}
function checkTreeWidth(){
	if($('helpTree').offsetWidth >0){
		$('handleBtn').className = 'handleExpand';
	}else{
		$('handleBtn').className = 'handleCollapse';
	}
}
function toggleTree(){
	myEffect.toggle();
	checkTreeWidth();
}