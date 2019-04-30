<!DOCTYPE html>
<html>
<head>
<title>Bible Search - Kindle Gen</title>
<link type="text/css" href="styles.css" rel="Stylesheet"/>
</head>
<body>
   
<header>
<?php

$submitted = $_GET['s'];
$errors = [];

if ($submitted && $_FILES['uploadedFile']['type'] == 'text/csv') {
    set_time_limit(10*60); // 10 minutes
    try {
        include "bible_to_sql.php";
        $csv = [];
    
        $fp = new SplFileObject($_FILES['uploadedFile']["tmp_name"]);
        $count = 0;
        while (!$fp->eof()) {
            $row = $fp->fgetcsv();

            $append = implode(",", $row);
            if (!empty($append)) {
                $csv[] = $append;
            }
        
            // stop over achievers
            $count++;
            if ($count > 366) {
                $errors[] = "CSV files must contain less than 367 entries.";
                break;
            }
        }

        $csvcount = count($csv);

        // get length of uploaded csv
        // if length > 365 continue
        if ($csvcount < 364) {
            $errors[] = "CSV files must have more than 364 entries. Saw: $csvcount.";
            throw new Exception("CSV files must have more than 364 entries. Saw: $csvcount.");
        }

        $datesArray=[];
        // leap years -_-
        if ($csvcount == 365) {
            $datesArray = $datesArray365;
            $toc = "toc365";
            $kjv = "KJVinaYear365.opf";
        } else {
            $datesArray = $datesArray366;
            $toc = "toc366";
            $kjv = "KJVinaYear366.opf";
        }

        // make folder
        $kindle_name = preg_replace('/[^a-zA-Z0-9]/', '', $_POST['kindle-name']);
        $workingPath = __DIR__ ."/output/$kindle_name";
        exec("rm -rf $workingPath");
        mkdir($workingPath, 0777, true);

        // loop 366 times
        for ($i=0; $i < $csvcount; $i++) {
            $today = $datesArray[$i];
            $todayHTML = preg_replace('/\s/', '', $today);
            // create file
            $fp = fopen("$workingPath/$todayHTML.html", "w");

            // curl api for files // index.php?k=1&date=September%2030&b=1%20Kings%202,Galatians%206,Ezekiel%2033,Psalm%2081,Psalm%2082
            $curlURL = "http://localhost/index.php?k=1&date=".urlencode($today)."&b=".urlencode($csv[$i]);
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $curlURL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
        
            // write file contents
            fwrite($fp, $result);
        
            fclose($fp);
        }
        // copy files into folder
        file_put_contents("$workingPath/mobi.css", file_get_contents(__DIR__.'/mobi.css'));
        file_put_contents("$workingPath/kf8.css", file_get_contents(__DIR__.'/kf8.css'));
        file_put_contents("$workingPath/styles.css", file_get_contents(__DIR__.'/styles.css'));
        file_put_contents("$workingPath/toc.html", file_get_contents(__DIR__."/$toc.html"));
        file_put_contents("$workingPath/toc.ncx", file_get_contents(__DIR__."/$toc.ncx"));
        file_put_contents("$workingPath/cover.jpg", file_get_contents(__DIR__.'/cover.jpg'));
        file_put_contents("$workingPath/linlibertine_bd-4.1.5ro-webfont.ttf", file_get_contents(__DIR__.'/linlibertine_bd-4.1.5ro-webfont.ttf'));
        file_put_contents("$workingPath/linlibertine_re-4.7.5ro-webfont.ttf", file_get_contents(__DIR__.'/linlibertine_re-4.7.5ro-webfont.ttf'));
        file_put_contents("$workingPath/KJVinaYear.opf", file_get_contents(__DIR__."/$kjv"));
        $exec = "docker-compose exec api kindlegen $workingPath/KJVinaYear.opf -c2 -verbose -o $kindle_name.mobi";
        if (empty($returnRes)) {
            $errors[] = "Done making the file.";
            $errors[] = "Run from a new terminal: '$exec'";
        } else {
            $errors = $output;
        }
    } catch (Exception $e) {
    }
} else {
    $errors[] = "Uploaded file must be a csv";
}
?>
<?php 
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<div class='error'>$error</div>";
    }
}
?>
	
<form action="kindleGen.php?s=1" method="POST" enctype="multipart/form-data">
<label for="kindle-name">Kindle Book Name: </label><input type="text" name="kindle-name" value="" />
<div>
      <label for="uploadedFile">Upload a File:</label>
      <input type="file" name="uploadedFile" />
    </div>
<input type="submit" value="generate" /><br />

</form>

</header>
<main>
	
</main>

<footer>
</footer>

</body>
</html>