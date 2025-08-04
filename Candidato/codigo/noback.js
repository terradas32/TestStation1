if (window.history) {
        function noBack(){
	        window.location.hash="no-back-button";
	        window.location.hash="Again-No-back-button"; //chrome
	        window.onhashchange=function(){window.location.hash="no-back-button";}
        }
        noBack();
        window.onload=noBack;
        window.onpageshow=function(evt){if(evt.persisted)noBack()}
        window.onunload=function(){void(0)}
}