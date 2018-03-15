$(window).on('load', function() {
		// Animate loader off screen
		$(".se-pre-con").fadeOut("slow");
});


window.sr = ScrollReveal({ duration: 2000 });
sr.reveal(document.getElementById('about-left'));
sr.reveal(document.getElementById('about-right'));
sr.reveal(document.querySelectorAll('.sub-heading-div'));

$(".answer").click(function (e) {
    $("#cancel_form").submit();
});