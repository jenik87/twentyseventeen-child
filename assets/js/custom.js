(function($) {
    
    $(document).on('ready', function (e) {

    	function randomize() {
			document.getElementById('ts-color-picker-title').style.color = randomColors();
		}

		function randomColors() {
			var color = '#' + Math.floor(Math.random() * 16777215).toString(16);
			console.log('New color : '+color);
			return color;
		}

		$('#ts-color-picker-title').click(function () {
			randomize();
	    });

    });
    
})(jQuery);