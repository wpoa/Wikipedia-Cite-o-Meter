<?php

//defaults
define('DOI_PREFIX', '10.1515');
define('LANG', 'en');
date_default_timezone_set('UTC');
$base_url = 'wikipedia.org/w/api.php?action=query&list=search&format=json&srsearch=';
$base_url_commons = 'commons.wikimedia.org/w/api.php?action=query&list=search&format=json&srnamespace=6&srsearch=';
$commons_search = 'http://commons.wikimedia.org/w/index.php?title=Special%3ASearch&redirs=1&fulltext=Search&ns6=1&title=Special%3ASearch&search=';
$wc_url = $_SERVER['PHP_SELF'];
$langs = array('en','de','fr','it','pl','es','ru','ja','nl','pt','sv','zh','ca','uk','no','fi','vi','cs','hu','ko','tr','id','ro','fa','ar','da','eo','sr','lt','sk','he','ms','bg','sl','vo','eu','war','hr','hi','et','az','kk','gl','simple','nn','new','th','el','roa-rup','la','tl','ht','ka','mk','te','sh','pms','ceb','be-x-old','br','ta','jv','lv','mr','sq','cy','lb','be','is','bs','oc','yo','an','bpy','mg','bn','io','sw','fy','lmo','gu','ml','pnb','af','nds','scn','ur','qu','ku','zh-yue','ne','diq','hy','ast','su','nap','ga','cv','bat-smg','tt');
$footer = '	<div id="footer"><a href="?about"><strong>Wikipedia Cite-o-Meter</strong></a> was hacked in 2011 by <a href="http://nitens.org">Dario Taraborelli</a> (<a href="mailto:dtaraborelli@wikimedia.org">@</a>) [<a href="http://github.com/dartar/Wikipedia-Cite-o-Meter">code</a>] and <a href="http://evomri.net/">Daniel Mietchen</a> (<a href="mailto:daniel.mietchen@evomri.net">@</a>) [<a href="http://meta.wikimedia.org/wiki/Wikimedian_in_Residence_on_Open_Science/Reusing_Open_Access_materials">concept and early data</a>]. Data released under <a href="http://creativecommons.org/publicdomain/zero/1.0/">CC0</a>, code released under <a href="http://www.gnu.org/licenses/gpl-2.0.html">GPL</a>.</div>'."\n";

//functions
function getPage($proxy, $url, $referer, $agent, $header, $timeout) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXY, $proxy);
    curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_REFERER, $referer);
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);

    $result['EXE'] = curl_exec($ch);
    $result['INF'] = curl_getinfo($ch);
    $result['ERR'] = curl_error($ch);
    curl_close($ch);
    return $result;
}

//--------------- Read DOI table
$doi = array();
$doidef = './doi_pref.tsv';
if (($handle = fopen($doidef, "r")) !== FALSE)
{
    while (($data = fgetcsv($handle, 255, "\t")) !== FALSE)
    {
		$doi[$data[1]] = $data[0];
    }
    fclose($handle);
}

//--------------- Default view

$default =<<<EOD
<html>
  <head>
	  <title>Wikipedia Cite-o-Meter: Find citations by publisher in Wikipedia</title>
	  <link rel="stylesheet" type="text/css" href="style.css" />
  </head>
  <body>
	<h1><a href="$wc_url">Wikipedia Cite-o-Meter</a></h1>
    <h2>Find citations by publisher in the top 100 Wikipedias <a title="About Wikipedia Cite-o-Meter" href="?about">(read more)</a></h2>
	<form onsubmit="document.DOISearch.SubmitButton.disabled=true; document.getElementById('loading').style.display = ''; document.getElementById('crossref').style.display = 'none';" id="DOISearch" name="DOISearch" method="get">
	<fieldset><legend>Select a publisher</legend>
    	<select name="doip">
EOD;

foreach($doi as $k=>$v)
{
	$default .= '	<option value="'.$k.'">'.$v.' ('.$k.')</option>'."\n";
}

