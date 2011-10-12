<?php

//--------------- init
define('DEFAULT_DOI_PREFIX', '10.1515');
define('DEFAULT_LANG', 'en');
date_default_timezone_set('UTC');
$base_url = 'wikipedia.org/w/api.php?action=query&list=search&format=json&srsearch=';
$base_url_commons = 'commons.wikimedia.org/w/api.php?action=query&list=search&format=json&srnamespace=6&srsearch=';
$commons_search = 'http://commons.wikimedia.org/w/index.php?title=Special%3ASearch&redirs=1&fulltext=Search&ns6=1&title=Special%3ASearch&search=';
$wc_url = $_SERVER['PHP_SELF'];
$langs = array('en','de','fr','it','pl','es','ru','ja','nl','pt','sv','zh','ca','uk','no','fi','vi','cs','hu','ko','tr','id','ro','fa','ar','da','eo','sr','lt','sk','he','ms','bg','sl','vo','eu','war','hr','hi','et','az','kk','gl','simple','nn','new','th','el','roa-rup','la','tl','ht','ka','mk','te','sh','pms','ceb','be-x-old','br','ta','jv','lv','mr','sq','cy','lb','be','is','bs','oc','yo','an','bpy','mg','bn','io','sw','fy','lmo','gu','ml','pnb','af','nds','scn','ur','qu','ku','zh-yue','ne','diq','hy','ast','su','nap','ga','cv','bat-smg','tt');
$footer = '	<div id="footer"><a href="?about"><strong>Wikipedia Cite-o-Meter</strong></a> hacked in 2011 by <a href="http://nitens.org">Dario Taraborelli</a> (<a href="mailto:dtaraborelli@wikimedia.org">@</a>) [<a href="http://github.com/dartar/Wikipedia-Cite-o-Meter">code</a>] and <a href="http://evomri.net/">Daniel Mietchen</a> (<a href="mailto:daniel.mietchen@evomri.net">@</a>) [<a href="http://meta.wikimedia.org/wiki/Wikimedian_in_Residence_on_Open_Science/Reusing_Open_Access_materials">concept and early data</a>] &bull; Data released under <a href="http://creativecommons.org/publicdomain/zero/1.0/">CC0</a> &bull; Code released under <a href="http://www.gnu.org/licenses/gpl-2.0.html">GPL</a></div>'."\n";

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

//--------------- Process request
//only allow valid Wikipedia projects
if (isset($_GET['lang']) && in_array($_GET['lang'], $langs))
{
        $lang = $_GET['lang'];
}
else
{
        $lang = DEFAULT_LANG;
}

if (isset($_GET['doip']))
{
	//only allow valid DOI prefixes
	if (isset($_GET['doip']) && in_array($_GET['doip'], array_keys($doi)))
	{
		$doip = $_GET['doip'];
	}
	else
	{
		$doip = DEFAULT_DOI_PREFIX;
	}

	$file = './data/'.$doip.'.txt';
	$file_commons = './data/'.$doip.'_commons.txt';
	$t = array();
	$tcommons = array();

	//get data from cache or call API
	if (file_exists($file) || isset($_GET['refresh']))
	{
	    $fm = date("Y-m-d H:i:s", filemtime($file));
		$t = unserialize(file_get_contents($file));
	}
	else
	{
	    $fm = date("Y-m-d H:i:s.");

		//get data from x.wp API
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

	//get data from commons API
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

	//slice array
	$langs1 = array_slice($langs, 0, 49);
	$langs2 = array_slice($langs, 50, 99);
	$cl1 = count($langs1);
	$cl2 = count($langs2);

//--------------- Load handlers
	
	//sortable table
	if (isset($_GET['table']))
	{
		include('./inc/table.inc.php');
	}
	//json
	else if(isset($_GET['json']))
	{
		include('./inc/json.inc.php');
	}
	//chart
	else
	{
		include('./inc/chart.inc.php');
	}
}
//all
else if(isset($_GET['lang']))
{
	include('./inc/lang.inc.php');
}
//display about page
else if(isset($_GET['about']))
{
	include('./inc/about.inc.php');
}
//display default page
else
{
	include('./inc/default.inc.php');
}
?>