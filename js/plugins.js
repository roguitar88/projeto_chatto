$(document).ready(function(){
    
//	var alphabet = "abcdef".split("");
//	alphabet.each(function(letter) {
////	  $('.emotion-area').append('<img src="images/emojis/1f60${letter}.png"');
//		console.log(letter);
//	});
	
	function ApndImgEmotion() {
		for (var i = 65; i <= 70; i++) {
			$('.emotion-area').append(
				'<img width="30px" height="30px" src="images/emojis/1f60' + String.fromCharCode(i).toLowerCase() + '.png">' + 
				'<img width="30px" height="30px" src="images/emojis/1f61' + String.fromCharCode(i).toLowerCase() + '.png">' + 
				'<img width="30px" height="30px" src="images/emojis/1f62' + String.fromCharCode(i).toLowerCase() + '.png">' + 
				'<img width="30px" height="30px" src="images/emojis/1f47' + String.fromCharCode(i).toLowerCase() + '.png">' +
				'<img width="30px" height="30px" src="images/emojis/1f49' + String.fromCharCode(i).toLowerCase() + '.png">'
			);
		}
		
		for (var i = 4; i <= 8; i++) {
			$('.emotion-area').append(
				'<img width="30px" height="30px" src="images/emojis/1f32' + i + '.png">'
			);
		}
		
		for (var i = 3; i <= 8; i++) {
			$('.emotion-area').append(
				'<img width="30px" height="30px" src="images/emojis/1f49' + i + '.png">'
			);
		}
		
		for (var i = 0; i <= 9; i++) {
			$('.emotion-area').append(
				'<img width="30px" height="30px" src="images/emojis/1f60' + i + '.png">'
			);
		}
		
		for (var i = 10; i <= 44; i++) {
			$('.emotion-area').append(
				'<img width="30px" height="30px" src="images/emojis/1f6' + i + '.png">'
			);
		}
		
		for (var i = 10; i <= 17; i++) {
			$('.emotion-area').append(
				'<img width="30px" height="30px" src="images/emojis/1f9' + i + '.png">'
			);
		}
	}
	
//	$(document).one('click' , '.emotion-Icon', function(e){
//		ApndImgEmotion();
//	});
	
	$(document).on('click' , '.emotion-Icon', function(e){
		var top = $(this).offset().top ,
			top = Math.floor(top),
			emotionArea = $(this).find('.emotion-area');
		
		emotionArea.toggleClass('ShowImotion');
		
		if( top <= 160 ){
			emotionArea.toggleClass('top');
		}
		
		if(!emotionArea.hasClass('ShowImotion')){
			$('.emotion-area').empty();
			emotionArea.removeClass('top');
		}else{
			ApndImgEmotion();
		}
		
	});
	
	$(document).on('click', '.emotion-area' ,function(e){
		e.stopPropagation();
	});
	
	$(document).on('click' , '.emotion-area img', function(e){
		var imgIcon = $(this).clone();
		$(this).parents('.emotion').find('.input').append(imgIcon);
	});
	
});













