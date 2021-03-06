<?PHP
  // Code based on https://php.happycodings.com/html-and-php/code19.html

  // DB connection info
  $DBUSER = getenv("MYSQL_USER");
  $DBPASS = getenv("MYSQL_PASSWORD");
  $DBHOST = getenv("MYSQL_SERVICE_HOST");
  $DBNAME = getenv("MYSQL_DATABASE");
     
  // initialize variable that will hold the quote. 
  $quote = ""; 
  // establish connection to the database containing the quotes. 
  $db = new mysqli($DBHOST, $DBUSER, $DBPASS); //or die ("Unable to connect to database."); 
  if ($db->connect_error) {
    die("Connection failed. Please make sure you have the MYSQL_SERVICE_HOST, MYSQL_USER, MYSQL_PASSWORD, and MYSQL_DATABASE environment variables : " . $db->connect_error);
  }
  mysqli_select_db($db, $DBNAME) or die ("Unable to select database."); 
  
  // select the quotes that have not been displayed (q_mark = 0). 
  $sql = "SELECT * from quote WHERE q_mark = 0"; 
  $result = mysqli_query($db, $sql); 
  // simple error checking 
  if (mysqli_errno($db)>0) { 
    echo "<BR>\n<FONT COLOR=\"#990000\">".mysqli_errno($db).": ".mysqli_error($db)."<BR>\n"; 
    exit; 
  } 
  // put the number of rows found into $max. 
  $max = mysqli_num_rows($result)-1; 
  
  // if we do not find an available quote, then mark them (q_mark) to 0 and select again. 
  if ($max < 0) { 
    $result = mysqli_query($db, "UPDATE quote SET q_mark = 0"); 
      if (mysqli_errno($db)>0) { 
        echo "<BR>\n<FONT COLOR=\"#990000\">".mysqli_errno($db).": ".mysqli_error($db)."<BR>\n"; 
        exit; 
      } 
    $sql = "SELECT * from quote WHERE q_mark = 0"; 
    $result = mysqli_query($db, $sql); 
    if (mysqli_errno($db)>0) { 
      echo "<BR>\n<FONT COLOR=\"#990000\">".mysqli_errno($db).": ".mysqli_error($db)."<BR>\n"; 
      exit; 
    } 
    $max = mysqli_num_rows($result)-1; 
  } 
  // generate a random number between 0 and the number of quotes available. 
  mt_srand((double)microtime()*1000000); 
  if ($max > 0) { 
    $random = mt_rand(0,$max); 
  } else { 
    $random = 0; 
  } 
  // select the random quote and store the text in $quote and it's id in $id. 
  for ($x=0;$x<=$random;$x++) { 
    $myrow = mysqli_fetch_array($result); 
  } 
  $id = $myrow[0]; 
  $quote = $myrow[1]; 
  // mark this selected quote as displayed (q_mark = 1). 
  $result = mysqli_query($db, "UPDATE quote SET q_mark = 1 WHERE quote_id = '$id'"); 
  if (mysqli_errno($db)>0) { 
    echo "<BR>\n<FONT COLOR=\"#990000\">".mysqli_errno($db).": ".mysqli_error($db)."<BR>\n"; 
    exit; 
  } 
  // convert to HTML special characters, you know, like ?, ?, ?, and so on. 
  $quote = nl2br(htmlentities($quote)); 
  // finally replace the "<" and ">" for < and > so you that can use tags. 
  //$quote = preg_replace ("<", "<", $quote); 
  //$quote = preg_replace (">", ">", $quote); 
  echo $quote."<BR>\n"; 
?>
