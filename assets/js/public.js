(function(){
	'use strict';

	function getCookie(name){
		var match=document.cookie.match(new RegExp('(^| )'+name+'=([^;]+)'));
		return match?match[2]:null;
	}

	function setCookie(name,value,days){
		var expires='';
		if(days){
			var date=new Date();
			date.setTime(date.getTime()+(days*24*60*60*1000));
			expires='; expires='+date.toUTCString();
		}
		document.cookie=name+'='+value+expires+'; path=/; SameSite=Lax';
	}

	function initBar(bar){
		if(!bar || !bar.classList.contains('is-dismissible')){
			return;
		}

		var versionHash=bar.getAttribute('data-version-hash')||'default';
		var cookieName='aaweb_ab_closed_'+versionHash;
		var closeBtn=bar.querySelector('.aaweb-ab-close');
		var dismissDays=parseInt(bar.getAttribute('data-dismiss-days')||'7',10);

		if(getCookie(cookieName)==='1'){
			bar.style.display='none';
			return;
		}

		if(closeBtn){
			closeBtn.addEventListener('click',function(){
				bar.style.display='none';
				setCookie(cookieName,'1',dismissDays);
			});
		}
	}

	document.addEventListener('DOMContentLoaded',function(){
		var bars=document.querySelectorAll('.aaweb-ab-wrap');
		for(var i=0;i<bars.length;i++){
			initBar(bars[i]);
		}
	});
})();