$default .=<<<EOD
    	</select>
    	<input type="submit" value="Submit" id="SubmitButton" />
		<p id="crossref" class="small sp">Source: <a href="http://www.crossref.org/06members/50go-live.html">CrossRef</a> (last updated: 2011-09-24)</p>
		<p id="loading" class="small sp" style="display: none; color: #933"><img src="ajax-loader.gif" /> Retrieving data (this may take a few minutes)</p>
	</fieldset>

	<fieldset><legend>...or try one of the following</legend>
	<ul>
		<li><a class="small" href="?doip=10.1126">American Association for the Advancement of Science (AAAS)</a></li>
		<li><a class="small" href="?doip=10.1021">American Chemical Society (ACS)</a></li>
		<li><a class="small" href="?doip=10.5194">Copernicus GmbH</a></li>
		<li><a class="small" href="?doip=10.1016">Elsevier</a></li>
		<li><a class="small" href="?doip=10.1529">Elsevier - Biophysical Society</a></li>
		<li><a class="small" href="?doip=10.3389">Frontiers Research Foundation</a></li>
		<li><a class="small" href="?doip=10.1155">Hindawi Publishing Corporation</a></li>
		<li><a class="small" href="?doip=10.1080">Informa UK (Taylor & Francis)</a></li>
		<li><a class="small" href="?doip=10.1088">IOP Publishing</a></li>
		<li><a class="small" href="?doip=10.1038">Nature Publishing Group</a></li>
		<li><a class="small" href="?doip=10.3897">Pensoft Publishers</a></li>
		<li><a class="small" href="?doip=10.1073">Proceedings of the National Academy of Sciences (PNAS)</a></li>
		<li><a class="small" href="?doip=10.1371">Public Library of Science</a></li>
		<li><a class="small" href="?doip=10.1177">Sage Publications</a></li>
		<li><a class="small" href="?doip=10.1007">Springer-Verlag</a></li>
		<li><a class="small" href="?doip=10.1186">Springer (Biomed Central Ltd.)</a></li>
		<li><a class="small" href="?doip=10.1098">The Royal Society</a></li>
		<li><a class="small" href="?doip=10.1515">Walter de Gruyter</a></li>
		<li><a class="small" href="?doip=10.1111">Wiley Blackwell (Blackwell Publishing)</a></li>
	</ul>
	</fieldset>
    </form>
    $footer
</body>
</html>
EOD;

