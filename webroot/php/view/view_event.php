<?

function view_event($event) { //print_r($event);
	$ed = json_decode($event['json']);?>

<div id="coverphoto"></div>
<div class="container">
<div class="artist">
<h1><?for ($i=0; $i<sizeof($ed->Artists); $i++) {
			echo $ed->Artists[$i]->Name;
			if ($i == sizeof($ed->Artists)-2) {
				echo ' and ';
			} else if ($i < sizeof($ed->Artists)-1) {
				echo ', ';
			}
			}?> @ <?=$ed->Venue->Name?></h1>
</div>
<input type='text' id='nameInput' placeholder='Name'>
<input type='text' id='messageInput' placeholder='Message'>

<div id='messagesDiv'></div>


</div>

<script type='text/javascript' src='https://cdn.firebase.com/js/simple-login/1.4.1/firebase-simple-login.js'></script>
<script>
var myDataRef = new Firebase('https://settimes.firebaseio.com/' + <?=$event['id']?>);
var auth = new FirebaseSimpleLogin(myDataRef, function(error, user) {
  if (error) {
    // an error occurred while attempting login
    console.log(error);
  } else if (user) {
    // user authenticated with Firebase
    console.log('User ID: ' + user.uid + ', Provider: ' + user.provider);
    console.log(user.displayName + ' ' + user.thirdPartyUserData + ' ' + user.username);
    console.log(user.thirdPartyUserData);
    var twitterpic = $('<img/>').attr('class', 'twitterpic').attr('src', user.thirdPartyUserData.profile_image_url);
    var twitterUser = $('<div/>').attr('class', 'twitteruser').html(user.displayName + ' (<a href="//twitter.com/' + user.username + '" target="_blank">@' + user.username + '</a>)').prepend(twitterpic);

    $('#nameInput').val(twitterUser.html()).hide();
    $('#nameInput').after(twitterUser);
  } else {
  	var twitterLogin = $('<a>').attr('href', '#').attr('class', 'twitterLogin').text('Sign in with Twitter');
    $('#messageInput').after(twitterLogin);
  }
});
$('#messageInput').keypress(function (e) {
	if (e.keyCode == 13) {
		var name = $('#nameInput').val();
		var text = $('#messageInput').val();
		var score = 0;
		myDataRef.push({name: name, text: text, score: score});
		$('#messageInput').val('');
	}
});

myDataRef.on('child_added', function(snapshot) {
	var message = snapshot.val();
	displayChatMessage(snapshot.ref().toString(), message.name, message.text, message.score);
});

function displayChatMessage(ref, name, text, score) {
	var scoreDiv = $('<div/>').attr('class', 'score').attr('fbref', ref);
		$('<a/>').attr('href', '#').attr('class', 'vote voteup').html('<i class="fa fa-chevron-up"></i>').appendTo(scoreDiv);
		$('<p/>').text(score).appendTo(scoreDiv);
		$('<a/>').attr('href', '#').attr('class', 'vote votedown').html('<i class="fa fa-chevron-down"></i>').appendTo(scoreDiv);

	$('<div/>').html(text).prepend($('<div/>').attr('class', 'twitteruser').html(name+': ')).prepend(scoreDiv).appendTo($('#messagesDiv'));
	$('#messagesDiv')[0].scrollTop = $('#messagesDiv')[0].scrollHeight;
};

$('#messagesDiv').on('click', '.vote', function (e) {
	e.preventDefault();

	var voteRef = new Firebase($(this).parent().attr('fbref'));
	var score = $(this).parent().find('p').text();
	if ($(this).hasClass('voteup')) {
		score++;
	} else if ($(this).hasClass('votedown')) {
		score--;
	}
	voteRef.child('score').set(score);
	$(this).parent().find('p').text(score);
});

var facebook = '';
var musicbrainz = '';
$.getJSON('http://developer.echonest.com/api/v4/artist/urls?api_key=B0ZOOGX45LXICO51L&id=jambase:artist:<?=$ed->Artists[0]->Id?>&format=json', function (data) {
	if (data.response.status.message == 'Success') {
		$.each(data.response.urls, function(k, v) {
			//console.log(k);
			if (k == 'facebook_url' || k == 'fb_url') {
				facebook = v;
				displayFacebook(facebook);
				//break;
			}
			if (k == 'mb_url') {
				musicbrainz = v.replace('http://musicbrainz.org/artist/', '').replace('.html', '');
			}
		});

		if (musicbrainz != '') {
			$.getJSON('http://musicbrainz.org/ws/2/artist/' + musicbrainz + '?inc=url-rels&fmt=json', function(data) {
				console.log(data);
				for (var i = 0; i < data.relations.length; i++) {
					if (data.relations[i].url.resource.indexOf('facebook.com') > -1) {
						//console.log(data.relations[i].url.resource);
						//console.log(data.relations[i].url.resource.substring(data.relations[i].url.resource.indexOf('facebook.com') + 13));
						facebook = data.relations[i].url.resource.substring(data.relations[i].url.resource.indexOf('facebook.com') + 13);
					}
				}

				console.log(facebook);
				displayFacebook(facebook);
			});
		}
	}
});

function displayFacebook(facebookid) {
	$.getJSON('https://graph.facebook.com/' + facebookid + '?fields=cover', function(data) {
		//console.log(data.cover.source);
		$('#coverphoto').css('background-image', "url('" + data.cover.source + "')");
		$('#coverphoto').css('height', '350px');
	});

	$.getJSON('https://graph.facebook.com/' + facebookid + '/picture?redirect=0&height=200&type=normal&width=200', function(data) {
		if (!data.data.is_silhouette) {
			var img = $('<img/>').attr('class', 'profilepic').attr('src', data.data.url);
			$('.artist').prepend(img);
		}
	});
}
</script>

<?}?>