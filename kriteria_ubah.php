<?php
    $row = $db->get_row("SELECT * FROM tb_kriteria WHERE id_kriteria='$_GET[ID]'"); 
?>
<div class="page-header">
    <h1>Ubah Kriteria</h1>
</div>
<div class="row">
    <div class="col-sm-6">
        <?php if($_POST) include'aksi.php'?>
        <form method="post" action="?m=kriteria_ubah&ID=<?=$row->id_kriteria?>">
            <div class="form-group">
                <label>Kode Kriteria <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="kode_kriteria" value="<?=$row->kode_kriteria?>"/>
            </div>
            <div class="form-group">
                <label>Nama Kriteria <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="nama_kriteria" value="<?=$row->nama_kriteria?>"/>
            </div>
            <div class="form-group">
                <label>Min Max <span class="text-danger">*</span></label>
                <select class="form-control" name="minmax">
                    <option></option>
                    <?=get_min_max_option($row->minmax)?>
                </select>
            </div>
            <div class="form-group">
                <label>Tipe Preferensi <span class="text-danger">*</span></label>
                <select class="form-control" name="tipe">
                    <option></option>
                    <?=get_tipe_option(set_value('tipe', $row->tipe))?>
                </select>
            </div>
            <div class="form-group">
                <label>Parameter q <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="par_q" value="<?=$row->par_q?>"/>
            </div>
            <div class="form-group">
                <label>Parameter p <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="par_p" value="<?=$row->par_p?>"/>
            </div>
            <div class="form-group">
                <button class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Simpan</button>
                <a class="btn btn-danger" href="?m=kriteria"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
            </div>
        </form>
    </div>
</div>