<?php
//carico le sole abilità del pg
$abilita = gdrcd_query("SELECT id_abilita, grado FROM clgpersonaggioabilita WHERE nome='" . gdrcd_filter('in', $_REQUEST['pg']) . "'", 'result');

$px_spesi = 0;
while ($row = gdrcd_query($abilita, 'fetch')) {
    /* Costo in px della singola abilità */
    $px_abi = $PARAMETERS['settings']['px_x_rank'];
    if( $row['grado'] > 20 &&  $row['grado'] <= 25 ){$px_abi = $PARAMETERS['settings']['px_x_rank_over_20'];}
    if( $row['grado'] > 25 ){$px_abi = $PARAMETERS['settings']['px_x_rank_over_25'];}
    /* Costo totale */
    $px_spesi += $px_abi;
    $ranks[$row['id_abilita']] = $row['grado'];
}

$personaggio = gdrcd_query("SELECT id_razza, esperienza FROM personaggio WHERE nome='" . gdrcd_filter('in', $_REQUEST['pg']) . "'", 'query');

$px_totali_pg = gdrcd_filter('int', $personaggio['esperienza']);
?>
<div class="elenco_abilita"><!-- Elenco abilità -->
    <div class="w3-header w3-center">
        <h2><?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['box_title']['skills']); ?></h2>
    </div>
    <?php
//Incremento skill
    if ((gdrcd_filter('get', $_REQUEST['op']) == 'addskill') && (($_SESSION['login'] == gdrcd_filter('out', $_REQUEST['pg'])) || ($_SESSION['permessi'] >= MODERATOR))) {
        
        $px_necessari = $PARAMETERS['settings']['px_x_rank'];
        if( ($ranks[$_REQUEST['what']] + 1) > 20 && ($ranks[$_REQUEST['what']] + 1) <= 25 ) {
            $px_necessari = $PARAMETERS['settings']['px_x_rank_over_20'];
        }
        
        if( ($ranks[$_REQUEST['what']] + 1) > 25  ) {
            $px_necessari = $PARAMETERS['settings']['px_x_rank_over_25'];
        }
        
        if (($px_totali_pg) >= $px_necessari) {
            $px_spesi += $px_necessari;
            
            if ($ranks[$_REQUEST['what']] == 0) {
                $query = "INSERT INTO clgpersonaggioabilita (id_abilita, nome, grado) VALUES (" . gdrcd_filter('num', $_REQUEST['what']) . ", '" . gdrcd_filter('in', $_REQUEST['pg']) . "', 1)";
                $ranks[$_REQUEST['what']] = 1;
            } else {
                $ranks[$_REQUEST['what']]++;
                $query = "UPDATE clgpersonaggioabilita SET grado = " . $ranks[$_REQUEST['what']] . " WHERE id_abilita = " . gdrcd_filter('num', $_REQUEST['what']) . " AND nome = '" . gdrcd_filter('in', $_REQUEST['pg']) . "'";
            }//else
            gdrcd_query($query);
            gdrcd_query("UPDATE personaggio SET esperienza = esperienza-".$px_necessari." WHERE nome = '".$_REQUEST['pg']."'");
            $px_totali_pg -= $px_necessari;
            echo '<div class="warning">' . gdrcd_filter('out', $MESSAGE['warning']['modified']) . '</div>';
        }
    }//Fine incremento skill
//Decremento skill
    if ((gdrcd_filter('get', $_REQUEST['op']) == 'subskill') && ($_SESSION['permessi'] >= MODERATOR)) {
        //Questo è il valore di PX di questo rango, quindi devo restituire i px al pg
        $px_necessari = $PARAMETERS['settings']['px_x_rank'];
        if( ($ranks[$_REQUEST['what']]) > 20 && ($ranks[$_REQUEST['what']]) <= 25 ) {
            $px_necessari = $PARAMETERS['settings']['px_x_rank_over_20'];
        }
        
        if( ($ranks[$_REQUEST['what']]) > 25  ) {
            $px_necessari = $PARAMETERS['settings']['px_x_rank_over_25'];
        }
        
        if ($ranks[$_REQUEST['what']] == 1) {
            $query = "DELETE FROM clgpersonaggioabilita WHERE id_abilita = " . $_REQUEST['what'] . " AND nome = '" . gdrcd_filter('in', $_REQUEST['pg']) . "' LIMIT 1";
            $ranks[$_REQUEST['what']] = 0;
        } else {
            $ranks[$_REQUEST['what']]--;
            $query = "UPDATE clgpersonaggioabilita SET grado = " . $ranks[$_REQUEST['what']] . " WHERE id_abilita = " . $_REQUEST['what'] . " AND nome = '" . gdrcd_filter('in', $_REQUEST['pg']) . "'";
        }//else
        gdrcd_query($query);
        gdrcd_query("UPDATE personaggio SET esperienza = esperienza+".$px_necessari." WHERE nome = '".$_REQUEST['pg']."'");
        $px_totali_pg += $px_necessari;
        echo '<div class="warning">' . gdrcd_filter('out', $MESSAGE['warning']['modified']) . '</div>';
    }//Fine decremento skill
//conteggio le abilità
    $row = gdrcd_query("SELECT COUNT(*) FROM abilita WHERE id_razza=-1 OR id_razza= " . $personaggio['id_razza'] . "");
    $num = $row['COUNT(*)'];

