<div class="page-header">
    <h1>Perhitungan Bobot Kriteria</h1>
</div>
<?php
if($_POST) include'aksi.php';

$rows = $db->get_results("SELECT k1.nama_kriteria, k1.kode_kriteria AS ID1, k2.kode_kriteria as ID2, nilai 
    FROM tb_rel_kriteria rk 
        INNER JOIN tb_kriteria k1 ON k1.id_kriteria=rk.ID1
        INNER JOIN tb_kriteria k2 ON k2.id_kriteria=rk.ID2 
    ORDER BY k1.id_kriteria, k2.id_kriteria");
$criterias = array();
$data = array();
foreach($rows as $row){    
    $criterias[$row->ID1] = $row->nama_kriteria;
    $data[$row->ID1][$row->ID2] = $row->nilai;
}
?>
<div class="panel panel-default">
<div class="panel-heading">
<form class="form-inline" action="?m=rel_kriteria" method="post">
    <div class="form-group">
        <select class="form-control" name="ID1">
        <?=get_kriteria_option($_POST['ID1'])?>
        </select>
    </div>
    <div class="form-group">
        <select class="form-control" name="nilai">
        <?=AHP_get_nilai_option($_POST['nilai'])?>
        </select>
    </div>
    <div class="form-group">
        <select class="form-control" name="ID2">
        <?=get_kriteria_option($_POST['ID2'])?>
        </select>
    </div>
    <div class="form-group">
        <button class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> Ubah</a>
    </div>
</form>
</div>

<table class="table table-bordered table-hover table-striped">
<thead>
    <tr>
        <th class="text-center">Kode</th>
        <?php 
        foreach($data as $key=>$value){
            echo "<th>$key</th>";
        }         
        ?>
    </tr>
</thead>
<tbody>
<?php

$no=1;

$a=1;
foreach($data as $key => $value):?>
<tr>
    <th class="nw"><?=$key?></th>
    <?php  
        $b=1;
        foreach($value as $k => $dt){ 
            if( $key == $k ) 
                $class = 'success';
            elseif($b > $a)
                $class = 'danger';
            else
                $class = '';
                
            echo "<td class='$class'>".round($dt, 3)."</td>";   
            $b++;            
        } 
        $no++;       
    ?>
</tr>
<?php $a++; endforeach;

$matriks = AHP_get_relkriteria();   
$total = AHP_get_total_kolom($matriks);
$normal = AHP_normalize($matriks, $total);                  
$rata = AHP_get_rata($normal);
simpan_bobot_kriteria($rata);
$cm = AHP_consistency_measure($matriks, $rata);
?>
</tbody>
</table>
<div class="panel-body">
    <div class="panel panel-default">
            <div class="panel-heading"><strong>Normalisasi Matriks Perbandingan Berpasangan Kriteria Dengan Nilai Vektor Eigen</strong></div>
            <div class="table-responsive">        
                <table class="table table-bordered table-striped table-hover">
                    <thead><tr>
                        <th class="text-center">Kode</th>
                        <?php foreach($normal as $key => $value):?>
                        <th><?=$KRITERIA[$key]['kode']?></th>
                        <?php endforeach?>                    
                        <th class="nw">Bobot Prioritas</th>     
                        <th class="nw">Consistency Measure</th>
                    </tr></thead>
                    <?php foreach($normal as $key => $value):?>
                    <tr>
                        <th><?=$KRITERIA[$key]['kode']?></th>
                        <?php foreach($value as $k => $v):?>
                        <td><?=round($v,3)?></td>
                        <?php endforeach?>
                        <td class="text-primary"><?=round($rata[$key],3)?></td>
                        <td class="text-primary"><?=round($cm[$key],3)?></td>
                    </tr>
                    <?php endforeach;?>                    
                </table>  
            </div>        
        </div>        
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Matriks Konsistensi Kriteria</strong></div>
            <div class="table-responsive">                             
                <div class="panel-body">Berikut tabel ratio index berdasarkan ordo matriks.</p>                
                    <table class="table table-bordered">
                        <tr>
                        <th>Ordo matriks</th>
                        <?php
                            foreach($nRI as $key => $value){
                                if(count($matriks)==$key)
                                    echo "<td class='text-primary'>$key</td>";
                                else
                                    echo "<td>$key</td>";
                            }
                        ?>
                        </tr>
                        <tr>
                        <th>Ratio index</th>
                        <?php
                            foreach($nRI as $key => $value){
                                if(count($matriks)==$key)
                                    echo "<td class='text-primary'>$value</td>";
                                else
                                    echo "<td>$value</td>";
                            }
                        ?>
                        </tr>
                    </table>
                </div>
                <div class="panel-footer">
                <?php
                    $CI = ((array_sum($cm)/count($cm))-count($cm))/(count($cm)-1);	
                	$RI = $nRI[count($matriks)];
                	$CR = $CI/$RI;
                	echo "<p>Consistency Index: ".round($CI, 3)."<br />";	
                	echo "Ratio Index: ".round($RI, 3)."<br />";
                	echo "Consistency Ratio: ".round($CR, 3);
                	if($CR>0.10){
                		echo " (Tidak konsisten)<br />";	
                	} else {
                		echo " (Konsisten)<br />";
                	}
                ?>
                </div>
            </div>
        </div>
</div>
</div>