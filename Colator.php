<?php 
class Colator{
	
	private $connect;
	
	public function __construct($localhost, $username, $password, $dbname){
		$this -> connect = mysqli_connect($localhost, $username, $password, $dbname);
	}
	public function query($Type, $TableName, $ForWhile, $Where){
		$SQL_Connect = $this -> connect;
		mysqli_set_charset($SQL_Connect, "utf8");
		//Where clause
		if($Where !== false)
		{
			$SQL_Where = " WHERE ".$Where;
		}
		
		//Action clause
		$SQL_FROM = " FROM ";
		$SQL_Select = " SELECT * ".$SQL_FROM.$TableName.$SQL_Where;
		$SQL_SelectCnt = " SELECT count(*) AS ".$Type.$SQL_FROM.$TableName.$SQL_Where;
		$SQL_SelectSum = " SELECT SUM(".$Type.") AS Sum".$SQL_FROM.$TableName.$SQL_Where;
		$SQL_Delete = " DELETE ".$SQL_FROM.$TableName.$SQL_Where;
		$SQL_Update = " UPDATE ".$TableName." SET ".$ForWhile.$SQL_Where;
		$SQL_Insert = " INSERT INTO ".$TableName."(".$ForWhile.") VALUES (".$Where.")";
		
		if($Type === "S")
		{
			$SQL_Code = $SQL_Select;
		}
		elseif($Type === "D")
		{
			$SQL_Code = $SQL_Delete;
		}
		elseif($Type === "U")
		{
			$SQL_Code = $SQL_Update;
		}
		elseif($Type === "I")
		{
			$SQL_Code = $SQL_Insert;
		}
		elseif($Type === "CNT")
		{
			$SQL_Code = $SQL_SelectCnt;
		}
		else
		{
			$SQL_Code = $SQL_SelectSum;
		}
		
		//Back result
		$SQL_Query = mysqli_query($SQL_Connect, $SQL_Code);
		
		if($ForWhile === true)
		{
			return $SQL_Query;
		}
		elseif($ForWhile === false)
		{ 
			$SQL_Result = mysqli_fetch_assoc($SQL_Query) or die(mysqli_error($SQL_Connect));
			return $SQL_Result;
		}
	}
	
	public function protectValue($Type, $Value) {
		if($Type == "Only")
		{
			$Method == $Value;
		}
		else
		{
			if($Type === "GET")
			{
				$Method = $_GET[$Value];
			}
			elseif($Type === "POST")
			{
				$Method = $_POST[$Value];
			}
		}
		$SQL_Connect = $this -> connect;
		$Trim = trim($Method);
		$Chars = htmlspecialchars($Trim);
		$RealString = mysqli_real_escape_string($SQL_Connect, $Chars);
		return $RealString;
	}

	public function Whiling($TableName, $Where, $FileData) {
		$SQL_Connect = $this -> connect;
		$WhilingQuery = $this->query('S', $TableName, true, $Where) or die(mysqli_error($SQL_Connect));
		while ($massive = mysqli_fetch_assoc($WhilingQuery)) {
			include $FileData.'.php';
		}
	}
}