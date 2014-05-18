<?
require_once('../model/db.php');
require_once('../model/model_event.php');

print_r($_GET);

if ($_GET['zipcode'] && $_GET['radius'] && $_GET['date'] && $_GET['page'] != null) {
	$E = new Event();
	$E->jambaseZip($_GET['zipcode'], $_GET['radius'], $_GET['date'], $_GET['page']);
	$E->closeConn();
} else {
	echo 'no';
}

?>