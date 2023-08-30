<?php

$query = "SELECT * FROM stato_citta";
$res = gdrcd_query($query, 'query');
?>
<div class="page_frame_top">
    <h2 class="w3-center">Stato della Città</h2>
    <div class="w3-center"> <?php echo $res['valore_stato']; ?> - <?php echo $res['nome_stato']; ?> </div>
    <p class="w3-center"> <?php echo $res['descr_stato']; ?> </p>
</div>