if (isset($_GET['doip']))
{

	//Sanitize input
	//only allow valid DOI prefixes
	if (isset($_GET['doip']) && in_array($_GET['doip'], array_keys($doi)))
	{
		$doip = $_GET['doip'];
	}
	else
	{
		$doip = DOI_PREFIX;
	}

	//only allow valid Wikipedia projects
	if (isset($_GET['lang']) && in_array($_GET['lang'], $langs))
	{
	        $lang = $_GET['lang'];
	}
	else
	{
	        $lang = LANG;
	}

	$file = './data/'.$doip.'.txt';
	$file_commons = './data/'.$doip.'_commons.txt';
	$t = array();
	$tcommons = array();

	//get data from cache or retrieve them otherwise
	if (file_exists($file) || isset($_GET['refresh']))
	{
	    $fm = date("Y-m-d H:i:s", filemtime($file));
		$t = unserialize(file_get_contents($file));
	}
	else
	{
	    $fm = date("Y-m-d H:i:s.");

		foreach ($langs as $l)
		{
		    $count = '';
		    $url = 'http://'.$l.'.'.$base_url.$doip;
		    $result = getPage(
		    '',// leave it blank if using no proxy
		    $url,
		    '', // leave it blank if no referer
		    'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8',
		    0,
		    15);

			$res = json_decode($result['EXE'], TRUE);
			$count = $res['query']['searchinfo']['totalhits'];
			$t[$l] = $count;
		}
		$out = serialize($t);
		file_put_contents($file, $out);
	}

	//get data from commons
	if (file_exists($file_commons) || isset($_GET['refresh']))
	{
		$tcommons = unserialize(file_get_contents($file_commons));
	}
	else
	{
		    $count = '';
		    $url = 'http://'.$base_url_commons.$doip;
		    $result = getPage(
		    '',// leave it blank if using no proxy
		    $url,
		    '', // leave it blank if no referer
		    'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8',
		    0,
		    15);

			$res = json_decode($result['EXE'], TRUE);
			$count = $res['query']['searchinfo']['totalhits'];
			$tcommons[0] = $count;
		$outc = serialize($tcommons);
		file_put_contents($file_commons, $outc);
	}

	//prepare links
	$graph_url = "?doip=$doip";
	$table_url = "?doip=$doip&amp;table";
	$json_url = "?doip=$doip&amp;json";

	// calculate metrics
	$cl = count($langs);
	$totalcit = array_sum($t);
	$tc = $t;
	arsort($tc);
	$topl = array_keys($tc);
	$topc = $t[$topl[0]];
	$totalcommons = $tcommons[0];

//--------------- Stats view

	$output = <<<EOF
	<html>
	  <head>
	  	<title>Wikipedia Cite-o-Meter: Statistics for $doi[$doip]</title>
	  	<link rel="stylesheet" type="text/css" href="style.css" />
	    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
	    <script type="text/javascript">
	      google.load("visualization", "1", {packages:["corechart"]});
	      google.setOnLoadCallback(drawChart);
	      function drawChart() {
	        var data = new google.visualization.DataTable();
	        data.addColumn('string', 'Project');
	        data.addColumn('number', 'Citations');
	        data.addRows([
EOF;

	//split array in 2
	$langs1 = array_slice($langs, 0, 49);
	$langs2 = array_slice($langs, 50, 99);
	foreach ($langs1 as $l)
	{
		$output .= "          ['$l', $t[$l]],";
	}

	$output .= <<<EOF
	        ]);

	        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
	        chart.draw(data, {width: 1200, height: 240, legend: 'none',
				  vAxis: {logScale: true, format:'#,###'},
	                          hAxis: {slantedTextAngle: 60}
	                         });
	      	}
	    </script>
	    <script type="text/javascript">
	      google.load("visualization", "1", {packages:["corechart"]});
	      google.setOnLoadCallback(drawChart2);
	      function drawChart2() {
	        var data = new google.visualization.DataTable();
	        data.addColumn('string', 'Project');
	        data.addColumn('number', 'Citations');
	        data.addRows([
EOF;

	foreach($langs2 as $l)
	{
		$output .= "          ['$l', $t[$l]],";
	}
	$maxv = $t['en'];

	$output .= <<<EOF
	        ]);

	        var chart = new google.visualization.AreaChart(document.getElementById('chart_div2'));
	        chart.draw(data, {width: 1200, height: 240, legend: 'none',
				  				vAxis: {logScale: true, format:'#,###', maxValue: $maxv},
	                          	hAxis: {slantedTextAngle: 60}
	                         });
	     	}
	    </script>
	  </head>
	  <body>
	  	<h1><a href="$wc_url">Wikipedia Cite-o-Meter</a></h1>
	    <h2>Citations for <strong>$doi[$doip]</strong> (<strong>$doip</strong>) in the top 100 Wikipedias</h2>
	    <h3>[stats] [<a href="$table_url">data</a>] [<a href="$json_url">json</a>]</h3>
	    <div style="margin:0" id="chart_div"></div>
	    <div style="margin:0" id="chart_div2"></div>

		<h2>Statistics for <strong>$doi[$doip]</strong> (<strong>$doip</strong>)</h2>
		<ul>
			<li>Total number of citations across Wikipedia: <strong>$totalcit</strong></li>
			<li>Project with the largest number of citations: <strong>$topl[0]</strong> ($topc citations)</li>
			<li>Total number of citations in Wikimedia Commons: <strong><a href="$commons_search$doip">$totalcommons</a></strong></li>
			<li>Data last updated: <strong>$fm</strong></li>
		</ul>
    	$footer
	  </body>
	</html>
EOF;

//--------------- Table view

	$output2 = <<<EOT
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
		$output2 .="        data.setCell($k, 0, '<a href=\"http://$l.wikipedia.org/w/index.php?title=Special:Search&search=$doip\">$l</a>');";
		$output2 .="        data.setCell($k, 1, $t[$l]);";
		$k++;
	}

	$output2 .=<<<EOT
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

//--------------- JSON view

	$jout = array(
		'doi_prefix' => $doip,
		'publisher_name' => $doi[$doip],
		'timestamp' => $fm,
		'total_wp_citations' => $totalcit,
		'top_wp_by_citations' => $topl[0],
		'total_commons_citations' => $totalcommons,
		'wp' => $t
		);
	$output0 = json_encode($jout);

//--------------- Switch views

	if (isset($_GET['table']))
	{
		echo $output2;
	}
	else if(isset($_GET['json']))
	{
		header('Cache-Control: no-cache, must-revalidate');
		header('Content-type: application/json');
		echo $output0;
	}
	else
	{
		echo $output;
	}
}
else if(isset($_GET['about']))
{
	include('./about.inc.php');
}
else
{

	echo $default;
}
?>
