<?php
    require_once 'functions.php';

    /** LOGIN */ 
    if ($act=='login'){
        $user = esc_field($_POST['user']);
        $pass = esc_field($_POST['pass']);
        
        $row = $db->get_row("SELECT * FROM tb_user WHERE user='$user' AND pass='$pass'");
        if($row){
            $_SESSION['login'] = $row->user;
            redirect_js("index.php");
        } else{
            print_msg("Maaf kombinasi username dan password yang anda masukan salah!!!.");
        }          
    } elseif($act=='logout'){
        unset($_SESSION['login']);
        header("location:login.php");
    } else if ($mod=='password'){
        $pass1 = $_POST['pass1'];
        $pass2 = $_POST['pass2'];
        $pass3 = $_POST['pass3'];
        
        $row = $db->get_row("SELECT * FROM tb_user WHERE user='$_SESSION[login]' AND pass='$pass1'");        
        
        if($pass1=='' || $pass2=='' || $pass3=='')
            print_msg('Field bertanda * harus diisi.');
        elseif(!$row)
            print_msg('Password lama salah.');
        elseif( $pass2 != $pass3 )
            print_msg('Maaf kombinasi username dan password yang anda masukan salah!!!.');
        else{        
            $db->query("UPDATE tb_user SET pass='$pass2' WHERE user='$_SESSION[login]'");                    
            print_msg('Password berhasil diubah.', 'success');
        }
    } 
    
    /** ALTERNATIF */
    elseif($mod=='alternatif_tambah'){
        $kode_alternatif = $_POST['kode_alternatif'];
        $nama_alternatif = $_POST['nama_alternatif'];
        $keterangan = $_POST['keterangan'];
        if($kode_alternatif=='' || $nama_alternatif=='')
            print_msg("Field yang bertanda * tidak boleh kosong!");
        elseif($db->get_results("SELECT * FROM tb_alternatif WHERE kode_alternatif='$kode_alternatif'"))
            print_msg("Kode sudah ada!");
        else{
            $db->query("INSERT INTO tb_alternatif (kode_alternatif, nama_alternatif, keterangan) VALUES ('$kode_alternatif', '$nama_alternatif', '$keterangan')");
            $ID = $db->insert_id;            
            $db->query("INSERT INTO tb_rel_alternatif(id_alternatif, id_kriteria, nilai) SELECT '$ID', id_kriteria, -1 FROM tb_kriteria");       
            redirect_js("index.php?m=alternatif");
        }
    } else if($mod=='alternatif_ubah'){
        $kode_alternatif = $_POST['kode_alternatif'];
        $nama_alternatif = $_POST['nama_alternatif'];
        $keterangan = $_POST['keterangan'];
        
        if($kode_alternatif=='' || $nama_alternatif=='')
            print_msg("Field yang bertanda * tidak boleh kosong!");
        elseif($db->get_results("SELECT * FROM tb_alternatif WHERE kode_alternatif='$kode_alternatif' AND id_alternatif<>'$_GET[ID]'"))
            print_msg("Kode sudah ada!");
        else{
            $db->query("UPDATE tb_alternatif SET kode_alternatif='$kode_alternatif', nama_alternatif='$nama_alternatif', keterangan='$keterangan' WHERE id_alternatif='$_GET[ID]'");
            redirect_js("index.php?m=alternatif");
        }
    } else if ($act=='alternatif_hapus'){
        $db->query("DELETE FROM tb_alternatif WHERE id_alternatif='$_GET[ID]'");
        $db->query("DELETE FROM tb_rel_alternatif WHERE id_alternatif='$_GET[ID]'");
        header("location:index.php?m=alternatif");
    } 
    
    /** KRITERIA */    
    if($mod=='kriteria_tambah'){
        $kode_kriteria = $_POST['kode_kriteria'];
        $nama_kriteria = $_POST['nama_kriteria'];    
        $minmax = $_POST['minmax'];
        $tipe = $_POST['tipe'];
        $par_q = $_POST['par_q'];
        $par_p = $_POST['par_p'];
        
        if($kode_kriteria=='' || $nama_kriteria=='' || $minmax=='' || $tipe=='')
            print_msg("Field bertanda * tidak boleh kosong!");
        elseif($db->get_results("SELECT * FROM tb_kriteria WHERE kode_kriteria='$kode_kriteria'"))
            print_msg("Kode sudah ada!");
        else{
            $db->query("INSERT INTO tb_kriteria (kode_kriteria, nama_kriteria, minmax, tipe, par_q, par_p) 
                VALUES ('$kode_kriteria', '$nama_kriteria', '$minmax', '$tipe', '$par_q', '$par_p')");
            $ID = $db->insert_id;        
            $db->query("INSERT INTO tb_rel_alternatif(id_alternatif, id_kriteria, nilai) SELECT id_alternatif, '$ID', -1  FROM tb_alternatif");        
            $db->query("INSERT INTO tb_rel_kriteria(ID1, ID2, nilai) SELECT '$ID', id_kriteria, 1 FROM tb_kriteria");
            $db->query("INSERT INTO tb_rel_kriteria(ID1, ID2, nilai) SELECT id_kriteria, '$ID', 1 FROM tb_kriteria WHERE id_kriteria<>'$ID'");           
            redirect_js("index.php?m=kriteria");
        }                    
    } else if($mod=='kriteria_ubah'){
        $kode_kriteria = $_POST['kode_kriteria'];
        $nama_kriteria = $_POST['nama_kriteria'];    
        $minmax = $_POST['minmax'];
        $tipe = $_POST['tipe'];
        $par_q = $_POST['par_q'];
        $par_p = $_POST['par_p'];
        
        if($kode_kriteria=='' || $nama_kriteria=='' || $minmax=='' || $tipe=='')
            print_msg("Field bertanda * tidak boleh kosong!");
        elseif($db->get_results("SELECT * FROM tb_kriteria WHERE kode_kriteria='$kode_kriteria' AND id_kriteria<>'$_GET[ID]'"))
            print_msg("Kode sudah ada!");
        else{        
            $db->query("UPDATE tb_kriteria SET kode_kriteria='$kode_kriteria', nama_kriteria='$nama_kriteria', minmax='$minmax', tipe='$tipe', par_q='$par_q', par_p='$par_p' WHERE id_kriteria='$_GET[ID]'");        
            redirect_js("index.php?m=kriteria");
        }    
    } else if ($act=='kriteria_hapus'){
        $db->query("DELETE FROM tb_kriteria WHERE id_kriteria='$_GET[ID]'");
        $db->query("DELETE FROM tb_rel_alternatif WHERE id_kriteria='$_GET[ID]'");    
        $db->query("DELETE FROM tb_rel_kriteria WHERE ID1='$_GET[ID]' OR ID2='$_GET[ID]'");
        header("location:index.php?m=kriteria");
    } 
    
    /** RELASI KRITERIA */
    else if ($mod=='rel_kriteria'){
        $ID1 = $_POST['ID1'];
        $ID2 = $_POST['ID2'];
        $nilai = abs($_POST['nilai']);
        
        if($ID1==$ID2 && $nilai<>1)
            print_msg("Kriteria yang sama harus bernilai 1.");
        else{
            $db->query("UPDATE tb_rel_kriteria SET nilai=$nilai WHERE ID1='$ID1' AND ID2='$ID2'");
            $db->query("UPDATE tb_rel_kriteria SET nilai=1/$nilai WHERE ID2='$ID1' AND ID1='$ID2'");
            print_msg("Nilai kriteria berhasil diubah.", 'success');
        }
    }
        
    /** RELASI ALTERNATIF */ 
    else if ($act=='rel_alternatif_ubah'){
        foreach($_POST as $key => $value){
            $ID = str_replace('ID-', '', $key);
            $db->query("UPDATE tb_rel_alternatif SET nilai='$value' WHERE ID='$ID'");
        }
        header("location:index.php?m=rel_alternatif");
    }