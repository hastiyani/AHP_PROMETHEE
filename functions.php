<?php
error_reporting(~E_NOTICE & ~E_DEPRECATED);
session_start();
include 'config.php';
include'includes/ez_sql_core.php';
include'includes/ez_sql_mysqli.php';
$db = new ezSQL_mysqli($config['username'], $config['password'], $config['database_name'], $config['server']);
include'includes/general.php';

$mod = $_GET['m'];
$act = $_GET['act'];

$rows = $db->get_results("SELECT id_alternatif, kode_alternatif, nama_alternatif FROM tb_alternatif ORDER BY id_alternatif");
foreach($rows as $row){
    $ALTERNATIF[$row->id_alternatif]['kode'] = $row->kode_alternatif;
    $ALTERNATIF[$row->id_alternatif]['nama'] = $row->nama_alternatif;
}

$nRI = array (
	1=>0,
	2=>0,
	3=>0.58,
	4=>0.9,
	5=>1.12,
	6=>1.24,
	7=>1.32,
	8=>1.41,
	9=>1.46,
	10=>1.49,
    11=>1.51,
    12=>1.48,
    13=>1.56,
    14=>1.57,
    15=>1.59
);

$rows = $db->get_results("SELECT id_kriteria, kode_kriteria, nama_kriteria, minmax, bobot, tipe, par_q, par_p FROM tb_kriteria ORDER BY kode_kriteria");
foreach($rows as $row){
    $KRITERIA[$row->id_kriteria] = array(
        'kode'=>$row->kode_kriteria,
        'nama'=>$row->nama_kriteria,
        'minmax'=>$row->minmax,
        'tipe'=>$row->tipe,
        'bobot'=>$row->bobot,
        'par_p'=>$row->par_p,
        'par_q'=>$row->par_q,
    );
}

function get_komposisi(){
    global $ALTERNATIF;
    $arr = array();
    $keys = array_keys($ALTERNATIF);
    
    foreach($keys as $key){
        foreach($keys as $k){
            if($key!=$k)
                $arr[$key][$k] = 1;
        }
    }    
    
    $result = array();
    foreach($arr as $key => $val){
        foreach($val as $k => $v){
            $result[] = array($key, $k);
        }
    }
            
    return $result;
}

function get_normal( $data = array(), $komposisi = array()){
    $arr = array();
    
    global $KRITERIA;
    
    foreach($KRITERIA as $key => $val){
        foreach($komposisi as $k => $v){
            $arr[$key][] = array( $v[0], $v[1]);
        }
    }
    return $arr;
}

function get_selisih($data = array(), $normal = array()){
    $arr = array();
    
    foreach($normal as $key => $val){
        foreach($val as $k => $v){
            $arr[$key][$k] = $data[$v[0]][$key] - $data[$v[1]][$key];
        }
    }
    return $arr;
}

function get_preferensi($selisih = array()){
    global $KRITERIA;
    foreach($selisih as $key => $val){
        foreach($val as $k => $v){
            $arr[$key][$k] = hitung_pref($KRITERIA[$key]['tipe'], $KRITERIA[$key]['par_q'], $KRITERIA[$key]['par_p'], $KRITERIA[$key]['minmax'], $v);
        }
    }
    return $arr;
}

function get_index_pref($preferensi = array()){
    global $KRITERIA;
    $arr = array();
    foreach($preferensi as $key => $val){
        foreach($val as $k => $v){        
            $arr[$key][$k] = $v * $KRITERIA[$key]['bobot'];
        }
    }    
    return $arr;
}

function hitung_pref($tipe, $q, $p, $minmax, $jarak){
    $minmax = strtolower($minmax);
    if($minmax=='minimasi' && $jarak > 0)
        return 0;
    if($minmax=='maksimasi' && $jarak < 0)
        return 0;
    
    if($tipe==5){
        if(abs($jarak) <= $q)
            return 0;
        if(abs($jarak) > $q && abs($jarak) <= $p)
            return (abs($jarak) - $q) / ($p - $q);
        if($p < abs($jarak))
            return 1;
        return -1;
    } else if($tipe==4){
        if(abs($jarak) <= $q)
            return 0;
        if(abs($jarak) > $q && abs($jarak) <= $p)
            return 0.5;
        if($p < abs($jarak))
            return 1;
        return -1;
    } else if($tipe==3){
        if($jarak >= $p * -1 && $jarak<=$p)
            return $jarak / $p;
        if($jarak < $p * -1 || $jarak >= $p)
            return 0;
        return -1;
    } else if($tipe==2){
        if($jarak >= $q * -1 && $jarak<=$q)
            return 0;
        if($jarak < $q * -1 || $jarak >= $q)
            return 1;
        return -1;
    } else if($tipe==1){
        if($jarak == 0)
            return 0;
        elseif($jarak != 0)
            return 1;  
        
        return -1;                  
    } else {
        return -1;
    } 
}

function get_total_indeks_pref($index_pref = array()){
    $arr = array();
    foreach($index_pref as $key => $val){
        foreach($val as $k => $v){
            $arr[$k]+= $v;
        }
    }
    return $arr;
}

function get_matriks($komposisi = array(), $total_index_pref = array()){
    $arr = array();
    global $ALTERNATIF;
    foreach($ALTERNATIF as $key => $val){
        foreach($ALTERNATIF as $k => $v){
            $arr[$key][$k] = 0;
        }
    }
    
    foreach($komposisi as $key => $val){
        $arr[$val[0]][$val[1]] = $total_index_pref[$key];
    }
    return $arr;
}

