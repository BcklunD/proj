<?php
function connect()
{
    if (!isProd())
        require_once(PREFIX . ".config.php");

    $host = getenv("DB_HOST");
    $dbuser = getenv("DB_USER");
    $password = getenv("DB_PASSWORD");
    $database = getenv("DB_NAME");
	$conn = mysqli_connect($host, $dbuser, $password, $database);	
	if ($conn == null) {
		if (mysqli_connect_errno()) {
			echo mysqli_connect_error();
			die;
		}
	}
	$_REQUEST['mysqli_connection'] = $conn;
}

function check_db_error($conn, $sql)
{
	$err = mysqli_error($conn);
	if (strlen($err) > 0) {
		if (defined('REST')) {
			$mess = $err;
		} else {
			$mess = "SQL error: " . $err . "\n";
			$mess .= "SQL errno: " . mysqli_errno($conn) . "\n";
			$mess .= "<br/>";
			$mess .= "SQL: " . $sql . "\n";
		}
		rollback();
		trigger_error($mess, E_USER_ERROR);
	}    
}

function fetch_row($query)
{
	return mysqli_fetch_row($query);
}

function fetch_assoc($query)
{
	return mysqli_fetch_assoc($query);
}

function fetch_array($query)
{
	return mysqli_fetch_array($query, MYSQLI_NUM);
}

function fetch_object($query)
{
	return mysqli_fetch_object($query);
}

/**
 *  Returnerar antalet rader i ett resultatset
 */
function num_rows($rs)
{
    if ($rs === false || $rs === null) {
        return 0;
    }
	return mysqli_num_rows($rs);
}

/**
 * Returnerar antalet uppdaterade/tillagda/borttagna rader för senaste update/insert/delete
 */
function affected_rows()
{
	$conn = $_REQUEST['mysqli_connection'];
	return mysqli_affected_rows($conn);
}

/**
 * Returerar auto genererat (auto_increment) nyckel som skapats vid föregående insert.
 */
function insert_id()
{
    return mysqli_insert_id($_REQUEST['mysqli_connection']);
}

/**
 *  Kör en SQL select och returnerar ett resulatset
 */
function query($sql, $types = "", ...$params)
{
	$conn = $_REQUEST['mysqli_connection'];
	//logger($sql, "sql");
	if (strlen($types) == 0)
		$rs = mysqli_query($conn, $sql);
	else {
		$stmt = mysqli_prepare($conn, $sql);
		mysqli_stmt_bind_param($stmt, $types, ...$params);
		mysqli_stmt_execute($stmt);
		$str = "";
		foreach ($params as $p) {
			if ($str != "")
				$str .= ",";
			$str .= $p;
		}
		//logger($str, "sql");
		$rs = mysqli_stmt_get_result($stmt);
	}
	check_db_error($conn, $sql);	
    return $rs;
}

/**
 * Kör en SQL som inte returerar ett resultatset, t.ex. insert/update
 */
function sql($sql, $types = "", ...$params)
{
    return query($sql, $types, ...$params);
}

function insert($sql, $types = "", ...$params)
{
	sql($sql, $types, ...$params);
	return insert_id();
}

function update($sql, $types = "", ...$params)
{
	sql($sql, $types, ...$params);
	return affected_rows();
}

/**
 * Hämtar nästa post i angivet resultatset
 */
function fetch($rs)
{
	if ($rs == null)
		return false;
    return fetch_object($rs);
}

function begin()
{
	sql("set autocommit=0");
	sql("begin");
}

function commit()
{
	sql("commit");
	sql("set autocommit=1");
}

function rollback()
{
	sql("rollback");
	sql("set autocommit=1");
}

/**
 * Kör en select och returerar första kolumnen  första raden. Används då man bara 
 * frågar efter ett enda värde.
 */
function findValue($sql, $default = null, $types = "", ...$params)
{
    $rs = query($sql, $types, ...$params);
    $row = fetch_array($rs);
    if ($row == null)
    	return $default;
    if ($row[0] == null && !is_numeric($row[0])	)
    	return $default;
    return $row[0];
}

/**
 * Gör om ett resultatset till en array av arrayer. Varje rad/post i resultatsetet
 * blir en array (i arrayen).
 */
function rs2array($rs, $single = false)
{
    $result = array();
    while ($row = fetch_array($rs)) {
    	if ($single)
    		$result[] = $row[0];
    	else
        	$result[] = $row;
    }
    return $result;
}

/**
 * Gör om ett resultatset med endast en kolumn till en array. 
 */
function rs2array1($rs)
{
    $result = array();
    while ($row = fetch_row($rs)) {
        $result[] = $row[0];
    }
    return $result;
}

function rs2maparray($rs)
{
    $result = array();
    while ($row = fetch_assoc($rs)) {
        $result[] = $row;
    }
    return $result;
}
/**
 * Gör om ett resultatset till en associativ array där första kolumnen blir till nyckeln
 */
function rs2map($rs)
{
    $result = array();
    while ($row = fetch_assoc($rs)) {
		$result[$row[array_key_first($row)]] = (object)array_slice($row, 1);
    }
    return $result;
}

function rs2objarray($rs)
{
    $result = array();
    while ($row = fetch($rs)) {
        $result[] = $row;
    }
    return $result;
}

function rs2xml($rs)
{
	$xml = "<rs>\n";
	$first = true;
	while ($row = fetch($rs)) {
		$row = get_object_vars($row);
        $xml .= "\t<row ";
		foreach ($row as $key => $value) {
			$xml .= $key."='".$value."' ";
		}
		$xml .= " />\n";
	}
	$xml.= "\n</rs>";
	return $xml;
}

function rs2json($rs, $utf8 = false)
{
	$json = "[";
	$first = true;
	while ($row = fetch($rs)) {
		if (!$first)
			$json .= ",";
		$json .= "\n".row2json($row);
		$first = false;
	}
	$json .= "\n]";
	if (defined('REST') || $utf8)
		$json = utf8_encode($json);
	return $json;
}

function row2json($row)
{
	$json = "{";
    $row = get_object_vars($row);
    $firstCol = true;
    foreach ($row as $key => $value) {
        if (!$firstCol)	
			$json .= ", ";
		$value = str_replace("\"", "'", $value);
        $json .= "\"$key\" : \"$value\"";
        $firstCol = false;
    }
    $json .= "}";
	return $json;
}

function field_name($rs, $i)
{
    $fields = mysqli_fetch_fields($rs);
    $field = $fields[$i];
    return $field->name;
}

function num_fields($rs)
{
    return mysqli_num_fields($rs);
}

function real_escape_string($param)
{
    return mysqli_real_escape_string($_REQUEST['mysqli_connection'], $param);
}

function tx($functionname, $params)
{
    begin();
    if (!is_array($params))
        $params = array();
    $ret = call_user_func_array($functionname, $params);
    commit();
    return $ret;
}