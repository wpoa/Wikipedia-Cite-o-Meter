<?php
/**
 * Tabular data handler 
 */

echo <<<EOT
	<html>
	  <head>
	  	<title>Wikipedia Cite-o-Meter: Statistics for $doi[$doip]</title>
	  	<link rel="stylesheet" type="text/css" href="style.css" />
	    <script type='text/javascript' src='https://www.google.com/jsapi'></script>
	    <script type='text/javascript'>
	      google.load('visualization', '1', {packages:['table']});
	      google.setOnLoadCallback(drawTable);
	      function drawTable() {
	        var data = new google.visualization.DataTable();
	        data.addColumn('string', 'Project');
	        data.addColumn('number', 'Citations');
	        data.addRows($cl);
EOT;

	$k = 0;
	foreach ($langs as $l)
	{
		echo "        data.setCell($k, 0, '<a href=\"http://$l.wikipedia.org/w/index.php?title=Special:Search&search=$doip\">$l</a>');";
		echo "        data.setCell($k, 1, $t[$l]);";
		$k++;
	}

echo <<<EOT
	        var table = new google.visualization.Table(document.getElementById('table_div'));
	        table.draw(data, {showRowNumber: true, allowHtml: true});
	      }
	    </script>
	  </head>
	  <body>
	  	<h1><a href="$wc_url">Wikipedia Cite-o-Meter</a></h1>
	    <h2>Citations for <strong>$doi[$doip]</strong> (<strong>$doip</strong>) in the top 100 Wikipedias</h2>
	    <h3>[<a href="$graph_url">stats</a>] [data] [<a href="$json_url">json</a>]</h3>
	    <div id='table_div'></div>
	  $footer
	  </body>
	</html>
EOT;
?>