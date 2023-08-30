<?php


?>
<div class="elenco_abilita"><!-- Elenco abilità -->
    <div class="w3-header w3-center">
        <h2>Statistiche</h2>
    </div>
    <?php
//Incremento statistica
    if ((gdrcd_filter('get', $_REQUEST['op']) == 'addstat') && (($_SESSION['login'] == gdrcd_filter('out', $_REQUEST['pg'])) || ($_SESSION['permessi'] >= MODERATOR))) {
//carico il personaggio
$personaggio = gdrcd_query("SELECT car0,car1,car2,car3,car4,car5 ,id_razza, esperienza FROM personaggio WHERE nome='" . gdrcd_filter('in', $_REQUEST['pg']) . "'", 'query');
$px_totali_pg = gdrcd_filter('int', $personaggio['esperienza']);        
//calcolo quanto costa alzare la statistica richiesta
        $px_necessari = $PARAMETERS['settings']['px_x_stat'];
        if( ($personaggio[$_REQUEST['what']] + 1) > 20 && ($personaggio[$_REQUEST['what']] + 1) <= 25 ) {
            $px_necessari = $PARAMETERS['settings']['px_x_stat_over_20'];
        }
        if( ($personaggio[$_REQUEST['what']] + 1) > 25  ) {
            $px_necessari = $PARAMETERS['settings']['px_x_stat_over_25'];
        }
        if (($px_totali_pg) >= $px_necessari) {
            //Se sto apposto con i px allora, aggiorno tutto.
            $query = "UPDATE personaggio SET ".gdrcd_filter("in", $_REQUEST['what'])." =".gdrcd_filter("in", $_REQUEST['what'])."+1 , esperienza = esperienza-".$px_necessari."  WHERE nome='" . gdrcd_filter('in', $_REQUEST['pg']) . "' LIMIT 1 ";           
            gdrcd_query($query);
            $px_totali_pg -= $px_necessari;
            echo '<div class="warning">' . gdrcd_filter('out', $MESSAGE['warning']['modified']) . '</div>';
        }
        
    }//Fine incremento skill
//Decremento skill
    if ((gdrcd_filter('get', $_REQUEST['op']) == 'substat') && ($_SESSION['permessi'] >= MODERATOR)) {
//carico il personaggio
$personaggio = gdrcd_query("SELECT car0,car1,car2,car3,car4,car5 ,id_razza, esperienza FROM personaggio WHERE nome='" . gdrcd_filter('in', $_REQUEST['pg']) . "'", 'query');
$px_totali_pg = gdrcd_filter('int', $personaggio['esperienza']);         
//Questo è il valore di PX di questo rango, quindi devo restituire i px al pg
        $px_necessari = $PARAMETERS['settings']['px_x_stat'];
        if( ($personaggio[$_REQUEST['what']]) > 20 && ($personaggio[$_REQUEST['what']]) <= 25 ) {
            $px_necessari = $PARAMETERS['settings']['px_x_stat_over_20'];
        }
        if( ($personaggio[$_REQUEST['what']]) > 25  ) {
            $px_necessari = $PARAMETERS['settings']['px_x_stat_over_25'];
        }
        
        //decremento solo se il PG ha ancora almeno 2 punti.
        if($personaggio[$_REQUEST['what']] >= 2){
        $query = "UPDATE personaggio SET ".gdrcd_filter("in", $_REQUEST['what'])." =".gdrcd_filter("in", $_REQUEST['what'])."-1 , esperienza = esperienza+".$px_necessari."  WHERE nome='" . gdrcd_filter('in', $_REQUEST['pg']) . "' LIMIT 1 ";
        gdrcd_query($query);
        $px_totali_pg += $px_necessari;
        echo '<div class="warning">' . gdrcd_filter('out', $MESSAGE['warning']['modified']) . '</div>';
        }else {
            echo '<div class="warning"> Non puoi portare una statistica al di sotto di 1</div>';
        }
        
    }//Fine decremento stat   
    //carico il personaggio aggiornato
    $personaggioAgg = gdrcd_query("SELECT car0,car1,car2,car3,car4,car5, esperienza FROM personaggio WHERE nome='" . gdrcd_filter('in', $_REQUEST['pg']) . "'", 'query');
    $px_totali_pg = gdrcd_filter('int', $personaggioAgg['esperienza']);    
    ?>
    <div class="form_info">
        
        <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['avalaible_xp']) . ': ' . ($px_totali_pg ); ?>
    </div>
    <div class="form_info">
        <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['info_skill_cost']); ?>
    </div>
    
    <div class="link_back_abilita">
        <a href="main.php?page=scheda&pg=<?php echo gdrcd_filter('url', $_REQUEST['pg']); ?>"><?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['link']['back']); ?></a>
    </div>
    <div class="link_back_abilita" style="margin-right: 5px;">
        <a href="main.php?page=scheda_abilita&pg=<?php echo gdrcd_filter('url', $_REQUEST['pg']); ?>">Abilità</a>
    </div>
    <section style="display: flex; width: 100%;">  
    <img style="width: 90px" src="../themes/advanced/imgs/scheda/bordurina-sx.png" alt="bordo sinistro"/>    
    <div class="contenitore_skill" >
        <div class="tabella_skill" style="width: 90%; margin-left:auto; margin-right: auto;">
            <div class="w3-center w3-content"> STATISTICHE </div>
            <?php for($i=0; $i<5; $i++) { ?>
            <div class="w3-content righe_skill">                  
                    <div class="abilita_scheda_car">
                        <?php echo gdrcd_filter('out', $PARAMETERS['names']['stats']['car'.$i]); ?>
                    </div>
                    <div class="abilita_scheda_nome">
                        <?php echo gdrcd_filter('out', $personaggioAgg['car'.$i]); ?>
                    </div>
                    <div class="abilita_scheda_sub">
                        <?php
                        /* Stampo il form di incremento se il pg ha abbastanza px */
                        $px_x_prossimogrado = $PARAMETERS['settings']['px_x_stat'];
                        if( ($personaggioAgg['car'.$i] + 1) > 20 && ($personaggioAgg['car'.$i] + 1) <= 25 ) {
                            $px_x_prossimogrado = $PARAMETERS['settings']['px_x_stat_over_20'];
                        }
                        if( ($personaggioAgg['car'.$i] + 1) > 25  ) {
                            $px_x_prossimogrado = $PARAMETERS['settings']['px_x_stat_over_25'];
                        }
                        if (($px_x_prossimogrado <= $px_totali_pg) && (gdrcd_filter('get', $_REQUEST['pg']) == $_SESSION['login']) || ($_SESSION['permessi'] >= MODERATOR)) {
                            ?>
                            [<a href="main.php?page=scheda_stat&pg=<?php echo gdrcd_filter('url', $_REQUEST['pg'])?>&op=addstat&what=<?php echo 'car'.$i ?>">+</a>]
                            <?php if (($_SESSION['permessi'] >= MODERATOR) && ($personaggioAgg['car'.$i] >=2)) { ?>
                            [<a href="main.php?page=scheda_stat&pg=<?php echo gdrcd_filter('url', $_REQUEST['pg']) ?>&op=substat&what=<?php echo 'car'.$i ?>">-</a>]
                            <?php
                            }
                        } else {
                            echo '&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            <?php } ?>
        </div>           
    </div>
    <img style="width: 90px" src="../themes/advanced/imgs/scheda/bordurina-dx.png" alt="bordo destro"/>
    </section>
    
    
</div>