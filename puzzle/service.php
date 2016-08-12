<?php
	define('DB_HOST', 'localhost');  
	define('DB_USER', 'puzzle');  
	define('DB_PASS', 'puzzle');  
	define('DB_DATABASENAME', 'linech_puzzle');  
	define('DB_TABLENAME', 'ranking');

	$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('connect failed' . mysql_error()); 

	$lines=file('SQL/puzzle.sql');
	$sqlstr='';
	foreach($lines as $line){
	  $line=trim($line);
	  if($line!=''){
	    if(!($line{0}=='#' || $line{0}.$line{1}=='--')){
	      $sqlstr.=$line;  
	    }
	  }
	}
	$sqlstr=rtrim($sqlstr,';');
	$sqls=explode(';',$sqlstr);

	foreach ($sqls as $sql) {
		mysql_query($sql,$conn) or die('exec sql file failed'.mysql_error()); 
	}

	function format($v){
		if($v < 10){ 
			$v = '0'.$v; 
		}
		return $v;
	}

	$level = $_POST['level'];
	$name = $_POST['name'];
	$time = format( floor($_POST['time']/1000/60)).':'.format($_POST['time']/1000);
	$levels = array('2' => 'EASY', '3' => 'MEDIUM', '4' => 'HARD');

	$insertSql = 'INSERT INTO ranking (name,difficult,time) VALUES("'.$name.'",'.$level.',"00:'.$time.'")';
	mysql_query($insertSql,$conn) or die('insert failed'.mysql_error());

	$selectSql = 'select * from ranking r where (select count(*) from ranking where difficult = '.$level.' and time < r.time) < 3 order by r.difficult desc,r.time asc';
	$query = mysql_query($selectSql);
	$num = 0;
	$hasMe = false;
	while($row = mysql_fetch_array($query)){
		$num += 1;
		$row_level = $row['difficult'];
		$row_name = $row['name'];
		$row_time = substr($row['time'],3);
		$isMe = ($row_level==$level && $row_name==$name && $row_time==$time);
		$hasMe = ($hasMe)? $hasMe : $isMe;
	 	echo ($isMe)? '<tr class="me">':'<tr>';
	 	echo '<td>'.$num.'</td>';
	 	echo '<td>'.$levels[$row_level].'</td>';
	 	echo '<td>'.$row_name.'</td>';
	 	echo '<td>'.$row_time.'</td>';
	 	echo '</tr>';
	}
	if(!$hasMe){
		$selectSql = 'select (select count(1)+1 from ranking where r.difficult = difficult and time < r.time) as rownum from ranking r where difficult = '.$level.' and name = "'.$name.'" and time = "00:'.$time.'" order by r.difficult desc,r.time asc';
		$query = mysql_query($selectSql);
		$result = mysql_fetch_assoc($query);
		echo '<tr class="me">';
	 	echo '<td>'.$result['rownum'].'</td>';
	 	echo '<td>'.$levels[$level].'</td>';
	 	echo '<td>'.$name.'</td>';
	 	echo '<td>'.$time.'</td>';
	 	echo '</tr>';
	}
	mysql_close($conn);
?>