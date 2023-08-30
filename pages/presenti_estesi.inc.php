<!-- Box presenti-->
<div class="pagina_presenti_estesa">
    <div class="page_title">
        <h2><?php echo gdrcd_filter('out', $MESSAGE['interface']['logged_users']['page_title']); ?></h2>
    </div>
    <div class="presenti_estesi">
        <?php
        /** * Abilitazione tooltip
         * @author Blancks
         */
        if ($PARAMETERS['mode']['user_online_state'] == 'ON') {
            echo '<div id="descriptionLoc"></div>';
        }
        //Carico la lista presenti.
        /** * Fix della query per includere l'uso dell'orario di uscita per capire istantaneamente quando il pg non è più connesso
         * @author Blancks
         */
        $query = "SELECT personaggio.nome,personaggio.fama, personaggio.cognome,cultura.nome_cultura, personaggio.permessi, personaggio.sesso, personaggio.id_razza, razza.sing_m, razza.sing_f, razza.icon, personaggio.disponibile, personaggio.online_status, personaggio.is_invisible, personaggio.ultima_mappa, personaggio.ultimo_luogo, personaggio.posizione, personaggio.ora_entrata, personaggio.ora_uscita, personaggio.ultimo_refresh, mappa.stanza_apparente, mappa.nome as luogo, mappa_click.nome as mappa FROM personaggio LEFT JOIN mappa ON personaggio.ultimo_luogo = mappa.id "
                . "LEFT JOIN mappa_click "
                . "ON personaggio.ultima_mappa = mappa_click.id_click "
                . "LEFT JOIN razza "
                . "ON personaggio.id_razza = razza.id_razza "
                . "LEFT JOIN cultura "
                . "ON personaggio.id_cultura = cultura.id_cultura "
                . "WHERE personaggio.ora_entrata > personaggio.ora_uscita "
                . "AND DATE_ADD(personaggio.ultimo_refresh, INTERVAL 4 MINUTE) > NOW() "
                . "ORDER BY personaggio.is_invisible, personaggio.ultima_mappa, personaggio.ultimo_luogo, personaggio.nome";
        $result = gdrcd_query($query, 'result');

        echo '<table class="table_presenti"> ';
        $ultimo_luogo_corrente = '';
        $mappa_corrente = '';
        
        //adesso stampo l'intestazione della tabella: una riga
        ?>
        <tr class="riga_intesazione_presenti">
            <th>Disponibile</th>
            <th>Cultura</th>
            <th>Nome Personaggio</th>
            <th>Fama</th>
            <th>Grado</th>
            <th>Invia Messaggio</th>
            <th>Stato Online</th>
        </tr>
        <?php while ($record = gdrcd_query($result, 'fetch')) {
        // Adesso stampiamo i dati raccolti dal database. Una riga per ogni presente -->
            //Stampo il nome del luogo
            if ($record['is_invisible'] == 1)  {
                $luogo_corrente = $MESSAGE['status_pg']['invisible'][1];
            } else {
                if ($record['mappa'] != $mappa_corrente)  {
                    $mappa_corrente = $record['mappa'];
                    echo '<tr class="nome_mappa_presenti"><td colspan="7">'.gdrcd_filter('out', $mappa_corrente).'</td></tr>';
                }//if

                if (empty($record['stanza_apparente'])) {
                    $luogo_corrente = $record['luogo'];
                } else  {
                    $luogo_corrente = $record['stanza_apparente'];
                }//else
            }
            //Stampo il nome del luogo solo per il primo PG che vi e' posizionato
            if (empty($luogo_corrente) === true) {
                if ($ultimo_luogo_corrente != $luogo_corrente)  {
                    $ultimo_luogo_corrente = $luogo_corrente;
                    echo '<tr class="luogo_presenti"><td colspan="7">'.gdrcd_filter('out', $luogo_corrente).'</td></tr>';
                } //if
            } else {
                if ($ultimo_luogo_corrente != $luogo_corrente)   {
                    $ultimo_luogo_corrente = $luogo_corrente;
                    if ($record['is_invisible'] == 0)  {
                        if (($PARAMETERS['mode']['mapwise_links'] == 'OFF')) { #||($record['ultima_mappa']==$_SESSION['mappa'])
                            echo '<tr class="luogo_presenti"><td colspan="7"><a href="main.php?dir='.$record['ultimo_luogo'].'&map_id='.$record['ultima_mappa'].'">'.gdrcd_filter('out', $luogo_corrente).'</a></td></tr>';
                        } else  {
                            echo '<tr class="luogo_presenti"><td colspan="7">'.gdrcd_filter('out', $luogo_corrente).'</td></tr';
                        }
                    } else  {
                        echo '<tr class="luogo_presenti"><td colspan="7">'.gdrcd_filter('out', $luogo_corrente).'</td></tr>';
                    }//else
                }
            }//if
            
            //costruisco la riga prescelta
            echo '<tr>';
            //Icona stato di disponibilità. E' sensibile se la riga che sto stampando corrisponde all'utente loggato.
            $change_disp = ($record['disponibile'] + 1) % 3;
                echo '<td class="colonna_presenti"><img class="presenti_ico" src="imgs/icons/disponibile'.$record['disponibile'].'.png" alt="'.gdrcd_filter('out', $MESSAGE['status_pg']['availability'][$record['disponibile']]).'" title="'.gdrcd_filter('out', $MESSAGE['status_pg']['availability'][$record['disponibile']]).'" /></td>';
                
                echo '<td class="colonna_presenti"><img class="cultura_ico" src="imgs/culture/'.$record['nome_cultura'].'_ico.png" alt="'.$record['nome_cultura'].'" /></td>';
                echo '<td class="colonna_presenti">';
                echo ' <a href="main.php?page=scheda&pg=' . $record['nome'] . '" class="link_sheet gender_' . $record['sesso'] . '">' . gdrcd_filter('out', $record['nome']);
                    if (empty($record['cognome']) === false) {
                        echo ' ' . gdrcd_filter('out', $record['cognome']);
                    }
                echo '</a></td>';
                $fama_icon = 'fama_bassa.png';
                if((int) $record['fama'] >= 0 && (int) $record['fama'] < 10){
                    $fama_icon = 'fama_bassa.png';
                }
                if((int) $record['fama'] >= 11 && (int) $record['fama'] < 15){
                    $fama_icon = 'fama_media.png';
                }
                if((int) $record['fama'] >= 15 ){
                    $fama_icon = 'fama_alta.png';
                }
                echo '<td class="colonna_presenti"><img class="fama_ico" src="imgs/icons/'.$fama_icon.'" alt="'.$record['fama'].'" /></td>';
                
                switch ($record['permessi']) {
                case USER:
                    $alt_permessi = '';
                    break;
                case GUILDMODERATOR:
                    $alt_permessi = $PARAMETERS['names']['guild_name']['lead'];
                    break;
                case GAMEMASTER:
                    $alt_permessi = $PARAMETERS['names']['master']['sing'];
                    break;
                case MODERATOR:
                    $alt_permessi = $PARAMETERS['names']['moderators']['sing'];
                    break;
                case SUPERUSER:
                    $alt_permessi = $PARAMETERS['names']['administrator']['sing'];
                    break;
            }//else
            //Livello di accesso del PG (utente, master, admin, superuser)
            echo '<td class="colonna_presenti"><img class="presenti_ico" src="imgs/icons/permessi'.$record['permessi'].'.gif" alt="'.gdrcd_filter('out', $alt_permessi).'" title="'.gdrcd_filter('out', $alt_permessi).'" /></td>';
            
            echo '<td class="colonna_presenti"> <a href="main.php?page=messages_center&op=create&destinatario='. $record['nome'] .'"><img class="fama_ico" src="../themes/advanced/imgs/scheda/pennino.png" alt="Invia Messaggio" /> </a> </td>';
            /** * Parametro di personalizzazione di uno stato online via tooltip
             * @author Blancks
             */
            $online_state = '';
            if ($PARAMETERS['mode']['user_online_state'] == 'ON' && ! empty($record['online_status']) && $record['online_status'] != null) {
                $record['online_status'] = trim(nl2br(gdrcd_filter('in', $record['online_status'])));
                $record['online_status'] = strtr($record['online_status'], ["\n\r" => '', "\n" => '', "\r" => '', '"' => '&quot;']);
                $online_state = $record['online_status'];
            }
            echo '<td class="colonna_presenti">'.$online_state.'</td>';
            echo '</tr>';
            ?>
         
            <?php } //fine while presenti ?>
        <?php echo '</table>';?>
    </div>
</div>
<!-- Chiusura finestra del gioco -->

