<!-- Trattti caratteriali -->

<div class="pagina_scheda" >
    <header class="w3-header w3-center">
        <h2>TRATTI CARATTERIALI</h2>
        <?php
        if (isset($_POST['submit']) && $_POST['op'] == 'point') {
            $values = [];
            $update_part = "";
            $result = gdrcd_query("SELECT DISTINCT id_tratto from anag_tratti ORDER BY id_tratto", 'result');
            while ($row = gdrcd_query($result, 'fetch')) {
                $values [] = (int) gdrcd_filter('num', $_POST[$row['id_tratto']]);
                //già che ci sono mi costruisco il pezzo della query e ottimizziamo il ciclo
                $update_part .= $row['id_tratto'] . '=' . $row['id_tratto'] . '+' . gdrcd_filter('num', $_POST[$row['id_tratto']]) . ",";
            }

            $point = gdrcd_query("SELECT da_spendere FROM tratti WHERE personaggio='" . gdrcd_filter('in', $_POST['pg']) . "' ");
            /*TODO il livello ADMIN e superiore, deve poter aggiungere tutto quello che vuole, infischiandosene del residuo*/
            if ((int) $point['da_spendere'] >= array_sum($values) || $_SESSION['permessi']>=ADMIN) {
                //Qui quindi aggiorniamo le statistiche con i punticini, guardiamo che privilegi abbiamo.
                $query = '';
                if($_SESSION['permessi']>= MODERATOR){
                    $query = "UPDATE tratti SET " . $update_part . " da_spendere = da_spendere-0 WHERE personaggio='".$_POST['pg']."'";
                }else{
                    $query = "UPDATE tratti SET " . $update_part . " da_spendere = da_spendere-" . array_sum($values) . " WHERE personaggio='" . $_POST['pg'] . "'";
                }
                gdrcd_query($query, 'query');
            } else {
                echo 'La somma dei punti distribuiti deve essere ' . $point['da_spendere'] . '!';
            }
        }
        elseif (isset($_POST['assegna']) && $_POST['op'] == 'assign') {
            $query="INSERT INTO clgpassionipersonaggio (personaggio,id_passione,valore,verso_chi) VALUES ('".gdrcd_filter('out', $_POST['nomePg'])."' , ".gdrcd_filter('num', $_POST['idPassione']).", ".gdrcd_filter('num', $_POST['valore']).", '".gdrcd_filter('out', $_POST['versoChi'])."' )";
            gdrcd_query($query); ?>
            <div class="warning">
                    <?php echo gdrcd_filter('out', $MESSAGE['warning']['inserted']); ?>
                </div>
                <!-- Link di ritorno alla visualizzazione di base -->
                <div class="link_back">
                    <a href="main.php?page=scheda_tratti&pg=<?php echo $_POST['nomePg']; ?>" >
                        <?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['skills']['link']['back']); ?>
                    </a>
                </div>
       <?php } ?>
    </header>

    <div class="w3-row w3-padding w3-border" >
        <?php
        $result = gdrcd_query("SELECT DISTINCT id_tratto from anag_tratti ORDER BY id_tratto", 'result');

        $tratti = gdrcd_query("SELECT * FROM tratti WHERE personaggio='" . $_REQUEST['pg'] . "' ");
        echo '<div class="w3-twothird">';
        while ($row = gdrcd_query($result, 'fetch')) {
            $anag_tratto = gdrcd_query("SELECT * FROM anag_tratti WHERE id_tratto='" . $row['id_tratto'] . "' ");
            echo ''
            . '<div class="tratti_voce_label w3-quarter">' . $anag_tratto['nome_tratto'] . '</div>'
            . '<div class="tratti_voce w3-rest">'
            . ' <div class="tratti_voce_label w3-third">' . $anag_tratto['nomeBasso'] . ' (' . $anag_tratto['valoreBasso'] . ') </div>'
            . ' <div class="valore_tratto w3-third">' . $tratti[$row['id_tratto']] . '/20</div>'
            . ' <div class="tratti_voce_label w3-third">' . $anag_tratto['nomeAlto'] . ' (' . $anag_tratto['valoreAlto'] . ') </div>'
            . '</div>'
            . '';
        }
        $result = gdrcd_query("SELECT pa.id_passione as id_passione, pa.nome as nome, clg.valore as valore  FROM clgpassionipersonaggio as clg LEFT JOIN passioni as pa ON clg.id_passione = pa.id_passione WHERE personaggio='" . $_REQUEST['pg'] . "' AND comune = 1", 'result');
        echo '</div>';
        echo '<div class="w3-third" style="border-left: 2px solid grey">';
        echo '<h3>Passioni</h3>';
        while ($row = gdrcd_query($result, 'fetch')) {
            echo '<div class="w3-row"><div class="w3-half">' . $row['nome'] . '</div><div>' . $row['valore'] . '</div></div>';
        }
        gdrcd_query($result,'free');
        
        $result = gdrcd_query("SELECT pa.id_passione as id_passione, pa.nome as nome, clg.valore as valore, clg.verso_chi  FROM clgpassionipersonaggio as clg LEFT JOIN passioni as pa ON clg.id_passione = pa.id_passione WHERE personaggio='" . $_REQUEST['pg'] . "' AND comune = 0", 'result');
        
        echo '<h3>Passioni Speciali</h3>';
        echo '<div class="w3-row"><div class="w3-third">Nome Passione</div><div class="w3-third">Valore</div> <div class="w3-third">Verso Chi</div></div>';
        while ($row = gdrcd_query($result, 'fetch')) {
            echo '<div class="w3-row"><div class="w3-third">' . $row['nome'] . '</div><div class="w3-third">' . $row['valore'] . '</div> <div class="w3-third">'.$row['verso_chi'].'</div></div>';
        }
        echo '</div>';
        gdrcd_query($result,'free');
        ?>
    </div>

    <?php if (($tratti['da_spendere'] > 0 && $_REQUEST['pg'] == $_SESSION['login']) || $_SESSION['permessi'] >= MODERATOR) { ?>

        <div style="padding: 25px;">
            <header class="w3-header">
                <h3>Distribuzione dei Tratti Caratteriali</h3>
            </header>
            <details>
                <summary>Espandi</summary>
                <div class="w3-border">

                    <form method="post" action="main.php?page=scheda_tratti&pg=<?php echo gdrcd_filter('url', $_REQUEST['pg']); ?>">
                        <header>
                            <h3 style="color: #2f8e04"> Hai <?php echo $tratti['da_spendere'] ?> da distribuire</h3>
                            <?php if($_SESSION['permessi']>=MODERATOR){echo '<h5>DA GRANDI POTERI DERIVANO GRANDI RESPONSABILITA\'</h5>';}?>
                        </header>
                        <div style="display: grid;grid-template-columns: repeat(3, minmax(0, 1fr));">
                            <?php
                            $result = gdrcd_query("SELECT DISTINCT id_tratto from anag_tratti ORDER BY id_tratto", 'result');
                            while ($row = gdrcd_query($result, 'fetch')) {
                                $anag_tratto = gdrcd_query("SELECT * FROM anag_tratti WHERE id_tratto='" . $row['id_tratto'] . "'");
                                echo '<div class="riga_tratto">' . $anag_tratto['nome_tratto'] . ': ' . $tratti[$row['id_tratto']] . ' + <input class="tratti_casella" style="width: 80px" name="' . $row['id_tratto'] . '" type="number" value="0"></div>';
                            }
                            ?>
                        </div>
                        <div class='form_submit'>
                            <input type="hidden" name="op" value="point" />
                            <input type="hidden" name="pg" value="<?php echo gdrcd_filter('out', $_REQUEST['pg']); ?>" />
                            <input type="submit" name="submit" value="<?php echo $MESSAGE['interface']['forms']['submit']; ?>" class="form_submit"/>
                        </div>
                    </form>
                </div>
            </details>
        </div>

<?php } ?>
    <?php if ($_SESSION['permessi'] >= MODERATOR) { ?>
        <section>
        <?php
        $query = "SELECT nome FROM personaggio WHERE permessi < " . SUPERUSER . " ORDER BY nome";
        $result = gdrcd_query($query, 'result');
        ?>

            <h3>Assegna una passione Speciale</h3>
            <details>
            <summary>Espandi</summary>  
            <form action="main.php?page=scheda_tratti&pg=<?php echo $_REQUEST['pg']; ?>" method="post" >
                <div class="form_label">
                    Passione
                </div>
                <div class="form_field">
                    <?php
                    $query = "SELECT id_passione, nome FROM passioni WHERE comune = 0 and id_passione NOT IN (SELECT id_passione from clgpassionipersonaggio where personaggio ='".gdrcd_filter('out', $_REQUEST['pg'])."') ORDER BY nome";
                    $result = gdrcd_query($query, 'result');
                    ?>
                    <select name="idPassione">
                        <option disabled selected> Passione </option>
                        <?php while ($row = gdrcd_query($result, 'fetch')) { ?>
                            <option value="<?php echo $row['id_passione']; ?>"><?php echo $row['nome']; ?></option>
                        <?php
                        }//while
                        gdrcd_query($result, 'free');
                        ?>
                    </select>

                </div>
                <div class="form_label">
                    Personaggio
                </div>
                <div class="form_field">
                    <?php
                    $query = "SELECT nome FROM personaggio WHERE permessi < " . SUPERUSER . " AND nome != '" . $_SESSION['login'] . "' ORDER BY nome";
                    $result = gdrcd_query($query, 'result');
                    ?>
                    <select name="versoChi">
                        <option disabled selected> Verso quale PG  </option>
                        <?php while ($row = gdrcd_query($result, 'fetch')) { ?>
                            <option value="<?php echo $row['nome']; ?>"><?php echo $row['nome']; ?></option>
                        <?php
                        }//while
                        gdrcd_query($result, 'free');
                        ?>
                    </select>
                </div>
                <div class="form_label">
                    Valore Passione
                </div>
                <div class="form_field">
                    <input name="valore" type="number" min="0" max="20">
                </div>
                <div class="form_submit">
                    <input type="hidden" name="op" value="assign" />
                    <input type="hidden" name="nomePg" value="<?php echo $_REQUEST['pg'] ?>" />
                    <input type="submit" name="assegna" value="<?php echo gdrcd_filter('out', $MESSAGE['interface']['user']['pass']['submit']['user']); ?>" />
                </div>
            </form>
          </details>
        </section>   
<?php } ?>
    <div class="link_back" >
        <a href="main.php?page=scheda&pg=<?php echo gdrcd_filter('url', $_REQUEST['pg']); ?>"> <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['link']['back']); ?> </a>
    </div>
</div>


