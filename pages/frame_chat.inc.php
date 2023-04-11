<?php
/* HELP: Frame della chat */
/* Tipi messaggio: (A azione, P parlato, N PNG, M Master, I Immagine, S sussurro, D dado, C skill check, O uso oggetto) */

/* Seleziono le info sulla chat corrente */
$info = gdrcd_query("SELECT nome, stanza_apparente, invitati, privata, proprietario, scadenza FROM mappa WHERE id=" . $_SESSION['luogo'] . " LIMIT 1");
?>
<div class="pagina_frame_chat">
    <div class="page_title"><h2><?php echo $info['nome']; ?></h2></div>
    <div class="page_body">
        <?php
        // Costruisco il controllore audio
        echo AudioController::build('chat');

        //e' una stanza privata?
        if ($info['privata'] == 1) {
            $allowance = false;

            if ((($info['proprietario'] == gdrcd_capital_letter($_SESSION['login'])) || (strpos($_SESSION['gilda'], $info['proprietario']) != false) || (strpos($info['invitati'], gdrcd_capital_letter($_SESSION['login'])) != false) || (($PARAMETERS['mode']['spyprivaterooms'] == 'ON') && ($_SESSION['permessi'] > MODERATOR))) && ($info['scadenza'] > strftime('%Y-%m-%d %H:%M:%S'))) {
                $allowance = true;
            }
        } else {
            $allowance = true;
        }
        //se e' privata e l'utente non ha titolo di leggerla
        if ($allowance === false) {
            echo '<div class="warning">' . $MESSAGE['chat']['whisper']['privat'] . '</div>';

            //echo $info['invitati']; echo gdrcd_capital_letter($_SESSION['login']);
        } else {
            ?>
    <?php $_SESSION['last_message'] = 0; ?>
            <div style="height: 1px; width: 1px;">
                <iframe src="pages/chat.inc.php?ref=30&chat=yes" class="iframe_chat" id="chat_frame" name="chat_frame" frameborder="0" allowtransparency="true">
                </iframe>
            </div>
            <div id='pagina_chat' class="chat_box">
            </div>
            <div class="panels_box">
                <div class="contenitore-form">
                    <!-- Form messaggi -->
                    <section class="d-flex">
                        <div style="width: 90%">
                           <div class="d-flex"> 
                               <form class="chat_form_branci" action="pages/chat.inc.php?ref=10&chat=yes" method="post" target="chat_frame" id="chat_form_messages">
                                <div class="chat_elements">
                                    <div class="casella_chat tendina-azioni">                                       
                                        <select name="type" id="type">
                                            <option value="0"><?php echo gdrcd_filter('out', $MESSAGE['chat']['type'][0]); //parlato  ?></option>
                                            <option value="1"><?php echo gdrcd_filter('out', $MESSAGE['chat']['type'][1]); //azione  ?></option>
                                            <option value="4"><?php echo gdrcd_filter('out', $MESSAGE['chat']['type'][4]); //sussurro  ?></option>
                                                <?php if ($_SESSION['permessi'] >= GAMEMASTER) { ?>
                                                <option value="2"><?php echo gdrcd_filter('out', $MESSAGE['chat']['type'][2]); //master
                                                    ?></option>
                                                <option value="3"><?php echo gdrcd_filter('out', $MESSAGE['chat']['type'][3]); //png
                                                    ?></option>
                                            <?php } ?>

                                                <?php if (($info['privata'] == 1) && (($info['proprietario'] == $_SESSION['login']) || ((is_numeric($info['proprietario']) === true) && (strpos($_SESSION['gilda'], '' . $info['proprietario']))))) { ?>
                                                <option value="5"><?php echo gdrcd_filter('out', $MESSAGE['chat']['type'][5]); //invita
                                                    ?></option>
                                                <option value="6"><?php echo gdrcd_filter('out', $MESSAGE['chat']['type'][6]); //caccia
                                                    ?></option>
                                                <option value="7"><?php echo gdrcd_filter('out', $MESSAGE['chat']['type'][7]); //elenco
                                                    ?></option>

                                            <?php } //if
                                            ?>

                                        </select>

                                        

                                    </div>

                                    <div class="casella_chat">
                                        <span class="casella_info">
                                            
                                        </span>
                                         
                                        <input name="tag" id="tag" value="" placeholder="<?php
                                            echo gdrcd_filter('out', $MESSAGE['chat']['tag']['info']['tag'] . $MESSAGE['chat']['tag']['info']['dst']);
                                            if ($_SESSION['permessi'] >= GAMEMASTER) {
                                                echo gdrcd_filter('out', $MESSAGE['chat']['tag']['info']['png']);
                                            }
                                            ?>"/>
                                       

                                    </div>

                                    <div class="casella_chat" style="width: 70%;">
                                        <input name="message" id="message" value="" style="width:100% !important;" placeholder="Messaggio/Azione"/>
                                    </div>

                                    <div class="casella_chat" id="inputchat">
                                        <input id="invia" type="submit" value="<?php echo gdrcd_filter('out', $MESSAGE['interface']['forms']['submit']); ?>" />
                                        <input type="hidden" name="op" value="new_chat_message" />
                                    </div>

                                </div>
                            </form>
                            <!-- fine Form messaggi -->
                            <!<!-- Inizio form Punti fato -->
                            <form style="width: 10%; margin-top: 10px; padding: 5px 0px 0px 5px;" action="pages/chat.inc.php?ref=30&chat=yes" method="post" target="chat_frame" id="chat_form_fato">
                                    <div class="casella_chat">
                                        <input class="invio-chat" form="chat_form_fato" type="submit" value="Spendi Punto Fato" />
                                        <input type="hidden" name="op" value="spendi_fato">
                                    </div>
                            </form>
                        </div>
                            <!-- Inizio form dadi -->
                            <div class="dice_form_branci">

                                <?php if (($PARAMETERS['mode']['skillsystem'] == 'ON') || ($PARAMETERS['mode']['dices'] == 'ON')) { ?>
                                     <form  style="width: 60%" action="pages/chat.inc.php?ref=30&chat=yes" method="post" target="chat_frame" id="chat_form_actions">

                                    <?php if ($PARAMETERS['mode']['skillsystem'] == 'ON') { ?>

                                            <div class="casella_chat">
                                            <?php
                                            $categories = gdrcd_query("SELECT distinct categoria FROM abilita WHERE id_razza=-1 OR id_razza IN (SELECT id_razza FROM personaggio WHERE nome = '" . $_SESSION['login'] . "') ", 'result');
                                            ?>
                                            <select name="id_ab" id="id_ab">
                                                <option value="no_skill">Usa Abilità</option>
                                                <?php
                                                while ($categoria = gdrcd_query($categories, 'fetch')) {
                                                    $result = gdrcd_query("SELECT id_abilita, nome, categoria FROM abilita WHERE categoria ='" . $categoria['categoria'] . "' and (id_razza=-1 OR id_razza IN (SELECT id_razza FROM personaggio WHERE nome = '" . $_SESSION['login'] . "')) ORDER BY nome", 'result');
                                                    echo '<optgroup label="' . $categoria['categoria'] . '" >';
                                                    while ($row = gdrcd_query($result, 'fetch')) {
                                                        ?>
                                                        <option value="<?php echo $row['id_abilita']; ?>">
                                                        <?php echo gdrcd_filter('out', $row['nome']); ?>
                                                        </option>
                                                        <?php
                                                    }
                                                    echo '</optgroup>'; //while
                                                }
                                                gdrcd_query($result, 'free');
                                                gdrcd_query($categories, 'free');
                                                ?>

                                            </select>

                                        </div>
                                        <div class="casella_chat">
                                            <?php 
                                            $tratti = gdrcd_query("SELECT DISTINCT id_tratto, nome_tratto from anag_tratti ORDER BY nome_tratto", 'result');
                                            ?>
                                            <select name="tratto_c" id="id_tratto" style="min-width: 150px">
                                                <option value="no_trait">Tratto Caratteriale</option>
                                                <?php 
                                                while($row = gdrcd_query($tratti,'fetch')){
                                                    echo '<option value="'.$row['id_tratto'].'">'.$row['nome_tratto'].'</option>';
                                                }
                                                gdrcd_query($tratti, 'free');
                                                ?>
                                            </select>
                                            
                                        </div>
                                         <!-- Passioni -->
                                         <div class="casella_chat">
                                             <?php
                                             $passioni = gdrcd_query("SELECT pas.nome, clg.id_passione, clg.valore FROM passioni as pas LEFT JOIN clgpassionipersonaggio clg ON pas.id_passione = clg.id_passione WHERE clg.personaggio = '".$_SESSION['login']."' ", 'result');
                                             ?>
                                             <select name="passions" id="id_passione" style="min-width: 100px">
                                                 <option value="no_passion">Passioni</option>
                                                 <?php 
                                                while($row = gdrcd_query($passioni,'fetch')){
                                                    echo '<option value="'.$row['id_passione'].'">'.$row['nome'].'</option>';
                                                }
                                                gdrcd_query($passioni, 'free');
                                                ?>
                                                 
                                             </select>
                                         </div>
                                            <div class="casella_chat">
                                                <select name="id_stats" id="id_stats">
                                                    <option value="no_stats">Usa Caratteristica</option>
                                                    <?php
                                                    /*** Questo modulo aggiunge la possibilità di eseguire prove col dado e caratteristica.
                                                     * Pertanto sono qui elencate tutte le caratteristiche del pg.
                                                     * @author Blancks
                                                     */
                                                    foreach ($PARAMETERS['names']['stats'] as $id_stats => $name_stats) {
                                                        if (is_numeric(substr($id_stats, 3))) {
                                                            ?>
                                                            <option value="stats_<?php echo substr($id_stats, 3); ?>"><?php echo $name_stats; ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                
                                            </div>


                                            <?php
                                        } else {

                                            echo '<input type="hidden" name="id_ab" id="id_ab" value="no_skill">';
                                        }

                                        if ($PARAMETERS['mode']['dices'] == 'ON') {
                                            ?>

                                            <div class="casella_chat">
                                                <select name="dice" id="dice" style="min-width: 60px">
                                                    <option value="no_dice">Dado</option>
                                                    <?php
                                                    /*** Tipi di dado personalizzati da config
                                                     * @author Blancks
                                                     */
                                                    foreach ($PARAMETERS['settings']['skills_dices'] as $dice_name => $dice_value) {
                                                        ?>
                                                        <option value="<?php echo $dice_value; ?>"><?php echo $dice_name; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                                
                                            </div>

                                            <?php
                                        } else {

                                            echo '<input type="hidden" name="dice" id="dice" value="no_dice">';
                                        }

                                        if ($PARAMETERS['mode']['skillsystem'] == 'ON') {
                                            ?>


                                            <?php
                                        } else {

                                            echo '<input type="hidden" name="id_item" id="id_item" value="no_item">';
                                        }
                                        ?>

                                        <div class="casella_chat">
                                            <input class="invio-chat" type="submit" value="Usa!" />
                                            <input type="hidden" name="op" value="take_action">
                                        </div>

                                    </form>




    <?php } ?>

                                <form style="width: 35%" action="pages/chat.inc.php?ref=30&chat=yes" method="post" target="chat_frame" id="chat_form_danno">
                                    <div style="border-left: 2px solid black;">
                                        <div class="casella_chat">
                                            <input style="width: 50px" name="val_difesa" type="number" value="0" min="0" max="100"></input>
                                            </br>
                                            <label aria-label="valore danno">Difesa PG</label>
                                        </div>
                                        <div class="casella_chat">
                                            <input style="width: 50px" name="val_forza" value="0" type="number"  min="0" max="100"></input>
                                            </br>
                                            <label aria-label="valore danno">Forza Atk</label>
                                        </div>
                                    </div>
                                    <div >
                                        <div class="casella_chat">

                                            <?php
                                            $categories = gdrcd_query("SELECT distinct categoria FROM abilita WHERE id_razza=-1 OR id_razza IN (SELECT id_razza FROM personaggio WHERE nome = '" . $_SESSION['login'] . "') ", 'result');
                                            ?>


                                            <select name="id_ab" id="id_ab">

                                                <option value="no_skill"></option>
                                                <?php
                                                while ($categoria = gdrcd_query($categories, 'fetch')) {
                                                    $result = gdrcd_query("SELECT id_abilita, nome, categoria FROM abilita WHERE categoria ='" . $categoria['categoria'] . "' and (id_razza=-1 OR id_razza IN (SELECT id_razza FROM personaggio WHERE nome = '" . $_SESSION['login'] . "')) ORDER BY nome", 'result');
                                                    echo '<optgroup label="' . $categoria['categoria'] . '" >';
                                                    while ($row = gdrcd_query($result, 'fetch')) {
                                                        ?>
                                                        <option value="<?php echo $row['id_abilita']; ?>">
                                                        <?php echo gdrcd_filter('out', $row['nome']); ?>
                                                        </option>
                                                        <?php
                                                    }
                                                    echo '</optgroup>'; //while
                                                }
                                                gdrcd_query($result, 'free');
                                                gdrcd_query($categories, 'free');
                                                ?>

                                            </select>

                                            <br /><span class="casella_info"><?php echo gdrcd_filter('out', $MESSAGE['chat']['commands']['skills']); ?></span>

                                        </div>
                                        <div class="casella_chat">
                                            <input style="width: 50px" name="val_bonus" type="number" min="0" max="100"></input>
                                            </br>
                                            <label aria-label="valore danno">Bonus</label>
                                        </div>
                                    </div>
                                    <div class="casella_chat">
                                        <input class="invio-chat" form="chat_form_danno" type="submit" value="Attacca" />
                                        <input type="hidden" name="op" value="calcola_attacco">
                                    </div>
                                </form>
                                
                            </div>
                        </div>

                        <div class="save_elements">
                            <div class="casella_chat" id="salva-chat">

    <?php if ($PARAMETERS['mode']['chatsave'] == 'ON') { ?>

                                    <span class="casella_info salva">
                                        <a href="javascript:void(0);" onClick="window.open('chat_save.proc.php', 'Log', 'width=750,height=400,top=200, left=420,toolbar=no');">
                                            Salva Chat
                                        </a>
                                    </span>

                                    <?php
                                }

                                if (REG_ROLE) {
                                    ?>
                                <p><a href="javascript:parent.modalWindow('rolesreg', '', 'popup.php?page=chat_pannelli_index&pannello=segnalazione_role');">
                                        Registra Giocata
                                    </a></p>
    <?php } ?>
                            </div>
                        </div>

                    </section>
                </div>

            <?php } //else
            ?>

        </div>

    </div>
    <!-- Page-Body -->
</div><!-- Pagina -->

