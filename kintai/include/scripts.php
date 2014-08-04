<script>
	$(function() {
		/** hiding scroll bar of .container tag **/
		var cw = $(".container").width();
		var sw = 17;
		var ncw = cw + sw;
		$(".container").width(ncw);

		$(".popup").click(function() {
			window.open($(this).attr("url"), '_blank', 'directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no');
		});

	}); 
</script>