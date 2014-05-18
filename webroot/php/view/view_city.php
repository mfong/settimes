<?

function view_city($events) { //print_r($events);?>
<div class="container">
<h1>San Francisco</h1>
<table class="table table-striped table-hover showlist">
<?foreach ($events as $e) { $ed = json_decode($e['json']); //print_r($ed);?>
	<tr>
		<td><a href="event.php?q=<?=$e['id']?>"><?for ($i=0; $i<sizeof($ed->Artists); $i++) {
			echo $ed->Artists[$i]->Name;
			if ($i == sizeof($ed->Artists)-2) {
				echo ' and ';
			} else if ($i < sizeof($ed->Artists)-1) {
				echo ', ';
			}
			}?> @ <?=$ed->Venue->Name?></a></td>
	</tr>
<?}?>
</table>
</div>
<?}?>