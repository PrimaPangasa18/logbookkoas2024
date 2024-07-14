<?php

class ExportToExcel
{

	function exportWithPage($rsql,$excel_file_name)
	{
		$this->setHeader($excel_file_name);
		//echo $excel_file_name;
		echo "$rsql";

	}
	function setHeader($excel_file_name)//this function used to set the header variable
	{
		header('Content-Type: application/xls');
		//header("Content-type: application/octet-stream");//A MIME attachment with the content type "application/octet-stream" is a binary file.
		//Typically, it will be an application or a document that must be opened in an application, such as a spreadsheet or word processor.
		//$datenow=date("Ymd");
		header("Content-Disposition: attachment; filename=".$excel_file_name.".xls");//with this extension of file name you tell what kind of file it is.
		header("Pragma: no-cache");//Prevent Caching
		header("Expires: 0");//Expires and 0 mean that the browser will not cache the page on your hard drive



	}
	function exportWithQuery($qry,$excel_file_name,$conn)//to export with query
	{
		$tmprst=mysqli_query($qry,$conn);
		$header="<center><table border=1px><th>Personal Details</th>";
		$num_field=mysqli_num_fields($tmprst);
		while($row=mysqli_fetch_array($tmprst,MYSQL_BOTH))
		{
			$body.="<tr>";
			for($i=0;$i<$num_field;$i++)
			{
				$body.="<td>".$row[$i]."</td>";
			}
			$body.="</tr>";
		}

		$this->setHeader($excel_file_name);
		echo $header.$body."</table";
	}


}
?>
