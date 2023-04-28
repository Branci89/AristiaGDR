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
        
        <div id="content">
            
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
