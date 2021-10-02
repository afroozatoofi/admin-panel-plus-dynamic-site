<?php
if (! empty ( $clinics )) {
	foreach ( $clinics as $clinic ) {
		echo "<a href='".str_replace($_SERVER ['HTTP_HOST'],$clinic['englishName'].".".$_SERVER ['HTTP_HOST'],Router::url('', true))."'>";
		echo "<img src=".$cdnroot.$clinic['image']." />";	
		echo $clinic['name'];
		echo "</a>";
	}
}
?>