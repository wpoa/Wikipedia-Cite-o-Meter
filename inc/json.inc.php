<?php
/**
 * JSON handler 
 */
header('Cache-Control: no-cache, must-revalidate');
header('Content-type: application/json');

$jout = array(
		'doi_prefix' => $doip,
		'publisher_name' => $doi[$doip],
		'timestamp' => $fm,
		'total_wp_citations' => $totalcit,
		'top_wp_by_citations' => $topl[0],
		'total_commons_citations' => $totalcommons,
		'wp' => $t
	);
echo json_encode($jout,JSON_PRETTY_PRINT);
?>