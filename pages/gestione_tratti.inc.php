<div class="pagina_gestione_abilita">
    <?php /*HELP: */
    /*Controllo permessi utente*/
    if($_SESSION['permessi'] < MODERATOR) {
        echo '<div class="error">'.gdrcd_filter('out', $MESSAGE['error']['not_allowed']).'</div>';
    } else { ?>
        <!-- Titolo della pagina -->
        <div class="page_title">
            <h2><?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['tratti']['page_name']); ?></h2>
        </div>
        <!-- Corpo della pagina -->
        <div class="page_body">
            <?php /*Inserimento di un nuovo record*/
            if($_POST['op'] == 'insert') {
                /*E' un poco complesso, prima di tutto capiamo quanti tratti ci sono nella anag tratti*/
                $result = gdrcd_query("SELECT DISTINCT id_tratto FROM anag_tratti", 'result');
                $countTratti = gdrcd_query($result,'num_rows');
                $lastTratto = "tratto".$countTratti;
                $newTratto = "tratto".($countTratti+1);
                /*Adesso aggiungo la colonna alla tabella tratti*/
                gdrcd_query("ALTER TABLE tratti ADD COLUMN ".$newTratto." INT NOT NULL DEFAULT '0' AFTER ".$lastTratto."");
                
                /*Eseguo l'inserimento*/
                gdrcd_query("INSERT INTO anag_tratti (id_tratto, nomeBasso, valoreBasso, nomeAlto, valoreAlto, descrizione, nome_tratto )"
                        . "VALUES ('".$newTratto."', '". gdrcd_filter('out', $_POST['nomeBasso'])."', '". gdrcd_filter('out', $_POST['valoreBasso'])."' , '". gdrcd_filter('out', $_POST['nomeAlto'])."' , '". gdrcd_filter('out', $_POST['valoreAlto'])."' , '". gdrcd_filter('out', $_POST['descrizione'])."', '". gdrcd_filter('out', $_POST['nome_tratto'])."' )");
                
                ?>
                <div class="warning">
                    <?php echo gdrcd_filter('out', $MESSAGE['warning']['inserted']); ?>
                </div>
                <!-- Link di ritorno alla visualizzazione di base -->
                <div class="link_back">
                    <a href="main.php?page=gestione_tratti">
                        <?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['tratti']['link']['back']); ?>
                    </a>
                </div>
            <?php
            }
            /* Cancellatura in un record */
            if(gdrcd_filter('get', $_POST['op']) == 'erase') {
                /*Eseguo la cancellatura*/
                gdrcd_query("DELETE FROM anag_tratti WHERE id_tratto='".gdrcd_filter('out', $_POST['id_record'])."' LIMIT 1");
                /*Devo eliminare la colonna che rappresenta il tratto*/
                gdrcd_query("ALTER TABLE tratti DROP COLUMN ".gdrcd_filter('out', $_POST['id_record'])."");
               
                ?>
                <div class="warning">
                    <?php echo gdrcd_filter('out', $MESSAGE['warning']['deleted']); ?>
                </div>
                <!-- Link di ritorno alla visualizzazione di base -->
                <div class="link_back">
                    <a href="main.php?page=gestione_tratti">
                        <?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['tratti']['link']['back']); ?>
                    </a>
                </div>
            <?php
            }
            /*Modifica di un record*/
            if(gdrcd_filter('get', $_POST['op']) == 'modify') {
                /*Eseguo l'aggiornamento*/
                
                gdrcd_query("UPDATE anag_tratti SET nome_tratto ='".gdrcd_filter('in', $_POST['nome_tratto'])."', descrizione ='".gdrcd_filter('in', $_POST['descrizione'])."', nomeBasso = '".gdrcd_filter('out', $_POST['nomeBasso'])."', valoreBasso =". gdrcd_filter('num', $_POST['valoreBasso']).", nomeAlto='". gdrcd_filter('out', $_POST['nomeAlto'])."' , valoreAlto = ". gdrcd_filter('out', $_POST['valoreBasso'])." "
                        . "WHERE id_tratto ='".gdrcd_filter('out',$_POST['id_record'])."' LIMIT 1"); ?>
                <div class="warning">
                    <?php echo gdrcd_filter('out', $MESSAGE['warning']['modified']); ?>
                </div>
                <!-- Link di ritorno alla visualizzazione di base -->
                <div class="link_back">
                    <a href="main.php?page=gestione_tratti">
                        <?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['tratti']['link']['back']); ?>
                    </a>
                </div>
            <?php
            }
            /*Form di inserimento/modifica*/
            if((gdrcd_filter('get', $_POST['op']) == 'edit') || (gdrcd_filter('get', $_REQUEST['op']) == 'new')) {
                /*Preseleziono l'operazione di inserimento*/
                $operation = 'insert';
                /*Se è stata richiesta una modifica*/
                if(gdrcd_filter('get', $_POST['op']) == 'edit') {
                    /*Carico il record da modificare*/
                    $loaded_record = gdrcd_query("SELECT * FROM anag_tratti WHERE id_tratto='".gdrcd_filter('out', $_POST['id_record'])."' LIMIT 1 ");
                   
                    /*Cambio l'operazione in modifica*/
                    $operation = 'edit';
                } ?>
                <!-- Form di inserimento/modifica -->
                <div class="panels_box">
                    <form action="main.php?page=gestione_tratti" method="post" class="form_gestione">
                        <div class='form_label'>
                            Nome Tratto
                        </div>
                        <div class='form_field'>
                            <input name="nome_tratto" value="<?php echo $loaded_record['nome_tratto']; ?>" />
                        </div>
                        <div class='form_label'>
                            Valore Basso
                        </div>
                        <div class='form_field'>
                            <input type="number" min="0" max="20" name="valoreBasso" style="width: 80px" value="<?php echo $loaded_record['valoreBasso']; ?>"></input>
                        </div>
                        <div class='form_label'>
                            Nome Accezione Negativa
                        </div>
                        <div class='form_field'>
                            <input name="nomeBasso" value="<?php echo $loaded_record['nomeBasso']; ?>"></input>
                        </div>
                         <div class='form_label'>
                            Valore Accezionee Positiva
                        </div>
                        <div class='form_field'>
                            <input type="number" min="0" max="20" name="valoreAlto" style="width: 80px" value="<?php echo $loaded_record['valoreBasso']; ?>"></input>
                        </div>
                        <div class='form_label'>
                            Nome Accezione Positiva
                        </div>
                        <div class='form_field'>
                            <input name="nomeAlto" value="<?php echo $loaded_record['nomeAlto']; ?>"></input>
                        </div>
                       <div class='form_label'>
                            Descrizione
                        </div>
                        <div class='form_field'>
                            <textarea name="descrizione"><?php echo $loaded_record['descrizione']; ?></textarea>
                        </div>
                        
                        
                        <!-- bottoni -->
                        <div class='form_submit'>
                            <?php /* Se l'operazione è una modifica stampo i tasti modifica e annulla */
                            if($operation == "edit") { ?>
                                <input type="hidden" name="id_record" value="<?php echo $loaded_record['id_tratto']; ?>">
                                <input type="hidden" name="op" value="modify" />
                                <input type="submit" value="<?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['tratti']['submit']['edit']); ?>" />
                            <?php
                            }  else {  /* Altrimenti il tasto inserisci */ ?>
                                <input type="submit" value="<?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['tratti']['submit']['insert']); ?>" />
                                <input type="hidden" name="op" value="insert" />
                            <?php
                            } ?>
                        </div>
                    </form>
                </div>
                <!-- Link di ritorno alla visualizzazione di base -->
                <div class="link_back">
                    <a href="main.php?page=gestione_tratti">
                        <?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['tratti']['link']['back']); ?>
                    </a>
                </div>
            <?php }
            //if
            if(isset($_REQUEST['op']) === false) { /*Elenco record (Visualizzaione di base della pagina)*/
                //Determinazione pagina (paginazione)
                $pagebegin = (int) gdrcd_filter('get', $_REQUEST['offset']) * $PARAMETERS['settings']['records_per_page'];
                $pageend = $PARAMETERS['settings']['records_per_page'];
                //Conteggio record totali
                $record_globale = gdrcd_query("SELECT COUNT(*) FROM anag_tratti");
                $totaleresults = $record_globale['COUNT(*)'];

                //Lettura record
                $result = gdrcd_query("SELECT id_tratto, nome_tratto, nomeBasso,nomeAlto,valoreBasso,valoreAlto, descrizione FROM anag_tratti ORDER BY nome_tratto LIMIT ".$pagebegin.", ".$pageend."", 'result');
                $numresults = gdrcd_query($result, 'num_rows');

                /* Se esistono record */
                if($numresults > 0) { ?>
                    <!-- Elenco dei record paginato -->
                    <div class="elenco_record_gestione">
                        <table>
                            <!-- Intestazione tabella -->
                            <tr>
                                <td class="casella_titolo">
                                    <div class="titoli_elenco"><?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['name_col']); ?></div>
                                </td>
                                
                                <td class="casella_titolo">
                                   <div class="titoli_elenco">Accezione Negativa</div>
                                </td>
                                 <td class="casella_titolo">
                                   <div class="titoli_elenco">Valore Accezione negativa</div>
                                </td>
                                 <td class="casella_titolo">
                                   <div class="titoli_elenco">Accezione Positiva</div>
                                </td>
                                 <td class="casella_titolo">
                                   <div class="titoli_elenco">Valore Accezione positiva</div>
                                </td>
                                
                                <td class="casella_titolo">
                                    <div class="titoli_elenco">Descrizione</div>
                                </td>
                                <td class="casella_titolo">
                                    <div class="titoli_elenco"><?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['ops_col']); ?></div>
                                </td>
                            </tr>
                            <!-- Record -->
                            <?php while($row = gdrcd_query($result, 'fetch')) { ?>
                                <tr class="risultati_elenco_record_gestione">
                                    <td class="casella_elemento">
                                        <div class="elementi_elenco">
                                            <?php echo $row['nome_tratto']; ?>
                                        </div>
                                    </td>
                                    <td class="casella_elemento">
                                        <div class="elementi_elenco">
                                            <?php echo $row['nomeBasso']; ?>
                                        </div>
                                    </td>
                                    <td class="casella_elemento">
                                        <div class="elementi_elenco">
                                            <?php echo $row['valoreBasso']; ?>
                                        </div>
                                    </td>
                                    <td class="casella_elemento">
                                        <div class="elementi_elenco">
                                            <?php echo $row['nomeAlto']; ?>
                                        </div>
                                    </td>
                                    <td class="casella_elemento">
                                        <div class="elementi_elenco">
                                            <?php echo $row['valoreAlto']; ?>
                                        </div>
                                    </td>
                                    <td class="casella_elemento">
                                        <div class="elementi_elenco">
                                            <?php echo $row['descrizione']; ?>
                                        </div>
                                    </td>
                                   
                                    <td class="casella_controlli" style="display: flex; justify-content: center;"><!-- Iconcine dei controlli -->
                                                                  <!-- Modifica -->
                                        <div class="controlli_elenco">
                                            <div class="controllo_elenco">
                                                <form class="opzioni_elenco_record_gestione" action="main.php?page=gestione_tratti" method="post">
                                                    <input type="hidden" name="id_record" value="<?php echo $row['id_tratto'] ?>" />
                                                    <input type="hidden" name="op" value="edit" />
                                                    <input type="image" alt="<?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['ops']['edit']); ?>" title="<?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['ops']['edit']); ?>" src="imgs/icons/edit.png" />
                                                </form>
                                            </div>
                                            <!-- Elimina -->
                                            <div class="controllo_elenco">
                                                <form class="opzioni_elenco_record_gestione" action="main.php?page=gestione_tratti" method="post">
                                                    <input type="hidden" name="id_record" value="<?php echo $row['id_tratto'] ?>" />
                                                    <input type="hidden" name="op" value="erase" />
                                                    <input type="image" src="imgs/icons/erase.png" alt="<?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['ops']['erase']); ?>" title="<?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['ops']['erase']); ?>" />
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php } //while
                            gdrcd_query($result, 'free');
                            ?>
                        </table>
                    </div>
                <?php
                }//if
                ?>
                <!-- Paginatore elenco -->
                <div class="pager">
                    <?php if($totaleresults > $PARAMETERS['settings']['records_per_page']) {
                        echo gdrcd_filter('out', $MESSAGE['interface']['pager']['pages_name']);
                        for($i = 0; $i <= floor($totaleresults / $PARAMETERS['settings']['records_per_page']); $i++) {
                            if($i != gdrcd_filter('num', $_REQUEST['offset'])) {
                                ?>
                                <a href="main.php?page=gestione_tratti&offset=<?php echo $i; ?>"><?php echo $i + 1; ?></a>
                            <?php
                            } else {
                                echo ' '.($i + 1).' ';
                            }
                        } //for
                    }//if
                    ?>
                </div>

                <!-- link crea nuovo -->
                <div class="link_back">
                    <a href="main.php?page=gestione_tratti&op=new">
                        <?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['tratti']['link']['new']); ?>
                    </a>
                </div>
            <?php
            }//else
            ?>
        </div>
    <?php
    }//else (controllo permessi utente) ?>
    <div class="link_back" >
        <a href="main.php?page=gestione"> Torna al Menù </a>
    </div>
</div><!--Pagina-->
