<?

require_once('../model/db.php');
require_once('../model/model_event.php');

require_once('../view/view_common.php');
require_once('../view/view_event.php');

if ($_GET['q']) {
	$E = new Event();
	$event = $E->getEvent($_GET['q']);
	$E->closeConn();
}

view_head();
view_event($event);
view_foot();

?>