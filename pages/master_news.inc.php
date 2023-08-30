<?php
/* * *
 * Begin patch by eLDiabolo ed Eriannen
 * 	01/09/2012
 *   	
 * Effettuato modifiche per adattamento a gdrcd 5.2 by Eriannen
 *   20/08/2013
 * modificato div class per la pagina per renderla visibile
 * e aggiunto title page con messaggio vocabolario
 * */
?>
<div class="page_frame_top">

    <div class="page_title">
        <h2 class="w3-center"><?php echo gdrcd_filter('out', $MESSAGE['interface']['news']['title_page']); ?></h2>
    </div>

    <?php
    /* HELP: */
    /*     * *
     * End patch by eLDiabolo ed Eriannen
     * */


    $query = "SELECT data_da, titolo, testo FROM news WHERE speciale=0 and (data_da >= CURDATE() and CURDATE() <= data_a) ORDER BY data_da";
    $result = gdrcd_query($query, 'result');
    ?>
    <div class="panels_box">
        <div class="">
                <?php while ($row = gdrcd_query($result, 'fetch')) { ?>

                <div class="panels_box">
                    <?php
                    /**
                     * 	Patch by eLDiabolo ed Eriannen
                     * 01/09/2012
                     * se non si vuol utilizzare nessuna icona accanto ad ogni titolo di news per questo box
                     * sostituire la riga sottostante

                      <img src="../imgs/icons/news2.gif">

                      con la la seguente:

                      <!--img src="../imgs/icons/news2.gif"-->

                      e vice versa per renderla nuovamente visibile una volta creata l'icona e posizionata secondo istruzioni.
                     * * */
                    ?>
                    <!--img src="../imgs/icons/news2.gif"-->
                    <span><?php echo gdrcd_filter('out', $row['data_da']); ?>:</span>
                <?php echo gdrcd_bbcoder(gdrcd_filter('out', $row['testo'])); ?>
                </div>
<?php }//while  ?>
        </div><!--elenco_record_gioco-->
    </div><!--panels_box-->

</div>


