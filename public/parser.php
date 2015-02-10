<?php
	$handle=@fopen("./camscon-slug.csv", "r");
	$newdir = "../app/database/seeds";
	$newFilename = "/RestrictedNicknamesTableSeeder.php";
	$linetmp = 0;
	if ($handle) {
		$output=fopen($newdir.$newFilename, 'w');
		$headerText = "<?php\nclass RestrictedNicknamesTableSeeder extends Seeder {\n\n 	public function run() {\n 		Eloquent::unguard();\n 		DB::table('restricted_nicknames')->delete();
		DB::table('restricted_nicknames')->truncate();";
		fwrite($output, $headerText."\n");
		while (($line = fgets($handle, 4096)) !== false) { 
			$line = rtrim($line);
			if($linetmp !== $line){
				$insertSQL=vsprintf('		RestrictedNicknames::create(array(\'nickname\'=>\'%s\'));',$line);
				fwrite($output, $insertSQL."\n");
				$linetmp = $line;
			}
		}
		$footerText = "	}\n}\n?>";
		fwrite($output, $footerText);
		fclose($output);
		fclose($handle);
	}
?>