<?php

/** Homepage
 * Markup e procedure della homepage
 * @author Blancks
 */

/*
 * Includo i Crediti
 */
require 'includes/credits.inc.php';

/*
 * Conteggio utenti online
 */
$users = gdrcd_query("SELECT COUNT(nome) AS online FROM personaggio WHERE ora_entrata > ora_uscita AND DATE_ADD(ultimo_refresh, INTERVAL 4 MINUTE) > NOW()");


?>
<div id="main">
    <div id="site_width">
        <header class="w3-header w3-center">
            <h1><?php echo $MESSAGE['homepage']['main_content']['site_title']; ?></h1>
        </header>
       

        <div id="content">
            <div class="sidecontent">
                

                <div class="side_modules">
                    <strong><?php echo $users['online'], ' ', gdrcd_filter('out', $MESSAGE['homepage']['forms']['online_now']); ?></strong>
                </div>

                <div class="side_modules">
                    <?php
                        // Include il modulo di reset della password
                        include (__DIR__ . '/reset_password.inc.php');
                    ?>
                </div>

                <div class="side_modules">
                    <?php
                        // Include le statistiche del sito
                        include (__DIR__ . '/user_stats.inc.php');
                    ?>
                </div>
            </div>

            <div class="content_body">
                <?php
                    gdrcd_load_modules('homepage__'.$MODULE['content']);
                    ?>
            </div>
            <br class="blank"/>
        </div>

        <div id="w3-footer">
            <div>
                <p><?=$REFERENCES?></p>
                <p><?=$CREDITS,' ',$LICENCE?></p>
            </div>
        </div>
    </div>
</div>
