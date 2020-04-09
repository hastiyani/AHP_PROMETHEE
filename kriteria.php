<div class="page-header">
    <h1>Kriteria</h1>
</div>
<div class="panel panel-default">
    <div class="panel-heading">        
        <form class="form-inline">
            <input type="hidden" name="m" value="kriteria" />
            <div class="form-group">
                <input class="form-control" type="text" placeholder="Pencarian. . ." name="q" value="<?=$_GET['q']?>" />
            </div>
            <div class="form-group">
                <button class="btn btn-success"><span class="glyphicon glyphicon-refresh"></span> Refresh</a>
            </div>
            <div class="form-group">
                <a class="btn btn-primary" href="?m=kriteria_tambah"><span class="glyphicon glyphicon-plus"></span> Tambah</a>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Kriteria</th>
                <th>Bobot</th>
                <th>Min Max</th>
                <th>Tipe Preferensi</th>
                <th>Q</th>
                <th>P</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <?php
        $q = esc_field($_GET['q']);
        $rows = $db->get_results("SELECT * FROM tb_kriteria WHERE nama_kriteria LIKE '%$q%' ORDER BY id_kriteria");
        $no=0;
        foreach($rows as $row):?>
        <tr>
            <td><?=$row->kode_kriteria ?></td>
            <td><?=$row->nama_kriteria?></td>
            <td><?=round($row->bobot, 4)?></td>
            <td><?=$row->minmax?></td>
            <td><?=$row->tipe?></td>
            <td><?=$row->par_q?></td>
            <td><?=$row->par_p?></td>
            <td>
                <a class="btn btn-xs btn-warning" href="?m=kriteria_ubah&ID=<?=$row->id_kriteria?>"><span class="glyphicon glyphicon-edit"></span></a>
                <a class="btn btn-xs btn-danger" href="aksi.php?act=kriteria_hapus&ID=<?=$row->id_kriteria?>" onclick="return confirm('Hapus data?')"><span class="glyphicon glyphicon-trash"></span></a>
            </td>
        </tr>
        <?php endforeach;?>
        </table>
    </div>
</div>