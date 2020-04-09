<?php
$rows = $db->get_results("SELECT a.id_alternatif, ra.id_kriteria, ra.nilai            
        FROM tb_rel_alternatif ra 
        	INNER JOIN tb_alternatif a ON a.id_alternatif = ra.id_alternatif
        WHERE nama_alternatif LIKE '%".esc_field($_GET['q'])."%'
        ORDER BY ra.id_alternatif, ra.id_kriteria");
$data = array();   
     
foreach($rows as $row){
    $data[$row->id_alternatif][$row->id_kriteria]  = $row->nilai;    
}
?>
<div class="page-header">
    <h1>Nilai Bobot Alternatif</h1>
</div>
<div class="panel panel-default">
<div class="panel-heading">
<form class="form-inline">
    <input type="hidden" name="m" value="rel_alternatif" />
    <div class="form-group">
        <input class="form-control" type="text" name="q" value="<?=$_GET['q']?>" />
    </div>
    <div class="form-group">
        <button class="btn btn-success"><span class="glyphicon glyphicon-refresh"></span> Refresh</a>
    </div>
</form>
</div>
<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama Alternatif</th>
            <?php foreach($KRITERIA as $key => $val):?>
            <th><?=$val['nama']?></th>
            <?php endforeach?>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($data as $key => $val):?>
    <tr>
        <td><?=$ALTERNATIF[$key]['kode']?></td>
        <td><?=$ALTERNATIF[$key]['nama'];?></td>
        <?php foreach($val as $k => $v):?>
        <td><?=$v?></td>               
        <?php endforeach?>
        <td>
            <a class="btn btn-xs btn-warning" href="?m=rel_alternatif_ubah&ID=<?=$key?>"><span class="glyphicon glyphicon-edit"></span> Ubah</a>        
        </td>
    </tr>
    <?php endforeach;
    ?>
    </tbody>
    </table>
</div>
</div>