$('#city').change(function(e) {
	e.preventDefault();
	console.log($(this).val());
});

$('body').on('click', '.twitterLogin', function(e) {
	e.preventDefault();
	auth.login('twitter');
});