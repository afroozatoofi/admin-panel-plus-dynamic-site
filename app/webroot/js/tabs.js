function resetTabs() {
	$("#sections-content > div").hide();
	$("#sections a").removeClass('current');
}
$(document).ready(function(){
	$("#sections-content").show();
	$("#sections-content > div").hide();
	$("#sections li:first a").addClass("current");
	$("#sections-content > div:first").fadeIn();

	$("#sections a").on("click", function(e) {
		e.preventDefault();
		if ($(this).hasClass("current")) {
			return;
		} else {
			resetTabs();
			$(this).addClass("current");
			$($(this).attr('data-name')).fadeIn();
		}
	});

});