function get_total_kolom($matriks = array()){
    $arr = array();
    foreach($matriks as $key => $val){
        foreach($val as $k => $v){
            $arr[$k]+=$v;
        }
    }
    return $arr; 
}

function get_total_baris($matriks = array()){
    $arr = array();
    foreach($matriks as $key => $val){
        foreach($val as $k => $v){
            $arr[$key]+=$v;
        }
    }
    return $arr;
}

function get_leaving($matriks = array(), $total_baris = array()){
    $arr = array();
    foreach($matriks as $key => $val){
        $arr[$key] = $total_baris[$key] / (count($val) - 1);
    }
    return $arr;
}

function get_entering($matriks = array(), $total_kolom = array()){
    $arr = array();
    foreach($matriks as $key => $val){
        $arr[$key] = $total_kolom[$key] / (count($val) - 1);
    }
    return $arr;
}


function get_net_flow($leaving = array(), $entering = array()){
    $arr = array();
    foreach($leaving as $key => $val){
        $arr[$key] = $val - $entering[$key];
    }
    return $arr;
}

function get_rank($array){
    $data = $array;
    arsort($data);
    $no=1;
    $new = array();
    foreach($data as $key => $value){
        $new[$key] = $no++;
    }
    return $new;
}

function get_data(){
    global $db;
    $rows = $db->get_results("SELECT a.id_alternatif, k.id_kriteria, ra.nilai
        FROM tb_alternatif a 
        	INNER JOIN tb_rel_alternatif ra ON ra.id_alternatif=a.id_alternatif
        	INNER JOIN tb_kriteria k ON k.id_kriteria=ra.id_kriteria
        ORDER BY a.id_alternatif, k.id_kriteria");
    $data = array();
    foreach($rows as $row){
        $data[$row->id_alternatif][$row->id_kriteria] = $row->nilai;
    }
    return $data;
}

function get_min_max_option($selected = ''){
    $atribut = array('Minimasi'=>'Minimasi', 'Maksimasi'=>'Maksimasi');   
    foreach($atribut as $key => $value){
        if($selected==$key)
            $a.="<option value='$key' selected>$value</option>";
        else
            $a.= "<option value='$key'>$value</option>";
    }
    return $a;
}

function get_kriteria_option($selected = 0){
    global $KRITERIA;  
    
    foreach($KRITERIA as $key => $value){
        if($key==$selected)
            $a.="<option value='$key' selected>[$value[kode]] $value[nama]</option>";
        else
            $a.="<option value='$key'>[$value[kode]] $value[nama]</option>";
    }
    return $a;
}

function AHP_get_nilai_option($selected = ''){
    $nilai = array(
        '1' => 'Sama penting dengan',
        '2' => 'Mendekati sedikit lebih penting dari',
        '3' => 'Sedikit lebih penting dari',
        '4' => 'Mendekati lebih penting dari',
        '5' => 'Lebih penting dari',
        '6' => 'Mendekati sangat penting dari',
        '7' => 'Sangat penting dari',
        '8' => 'Mendekati mutlak dari',
        '9' => 'Mutlak sangat penting dari',
    );
    foreach($nilai as $key => $value){
        if($selected==$key)
            $a.="<option value='$key' selected>$key - $value</option>";
        else
            $a.= "<option value='$key'>$key - $value</option>";
    }
    return $a;
}

function AHP_get_relkriteria(){
    global $db;
    $data = array();
    $rows = $db->get_results("SELECT k.nama_kriteria, rk.ID1, rk.ID2, nilai 
        FROM tb_rel_kriteria rk INNER JOIN tb_kriteria k ON k.id_kriteria=rk.ID1 
        ORDER BY ID1, ID2");
    foreach($rows as $row){    
        $data[$row->ID1][$row->ID2] = $row->nilai;
    }
    return $data;
}   

function AHP_get_total_kolom($matriks = array()){
    $total = array();        
    foreach($matriks as $key => $value){
        foreach($value as $k => $v){
            $total[$k]+=$v;
        }
    }  
    return $total;
} 

function AHP_normalize($matriks = array(), $total = array()){
          
    foreach($matriks as $key => $value){
        foreach($value as $k => $v){
            $matriks[$key][$k] = $matriks[$key][$k]/$total[$k];
        }
    }     
    return $matriks;       
}

function AHP_get_rata($normal){
    $rata = array();
    foreach($normal as $key => $value){
        $rata[$key] = array_sum($value)/count($value); 
    } 
    return $rata;   
}

function simpan_bobot_kriteria($rata = array()){
    global $db;
    foreach($rata as $key => $val){
        $db->query("UPDATE tb_kriteria SET bobot='$val' WHERE id_kriteria='$key'");
    }        
}

function AHP_mmult($matriks = array(), $rata = array()){
	$data = array();
    
    $rata = array_values($rata);
    
	foreach($matriks as $key => $value){
        $no=0;
		foreach($value as $k => $v){
			$data[$key]+=$v*$rata[$no];       
            $no++;  
		}				
	}  
    
	return $data;
}

function AHP_consistency_measure($matriks, $rata){
    $matriks = AHP_mmult($matriks, $rata);    
    foreach($matriks as $key => $value){
        $data[$key]=$value/$rata[$key];        
    }
    return $data;
}


function get_tipe_option($selected = ''){
    $atribut = array(
        '1'=>'Tipe 1',
        '2'=>'Tipe 2', 
        '3'=>'Tipe 3', 
        '4'=>'Tipe 4', 
        '5'=>'Tipe 5', 
    );   
    foreach($atribut as $key => $value){
        if($selected==$key)
            $a.="<option value='$key' selected>$value</option>";
        else
            $a.= "<option value='$key'>$value</option>";
    }
    return $a;
}