<?php

    include "connect.php";
    error_reporting(E_ERROR | E_PARSE);
	$inputJSON = file_get_contents('php://input');
	$input = json_decode($inputJSON,TRUE);
    $username=$input['username'];
    $stase=$input['id'];
	$query_stase = "SELECT * FROM rotasi_internal WHERE stase like '$stase'";
	$data_stase = mysqli_query($conn,$query_stase);
	$jum=mysqli_num_rows($data_stase);
	$array_tmp = array();
    $array_total = array();
    $array1 = array();
    $array2 = array();
    $array3 = array();
    $array4 = array();
    $array5 = array();
    $array6 = array();
    $array7 = array();
    $array8 = array();
    $array9 = array();
    $array10 = array();
    $array11 = array();
    $array12 = array();
    $gelar1 = array();
    $gelar2 = array();
    $gelar3 = array();
    $gelar4 = array();
    $gelar5 = array();
    $gelar6 = array();
    
		while ($data1=mysqli_fetch_assoc($data_stase))
		{
            $array_tmp[] = $data1;
            $data2 = $data1['stase'];
            $data3 = "internal_".$data2;
	        $mulai = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `$data3` WHERE `nim`='$username'"));
			if($mulai['id']==null){
				$array_total[]=(object)["id"=>"null",
                    "nim"=>"null",                    
                    "rotasi1"=>"null",                    
                    "tgl1"=>"null",                    
                    "dosen1"=>"null",                    
                    "status1"=>"null",
                    "rotasi2"=>"null",                    
                    "tgl2"=>"null",                    
                    "dosen2"=>"null",                    
                    "status2"=>"null",
                    "rotasi3"=>"null",                    
                    "tgl3"=>"null",                    
                    "dosen3"=>"null",                    
                    "status3"=>"null",
                    "rotasi4"=>"null",                    
                    "tgl4"=>"null",                    
                    "dosen4"=>"null",                    
                    "status4"=>"null",  
                    "rotasi5"=>"null",                    
                    "tgl5"=>"null",                    
                    "dosen5"=>"null",                    
                    "status5"=>"null",
                    "rotasi6"=>"null",                    
                    "tgl6"=>"null",                    
                    "dosen6"=>"null",                    
                    "status6"=>"null",                      
                    ];
			}
			else{
			$array_total[] =$mulai;
			}
    
            $selesai = mysqli_fetch_assoc(mysqli_query($conn,"SELECT rotasi1,tgl1,dosen1,status1 FROM `$data3` WHERE `nim`='$username'"));
			if($selesai['tgl1']==null){
				$array1[]=(object)["rotasi1"=>"null",                    
                    "tgl1"=>"2000-01-01",                    
                    "dosen1"=>"null",                    
                    "status1"=>"null"];
			}
			else{
			$array1[] =$selesai;
            }
            $rotasi = mysqli_fetch_assoc(mysqli_query($conn,"SELECT rotasi2,tgl2,dosen2,status2 FROM `$data3` WHERE `nim`='$username'"));
			if($rotasi['tgl2']==null){
				$array2[]=(object)["rotasi2"=>"null",                    
                    "tgl2"=>"2000-01-01",                    
                    "dosen2"=>"null",                    
                    "status2"=>"null"];
			}
			else{
			$array2[] =$rotasi;
            }
             $rotasi3 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT rotasi3,tgl3,dosen3,status3 FROM `$data3` WHERE `nim`='$username'"));
            if($rotasi3['tgl3']==null){
				$array3[]=(object)["rotasi3"=>"null",                    
                    "tgl3"=>"2000-01-01",                    
                    "dosen3"=>"null",                    
                    "status3"=>"null"];
			}
			else{
			$array3[] =$rotasi3;
            }
             $rotasi4 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT rotasi4,tgl4,dosen4,status4 FROM `$data3` WHERE `nim`='$username'"));
            if($rotasi4['tgl4']==null){
				$array4[]=(object)["rotasi4"=>"null",                    
                    "tgl4"=>"2000-01-01",                    
                    "dosen4"=>"null",                    
                    "status4"=>"null"];
			}
			else{
			$array4[] =$rotasi4;
            }
             $rotasi5 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT rotasi5,tgl5,dosen5,status5 FROM `$data3` WHERE `nim`='$username'"));
            if($rotasi5['tgl5']==null){
				$array5[]=(object)["rotasi5"=>"null",                    
                    "tgl5"=>"2000-01-01",                    
                    "dosen5"=>"null",                    
                    "status5"=>"null"];
			}
			else{
			$array5[] =$rotasi5;
            }
             $rotasi6 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT rotasi6,tgl6,dosen6,status6 FROM `$data3` WHERE `nim`='$username'"));
            if($rotasi6['tgl6']==null){
				$array6[]=(object)["rotasi6"=>"null",                    
                    "tgl6"=>"2000-01-01",                  
                    "dosen6"=>"null",                    
                    "status6"=>"null"];
			}
			else{
			$array6[] =$rotasi6;
            }
            $rotasi7 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `$data3` WHERE `nim`='$username'"));
            
            $qry1="SELECT nama,username FROM admin WHERE username like '$rotasi7[dosen1]%'";
            $hbng1=mysqli_fetch_assoc(mysqli_query($conn,$qry1));
            $qry2="SELECT nama,username FROM admin Where username like '$rotasi7[dosen2]%'";
            $hbng2=mysqli_fetch_assoc(mysqli_query($conn,$qry2));
            $qry3="SELECT nama,username FROM admin Where username like '$rotasi7[dosen3]%'";
            $hbng3=mysqli_fetch_assoc(mysqli_query($conn,$qry3));
            $qry4="SELECT nama,username FROM admin Where username like '$rotasi7[dosen4]%'";
            $hbng4=mysqli_fetch_assoc(mysqli_query($conn,$qry4));
            $qry5="SELECT nama,username FROM admin Where username like '$rotasi7[dosen5]%'";
            $hbng5=mysqli_fetch_assoc(mysqli_query($conn,$qry5));
            $qry6="SELECT nama,username FROM admin Where username like '$rotasi7[dosen6]%'";
            $hbng6=mysqli_fetch_assoc(mysqli_query($conn,$qry6));
            if($rotasi7['dosen1']==null){
				$array7[]=(object)["nama"=>"null","username"=>"null"];
			}
			else{
                $array7[] =$hbng1;
            }
            $que1="SELECT gelar FROM dosen WHERE nip ='$hbng1[username]'";
                $gel1=mysqli_fetch_assoc(mysqli_query($conn,$que1));
                if($gel1['gelar']==null || $gel1['gelar']=="Residen"){
                    $gelar1[]=(object)["gelar"=>"null"];
                }
                else{
                    $gelar1[] =$gel1;
                }

                if($rotasi7['dosen2']==null){
				$array8[]=(object)["nama"=>"null","username"=>"null"];
			}
			else{
                $array8[] =$hbng2;
            } 
            $que2="SELECT gelar FROM dosen WHERE nip ='$hbng2[username]'";
            $gel2=mysqli_fetch_assoc(mysqli_query($conn,$que2));
            if($gel2['gelar']==null || $gel2['gelar']=="Residen"){
                $gelar2[]=(object)["gelar"=>"null"];
            }
            else{
                $gelar2[] =$gel2;
            }
                if($rotasi7['dosen3']==null){
				$array9[]=(object)["nama"=>"null","username"=>"null"];
			}
			else{
                $array9[] =$hbng3;
            } 
            $que3="SELECT gelar FROM dosen WHERE nip ='$hbng3[username]'";
                $gel3=mysqli_fetch_assoc(mysqli_query($conn,$que3));
                if($gel3['gelar']==null || $gel3['gelar']=="Residen"){
                    $gelar3[]=(object)["gelar"=>"null"];
                }
                else{
                    $gelar3[] =$gel3;
                }
                if($rotasi7['dosen4']==null){
				$array10[]=(object)["nama"=>"null","username"=>"null"];
			}
			else{
                $array10[] =$hbng4;
            } 
            $que4="SELECT gelar FROM dosen WHERE nip ='$hbng4[username]'";
                $gel4=mysqli_fetch_assoc(mysqli_query($conn,$que4));
                if($gel4['gelar']==null || $gel4['gelar']=="Residen"){
                    $gelar4[]=(object)["gelar"=>"null"];
                }
                else{
                    $gelar4[] =$gel4;
                }
                if($rotasi7['dosen5']==null){
				$array11[]=(object)["nama"=>"null","username"=>"null"];
            }
            
			else{
                $array11[] =$hbng5;
                
            } 
            $que5="SELECT gelar FROM dosen WHERE nip ='$hbng5[username]'";
                $gel5=mysqli_fetch_assoc(mysqli_query($conn,$que5));
                if($gel5['gelar']==null || $gel5['gelar']=="Residen"){
                    $gelar5[]=(object)["gelar"=>"null"];
                }
                else{
                    $gelar5[] =$gel5;
                }
                if($rotasi7['dosen6']==null){
				$array12[]=(object)["nama"=>"null","username"=>"null"];
			}
			else{
                $array12[] =$hbng6;
                
            } 
            $que6="SELECT gelar FROM dosen WHERE nip ='$hbng6[username]'";
                $gel6=mysqli_fetch_assoc(mysqli_query($conn,$que6));
                if($gel6['gelar']==null || $gel6['gelar']=="Residen"){
                    $gelar6[]=(object)["gelar"=>"null"];
                }
                else{
                    $gelar6[] =$gel6;
                }
            
            
        }
		$array["tmp"] = $array_tmp;
		$array["total"] = $array_total;
        $array["array1"] = $array1;
        $array["array2"] = $array2;
        $array["array3"] = $array3;
        $array["array4"] = $array4;
        $array["array5"] = $array5;
        $array["array6"] = $array6;
        $array["array7"] = $array7;
        $array["array8"] = $array8;
        $array["array9"] = $array9;
        $array["array10"] = $array10;
        $array["array11"] = $array11;
        $array["array12"] = $array12;
        $array["gelar1"] = $gelar1;
        $array["gelar2"] = $gelar2;
        $array["gelar3"] = $gelar3;
        $array["gelar4"] = $gelar4;
        $array["gelar5"] = $gelar5;
        $array["gelar6"] = $gelar6;

		header('Content-type:application/json');
		echo json_encode($array);
		mysqli_close($conn);
?>
