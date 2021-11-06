$(function(){
	$('.btn-login').click(function(){
		$('.login').css({
			display:'block',
			});
		});
	$('.btn-register').click(function(){
		$('.register').css({
			display:'block',
			});
		});
		
});

$(function(){
	$(window).scroll(function(){
		if($(window).scrollTop()>250){
			$('.top').css({
				height:'40px',
				});
				$('.header-tengah').css({
					position:'fixed',
					height:'50px',
					top:'0',
					background:'#39B4B2',
					color:'white',
					boxShadow:'0px 0px 5px 0px #188b89',
					});
					
				$('#ht-tiga input,#ht-tiga select').css({
					border:'0',
					});
		}else{
			$('.top').css({
				height:'0px',
				});
			$('.header-tengah').css({
					position:'relative',
					background:'#fff',
					color:'black',
					boxShadow:'0px 0px 0px 0px #188b89',
					});
			$('#ht-tiga input,#ht-tiga select').css({
					border:'solid 1px lightgray',
					});
		}
		});	
		$('.top').click(function(){
			$('body,html').animate({
				scrollTop:0,
				},1000);
				return false;
		});
});