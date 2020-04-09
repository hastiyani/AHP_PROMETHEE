<style>    
    .text-primary{font-weight: bold;}
</style>
<div class="page-header">
    <h3>Perhitungan <em>AHP - PROMETHE</em></h3>
</div>
<?php
    $c = $db->get_results("SELECT * FROM tb_rel_alternatif WHERE nilai < 0 ");
    if (!$ALTERNATIF || !$KRITERIA):
        echo "Tampaknya anda belum mengatur alternatif dan kriteria. Silahkan tambahkan minimal 3 alternatif dan 3 kriteria.";
    elseif ($c):
        echo "Tampaknya anda belum mengatur nilai alternatif. Silahkan atur pada menu <strong>Nilai Alternatif</strong>.";
    else:
    
    $data = get_data();
    $komposisi = get_komposisi();
    $normal = get_normal($data, $komposisi); 
    $selisih = get_selisih($data, $normal);
    $preferensi = get_preferensi($selisih);
    $index_pref = get_index_pref($preferensi);
    $total_index_pref = get_total_indeks_pref($index_pref);
    $matriks = get_matriks($komposisi, $total_index_pref);
    $total_kolom = get_total_kolom($matriks);
    $total_baris = get_total_baris($matriks);
    $leaving = get_leaving($matriks, $total_baris);
    $entering = get_entering($matriks, $total_kolom);
    $net_flow = get_net_flow($leaving, $entering);
    $rank = get_rank($net_flow);
?>
<div class="panel panel-default">
    <div class="panel-heading"><strong>Hasil Analisa</strong></div>
    <div class="table-responsive"> 
        <table class="table table-bordered table-striped table-hover">
            <thead><tr>
                <td rowspan="2">Kriteria</td>
                <td rowspan="2">Min Maks</td>
                <td rowspan="2">Bobot</td>
                <td colspan="<?=count($ALTERNATIF)?>">Alternatif</td>
                <td rowspan="2">Tipe Preferensi</td>
                <td colspan="2">Parameter</td>
            </tr>
            <tr>
                <?php foreach($ALTERNATIF as $key => $val):?>
                <td><?=$val['nama']?></td>
                <?php endforeach?>
                <td>q</td>
                <td>p</td>
            </tr></thead>
            <?php foreach($KRITERIA as $key => $val):?>
            <tr>
                <td><?=$val['nama']?></td>
                <td><?=$val['minmax']?></td>
                <td><?=round($val['bobot'], 4)?></td>
                <?php foreach($ALTERNATIF as $k => $v):?>
                <td><?=$data[$k][$key]?></td>
                <?php endforeach?>
                <td><?=$val['tipe']?></td>
                <td><?=$val['par_q']?></td>
                <td><?=$val['par_p']?></td>
            </tr>
            <?php endforeach?>
        </table>
    </div>
</div>
<?php foreach($normal as $key => $val):?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#ckrit<?=$key?>" aria-expanded="false" aria-controls="ckrit<?=$key?>">
                    Kriteria <?=$KRITERIA[$key]['nama']?>
                </a>
            </h3>
        </div>
        <div class="table-responsive collapse" id="ckrit<?=$key?>">
            <table class="table table-striped table-hover table-bordered">
                <thead>
                    <th colspan="2"><?=$KRITERIA[$key]['nama']?></th>
                    <th>a</th>
                    <th>b</th>
                    <th>d(selisih nilai kriteria)</th>
                    <th>H(d)</th>
                    <th>P (Preferensi)</th>
                    <th>P (Indeks Preferensi)</th>
                </thead>
                <?php foreach($val as $k => $v):?>
                <tr>
                    <td><?=$ALTERNATIF[$v[0]]['nama']?></td>
                    <td><?=$ALTERNATIF[$v[1]]['nama']?></td>
                    <td><?=$data[$v[0]][$key]?></td>
                    <td><?=$data[$v[1]][$key]?></td>
                    <td><?=$selisih[$key][$k]?></td>
                    <td><?=abs($selisih[$key][$k])?></td>
                    <td><?=round($preferensi[$key][$k], 4)?></td>
                    <td><?=round($index_pref[$key][$k], 4)?></td>
                </tr>
                <?php endforeach?>
            </table>
        </div>
    </div>
<?php endforeach?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Total Indeks Preferensi</h3>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead><tr>
                <th colspan="2">Alternatif</th>
                <th>Total</th>
            </tr></thead>
            <?php foreach($komposisi as $key => $val):?>
            <tr>
                <td><?=$ALTERNATIF[$val[0]]['nama']?></td>
                <td><?=$ALTERNATIF[$val[1]]['nama']?></td>
                <td><?=round($total_index_pref[$key], 4)?></td>
            </tr>
            <?php endforeach?>
        </table>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Perbandingan Alternatif</h3>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead><tr>
                <th>Alternatif</th>
                <?php foreach($matriks as $key => $val):?>
                <th><?=$ALTERNATIF[$key]['nama']?></th>        
                <?php endforeach?>
                <th>Jumlah</th>
                <th>Leaving</th>
            </tr></thead>
            <?php foreach($matriks as $key => $val):?>
            <tr>
                <td><?=$ALTERNATIF[$key]['nama']?></td>
                <?php foreach($val as $k => $v):?>
                <td><?=round($v, 4)?></td>
                <?php endforeach?>    
                <td><?=round($total_baris[$key], 4)?></td>
                <td><?=round($leaving[$key], 4)?></td>
            </tr>        
            <?php endforeach?>
            <tr>
                <td>Jumlah</td>
                <?php foreach($total_kolom as $k => $v):?>
                <td><?=round($v, 4)?></td>
                <?php endforeach?>
                <td colspan="2" rowspan="2"></td>
            </tr>
            <tr>
                <td>Entering</td>
                <?php foreach($entering as $k => $v):?>
                <td><?=round($v, 4)?></td>
                <?php endforeach?>
            </tr>
        </table>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Hasil Akhir </h3>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead><tr>
                <th>Alternatif</th>
                <th>Leaving Flow</th>
                <th>Entering Flow</th>
                <th>Net Flow</th>
                <th>Urutan</th>
            </tr></thead>
            <?php foreach($ALTERNATIF as $key => $val):?>
            <tr>
                <td><?=$val['nama']?></td>            
                <td><?=round($leaving[$key], 4)?></td>
                <td><?=round($entering[$key], 4)?></td>
                <td><?=round($net_flow[$key], 4)?></td>
                <td><?=$rank[$key]?></td>
            </tr>        
            <?php endforeach?>        
        </table>
    </div>
</div>
<?php
arsort($net_flow);
?>
<p class="text-center">Alternatif prioritas pilihan terbaik adalah <strong><?=$ALTERNATIF[key($net_flow)]['nama']?></strong> dengan total: <strong><?=round(current($net_flow), 4)?></strong></p>
<?php endif?>
