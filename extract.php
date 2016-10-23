<?php

if(empty($argv[1]) || empty($argv[2])){
	die("Usage: command <MARKDOWN_FILE> <OUTPUT_DIR> \n");
}

if(!is_file($argv[1])){
	die("Markdown file not found: $argv[1] \n");
}

if(!is_dir($argv[2])){
	die("Output dir not found: $argv[2] \n");
}

$lines = preg_split("/\r\n|\n/",file_get_contents($argv[1]));
$todir = rtrim($argv[2],'/');
$heading = ''; // last parsed heading
$code_lines = array(); // lines of current snippet being parsed
$parsing_code = false; // indicates if in "parsing code" state
$count = 0; // number of found snippets

// Parse md file
foreach($lines as $line){

	if(!$parsing_code){

		$line = trim($line);

		if(empty($line)){
			continue;
		}

		// Is heading?
		if(substr($line,0,1)=='#'){
			$heading = trim(ltrim($line, '#'));
		} else if(substr($line,0,3)=='```'){
			// Prepare for parsing a snippet
			$parsing_code = true;
			$code_lines = array(); //
		}

	} else {
		
		// Parsing code

		if(substr($line,0,3)=='```'){

			// END of snippet
			$parsing_code = false;

			// Not a php code?
			$fst_line = $code_lines[0];
			if(substr($fst_line,0,5)!='<?php'&&substr($fst_line,0,2)!='<?'){
				continue;
			}

			$count++; 

			// Save code to file named like "02-how_to_use_this.php"
			$file_name = $count < 10 ? '0'.$count : $count;
			if($heading){
				$file_name .= "-".heading2filename($heading).'.php';
			} else {
				$file_name .= "-untitled_snippet.php";
			}

			$destination = $todir."/".$file_name;

			echo "Extracting snippet to $destination \n";

			file_put_contents($destination, implode("\n",$code_lines));

		} else {
			$code_lines []= $line;
		}

	}

}

echo "Total snippets extracted: $count \n";

function unaccent($string){
  return preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml|caron);~i', '$1', htmlentities($string, ENT_COMPAT, 'UTF-8'));
}

function heading2filename($heading){
	$words = [];
	foreach(explode(' ',$heading) as $word){
		$word = unaccent($word);
		if(strlen($word)<3){
			continue;
		}
		$words[] = strtolower($word);
	}
	return implode('_',$words);
}
