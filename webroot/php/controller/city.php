<?

require_once('../model/db.php');
require_once('../model/model_event.php');

require_once('../view/view_common.php');
require_once('../view/view_city.php');

//if ($_GET['q']) {
	$E = new Event();
	$events = $E->getEvents();
	$E->closeConn();
//}

view_head();
view_city($events);
view_foot();

?>