//carico l'elenco delle abilità
    $result = gdrcd_query("SELECT nome, car, id_abilita FROM abilita WHERE ( id_razza = -1 OR id_razza= " . $personaggio['id_razza'] . ") AND categoria = 'Comune' ORDER BY id_razza DESC, nome", 'result');
   
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
        <a href="main.php?page=scheda_stat&pg=<?php echo gdrcd_filter('url', $_REQUEST['pg']); ?>">Statistiche</a>
    </div>
    <section style="display: flex; width: 100%;">  
    <img style="width: 90px" src="../themes/advanced/imgs/scheda/bordurina-sx.png" alt="bordo sinistro"/>    
    <div class="contenitore_skill">
        <div class="w3-half tabella_skill">
            <div class="w3-center w3-content"> Skill Generali </div>
            <?php while ($row = gdrcd_query($result, 'fetch')) { ?>
                <div class="w3-content righe_skill">
                    <div class="abilita_scheda_nome">
                        <?php echo gdrcd_filter('out', $row['nome']); ?>
                    </div>
                    <div class="abilita_scheda_car">
                        <?php echo '(' . gdrcd_filter('out', $PARAMETERS['names']['stats']['car' . $row['car']]) . ')'; ?>
                    </div>
                    <div class="abilita_scheda_tank">
                        <?php echo 0 + gdrcd_filter('int', $ranks[$row['id_abilita']]); ?>
                    </div>
                    <div class="abilita_scheda_sub">
                        <?php
                        /* Stampo il form di incremento se il pg ha abbastanza px */
                        $px_x_prossimogrado = $PARAMETERS['settings']['px_x_rank'];
                        if( ($ranks[$_REQUEST['what']] + 1) > 20 && ($ranks[$_REQUEST['what']] + 1) <= 25 ) {
                            $px_x_prossimogrado = $PARAMETERS['settings']['px_x_rank_over_20'];
                        }

                        if( ($ranks[$_REQUEST['what']] + 1) > 25  ) {
                            $px_x_prossimogrado = $PARAMETERS['settings']['px_x_rank_over_25'];
                        }
                        if ((($px_x_prossimogrado <= $px_totali_pg) && (gdrcd_filter('get', $_REQUEST['pg']) == $_SESSION['login']) && ($ranks[$row['id_abilita']] < $PARAMETERS['settings']['skills_cap'])) || ($_SESSION['permessi'] >= MODERATOR)) {
                            ?>
                            [<a href="main.php?page=scheda_abilita&pg=<?php
                            echo gdrcd_filter('url', $_REQUEST['pg']
                            )
                            ?>&op=addskill&what=<?php echo $row['id_abilita'] ?>">+</a>]
                                <?php if (($_SESSION['permessi'] >= MODERATOR) && ($ranks[$row['id_abilita']] > 0)) { ?>
                                [<a href="main.php?page=scheda_abilita&pg=<?php echo gdrcd_filter('url', $_REQUEST['pg']) ?>&op=subskill&what=<?php echo $row['id_abilita'] ?>">-</a>]
                                <?php
                            }
                        } else {
                            echo '&nbsp;';
                        }
                        ?>
                    </div>
                </div>
            <?php } 
             gdrcd_query($result,'free');
            ?>
        </div>
   
          <?php 
          $result = gdrcd_query("SELECT nome, car, id_abilita FROM abilita WHERE (id_razza=-1 OR id_razza= " . $personaggio['id_razza'] . ") AND categoria = 'Guerra' ORDER BY id_razza DESC, nome", 'result');
          ?>  
            

        <div class="w3-half tabella_skill">
            <div class="w3-center"> Skill Marziali </div>
            <?php while ($row = gdrcd_query($result, 'fetch')) { ?>
                <div  class="w3-content righe_skill">
                    <div class="abilita_scheda_nome">
                        <?php echo gdrcd_filter('out', $row['nome']); ?>
                    </div>
                    <div class="abilita_scheda_car">
                        <?php echo '(' . gdrcd_filter('out', $PARAMETERS['names']['stats']['car' . $row['car']]) . ')'; ?>
                    </div>
                    <div class="abilita_scheda_tank">
                        <?php echo 0 + gdrcd_filter('int', $ranks[$row['id_abilita']]); ?>
                    </div>
                    <div class="abilita_scheda_sub">
                        <?php
                       $px_x_prossimogrado = $PARAMETERS['settings']['px_x_rank'];
                        if( ($ranks[$_REQUEST['what']] + 1) > 20 && ($ranks[$_REQUEST['what']] + 1) <= 25 ) {
                            $px_x_prossimogrado = $PARAMETERS['settings']['px_x_rank_over_20'];
                        }

                        if( ($ranks[$_REQUEST['what']] + 1) > 25  ) {
                            $px_x_prossimogrado = $PARAMETERS['settings']['px_x_rank_over_25'];
                        }
                        /* Stampo il form di incremento se il pg ha abbastanza px */
                       if ((($px_x_prossimogrado <= $px_totali_pg) && (gdrcd_filter('get', $_REQUEST['pg']) == $_SESSION['login']) && ($ranks[$row['id_abilita']] < $PARAMETERS['settings']['skills_cap'])) || ($_SESSION['permessi'] >= MODERATOR)) {
                            ?>
                            [<a href="main.php?page=scheda_abilita&pg=<?php
                            echo gdrcd_filter('url', $_REQUEST['pg']
                            )
                            ?>&op=addskill&what=<?php echo $row['id_abilita'] ?>">+</a>]
                                <?php if (($_SESSION['permessi'] >= MODERATOR) && ($ranks[$row['id_abilita']] > 0)) { ?>
                                [<a href="main.php?page=scheda_abilita&pg=<?php echo gdrcd_filter('url', $_REQUEST['pg']) ?>&op=subskill&what=<?php echo $row['id_abilita'] ?>">-</a>]
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