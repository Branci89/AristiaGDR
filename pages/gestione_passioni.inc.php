<div class="pagina_gestione_abilita">
    <?php /*HELP: */
    /*Controllo permessi utente*/
    if($_SESSION['permessi'] < MODERATOR) {
        echo '<div class="error">'.gdrcd_filter('out', $MESSAGE['error']['not_allowed']).'</div>';
    } else { ?>
        <!-- Titolo della pagina -->
        
        
        <div class="page_title">
            <h2><?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['passioni']['page_name']); ?></h2>
        </div>
        <!-- Corpo della pagina -->
        <div class="page_body">
            <?php /*Inserimento di un nuovo record*/
            if($_POST['op'] == 'insert') {
                /*Eseguo l'inserimento*/
                gdrcd_query("INSERT INTO passioni (nome, descrizione, comune) VALUES ('".gdrcd_filter('in', $_POST['nome'])."', '".gdrcd_filter('in', $_POST['descrizione'])."' , ".gdrcd_filter('num', $_POST['comune']).")");
                ?>
                <div class="warning">
                    <?php echo gdrcd_filter('out', $MESSAGE['warning']['inserted']); ?>
                </div>
                <!-- Link di ritorno alla visualizzazione di base -->
                <div class="link_back">
                    <a href="main.php?page=gestione_passioni">
                        <?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['passioni']['link']['back']); ?>
                    </a>
                </div>
            <?php
            }
            /* Cancellatura in un record */
            if(gdrcd_filter('get', $_POST['op']) == 'erase') {
                /*Eseguo la cancellatura*/
                gdrcd_query("DELETE FROM passioni WHERE id_passione=".gdrcd_filter('num', $_POST['id_record'])." LIMIT 1");

                /*Aggiorno i personaggi*/
                gdrcd_query("DELETE FROM clgpassionipersonaggio WHERE id_passione=".gdrcd_filter('num', $_POST['id_record'])."");
                ?>
                <div class="warning">
                    <?php echo gdrcd_filter('out', $MESSAGE['warning']['deleted']); ?>
                </div>
                <!-- Link di ritorno alla visualizzazione di base -->
                <div class="link_back">
                    <a href="main.php?page=gestione_passioni">
                        <?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['passioni']['link']['back']); ?>
                    </a>
                </div>
            <?php
            }
            /*Modifica di un record*/
            if(gdrcd_filter('get', $_POST['op']) == 'modify') {
                /*Eseguo l'aggiornamento*/
                gdrcd_query("UPDATE passioni SET nome ='".gdrcd_filter('in', $_POST['nome'])."', descrizione ='".gdrcd_filter('in', $_POST['descrizione'])."', comune = ".gdrcd_filter('num', $_POST['comune'])."  WHERE id_passione = ".gdrcd_filter('num', $_POST['id_record'])." LIMIT 1"); ?>
                <div class="warning">
                    <?php echo gdrcd_filter('out', $MESSAGE['warning']['modified']); ?>
                </div>
                <!-- Link di ritorno alla visualizzazione di base -->
                <div class="link_back">
                    <a href="main.php?page=gestione_passioni">
                        <?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['passioni']['link']['back']); ?>
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
                    $loaded_record = gdrcd_query("SELECT * FROM passioni WHERE id_passione=".gdrcd_filter('num', $_POST['id_record'])." LIMIT 1 ");
                    /*Cambio l'operazione in modifica*/
                    $operation = 'edit';
                } ?>
                <!-- Form di inserimento/modifica -->
                <div class="panels_box">
                    <form action="main.php?page=gestione_passioni" method="post" class="form_gestione">
                        <div class='form_label'>
                            <?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['passioni']['name']); ?>
                        </div>
                        <div class='form_field'>
                            <input name="nome" value="<?php echo $loaded_record['nome']; ?>" />
                        </div>
                        <div class='form_label'>
                            <?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['passioni']['infos']); ?>
                        </div>
                        <div class='form_field'>
                            <textarea name="descrizione"><?php echo $loaded_record['descrizione']; ?></textarea>
                        </div>
                        <div class='form_label'>
                            <?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['passioni']['is_commons']); ?>
                        </div>
                        <div class='form_field'>
                            <select name='comune'>
                                <option value="1" <?php if($loaded_record['comune'] == 0) { echo 'SELECTED'; } ?>>
                                    Comune</option>
                                <option value="0" <?php if($loaded_record['comune'] == 1) { echo 'SELECTED'; } ?>>
                                    Speciale</option>
                            </select>
                        </div>
                        
                        <!-- bottoni -->
                        <div class='form_submit'>
                            <?php /* Se l'operazione è una modifica stampo i tasti modifica e annulla */
                            if($operation == "edit") { ?>
                                <input type="hidden" name="id_record" value="<?php echo $loaded_record['id_passione']; ?>">
                                <input type="hidden" name="op" value="modify" />
                                <input type="submit" value="<?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['passioni']['submit']['edit']); ?>" />
                            <?php
                            }  else {  /* Altrimenti il tasto inserisci */ ?>
                                <input type="submit" value="<?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['passioni']['submit']['insert']); ?>" />
                                <input type="hidden" name="op" value="insert" />
                            <?php
                            } ?>
                        </div>
                    </form>
                </div>
                <!-- Link di ritorno alla visualizzazione di base -->
                <div class="link_back">
                    <a href="main.php?page=gestione_passioni">
                        <?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['passioni']['link']['back']); ?>
                    </a>
                </div>
            <?php }
            //if
            if(isset($_REQUEST['op']) === false) { /*Elenco record (Visualizzaione di base della pagina)*/
                //Determinazione pagina (paginazione)
                $pagebegin = (int) gdrcd_filter('get', $_REQUEST['offset']) * $PARAMETERS['settings']['records_per_page'];
                $pageend = $PARAMETERS['settings']['records_per_page'];
                //Conteggio record totali
                $record_globale = gdrcd_query("SELECT COUNT(*) FROM passioni");
                $totaleresults = $record_globale['COUNT(*)'];

                //Lettura record
                $result = gdrcd_query("SELECT id_passione, nome, descrizione, comune FROM passioni ORDER BY nome LIMIT ".$pagebegin.", ".$pageend."", 'result');
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
                                    <div class="titoli_elenco">Descrizione</div>
                                </td>
                                <td class="casella_titolo">
                                   <div class="titoli_elenco"><?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['passioni']['is_commons']); ?></div>
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
                                            <?php echo $row['nome']; ?>
                                        </div>
                                    </td>
                                    <td class="casella_elemento">
                                        <div class="elementi_elenco">
                                            <?php echo $row['descrizione']; ?>
                                        </div>
                                    </td>
                                    <td class="casella_elemento">
                                        <div class="elementi_elenco">
                                            <?php echo $row['comune']== 1 ? 'Comune':'Speciale' ; ?>
                                        </div>
                                    </td>
                                    <td class="casella_controlli"><!-- Iconcine dei controlli -->
                                                                  <!-- Modifica -->
                                        <div class="controlli_elenco">
                                            <div class="controllo_elenco">
                                                <form class="opzioni_elenco_record_gestione" action="main.php?page=gestione_passioni" method="post">
                                                    <input type="hidden" name="id_record" value="<?php echo $row['id_passione'] ?>" />
                                                    <input type="hidden" name="op" value="edit" />
                                                    <input type="image" alt="<?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['ops']['edit']); ?>" title="<?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['ops']['edit']); ?>" src="imgs/icons/edit.png" />
                                                </form>
                                            </div>
                                            <!-- Elimina -->
                                            <div class="controllo_elenco">
                                                <form class="opzioni_elenco_record_gestione" action="main.php?page=gestione_passioni" method="post">
                                                    <input type="hidden" name="id_record" value="<?php echo $row['id_passione'] ?>" />
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
                                <a href="main.php?page=gestione_passioni&offset=<?php echo $i; ?>"><?php echo $i + 1; ?></a>
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
                    <a href="main.php?page=gestione_passioni&op=new">
                        <?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['passioni']['link']['new']); ?>
                    </a>
                </div>
            <?php
            }//else
            ?>
             
                
        </div>
    <?php
    }//else (controllo permessi utente) ?>
</div><!--Pagina-->