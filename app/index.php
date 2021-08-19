<?PHP
  // Code based on https://php.happycodings.com/html-and-php/code19.html

  // DB connection info
  $DB_USER = "simple-quote";
  $DB_PASS = "RED!hat@"
  $DB_HOST = "localhost"
  $DB_NAME = "quotes"
     
  // initialize variable that will hold the quote. 
  $quote = ""; 
  // establish connection to the database containing the quotes. 
  $db = mysql_connect($DB_HOST, $DB_USER, $DB_PASS) or die ("Unable to connect to database."); 
  mysql_select_db($DB_NAME) or die ("Unable to select database."); 
  
  // select the quotes that have not been displayed (q_mark = 0). 
  $sql = "SELECT * from quote WHERE q_mark = 0"; 
  $result = mysql_query($sql); 
  // simple error checking 
  if (mysql_errno()>0) { 
    echo "<BR>\n<FONT COLOR=\"#990000\">".mysql_errno().": ".mysql_error()."<BR>\n"; 
    exit; 
  } 
  // put the number of rows found into $max. 
  $max = mysql_num_rows($result)-1; 
  
  // if we do not find an available quote, then mark them (q_mark) to 0 and select again. 
  if ($max < 0) { 
    $result = mysql_query("UPDATE quote SET q_mark = 0"); 
      if (mysql_errno()>0) { 
        echo "<BR>\n<FONT COLOR=\"#990000\">".mysql_errno().": ".mysql_error()."<BR>\n"; 
        exit; 
      } 
    $sql = "SELECT * from quote WHERE q_mark = 0"; 
    $result = mysql_query($sql); 
    if (mysql_errno()>0) { 
      echo "<BR>\n<FONT COLOR=\"#990000\">".mysql_errno().": ".mysql_error()."<BR>\n"; 
      exit; 
    } 
    $max = mysql_num_rows($result)-1; 
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
    $myrow = mysql_fetch_array($result); 
  } 
  $id = $myrow[0]; 
  $quote = $myrow[1]; 
  // mark this selected quote as displayed (q_mark = 1). 
  $result = mysql_query("UPDATE quote SET q_mark = 1 WHERE q_id = '$id'"); 
  if (mysql_errno()>0) { 
    echo "<BR>\n<FONT COLOR=\"#990000\">".mysql_errno().": ".mysql_error()."<BR>\n"; 
    exit; 
  } 
  // convert to HTML special characters, you know, like ?, ?, ?, and so on. 
  $quote = nl2br(htmlentities($quote)); 
  // finally replace the "<" and ">" for < and > so you that can use tags. 
  $quote = ereg_replace ("<", "<", $quote); 
  $quote = ereg_replace (">", ">", $quote); 
  echo $quote."<BR>\n"; 
?>