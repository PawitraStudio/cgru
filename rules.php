<?php

function listDir( $i_readdir, &$o_out)
{
	$out = array();
	$dir = $i_readdir['readdir'];
	$rules = $i_readdir['rules'];
	$out['dir'] = $dir;

	$dir = str_replace('../','', $dir);
	$dir = str_replace('/..','', $dir);
	$dir = str_replace('..','', $dir);

	if( false == is_dir( $dir))
	{
		$out['error'] = 'No such folder.';
	}
	else if( $handle = opendir( $dir))
	{
		$out['folders'] = array();
		$out['files'] = array();
		$out['rufiles'] = array();
		$out['rules'] = array();
		$numdir = 0;
		$numfile = 0;
		while (false !== ( $entry = readdir( $handle)))
		{
			if( $entry == '.') continue;
			if( $entry == '..') continue;
			$path = $dir.'/'.$entry;
			if( false == is_dir( $path))
			{
				$out['files'][$numfile++] = $entry;
				continue;
			}

			$out['folders'][$numdir++] = $entry;

			if( $entry != $rules ) continue;

			$numrufile = 0;
			if( $rHandle = opendir( $path))
			{
				while (false !== ( $entry = readdir( $rHandle)))
				{
					if( $entry == '.') continue;
					if( $entry == '..') continue;
					$out['rufiles'][$numrufile++] = $entry;
					if( strrpos( $entry,'.json') == strlen($entry)-5)
					{
						if( $fHandle = fopen( $path.'/'.$entry, 'r'))
						{
							$rudata = fread( $fHandle, 1000000);
							$ruobj = json_decode( $rudata, true);
							$out['rules'][$entry] = $ruobj;
							fclose($fHandle);
						}
					}
				}
				closedir($rHandle);
				ksort($out['rules']);
			}
		}
		closedir($handle);
		sort( $out['folders']);
		sort( $out['files']);
		sort( $out['rufiles']);
	}

	$o_out['readdir'] = $out;
}

function readConfig( $i_file, &$o_out)
{
	if( $fHandle = fopen( $i_file, 'r'))
	{
		$data = fread( $fHandle, 1000000);
		fclose($fHandle);
		$o_out[$i_file] = json_decode( $data, true);
		if( $o_out[$i_file]['cgru_config']['include'] )
			foreach( $o_out[$i_file]['cgru_config']['include'] as $file )
				readConfig( $file, $o_out);
	}
}

$recv = json_decode( $HTTP_RAW_POST_DATA, true);
$out = array();
if( $recv['readdir'])
{
	listDir($recv, $out);
}
else if( $recv['readconfig'])
{
	$configs = array();
	readConfig( $recv['readconfig'], $configs); 
	$out['config'] = $configs;
}

echo json_encode( $out);

?>

