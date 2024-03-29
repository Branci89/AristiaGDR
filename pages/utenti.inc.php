<div class="pagina_uffici">
    <div class="page_title">
        <div class="link_menu_presenti"><?php echo gdrcd_filter('out', $PARAMETERS['user_page_name']); ?></div>
    </div>
    <div class="page_body_uffici">
        <?php
        /* Generazione automatica del menu del gioco */
        foreach ($PARAMETERS['user'] as $link_menu)
        {
            if ((empty($link_menu['url']) === false) &&
                (empty($link_menu['text']) === false) &&
                (isset($link_menu['access_level']) === true) &&
                ($link_menu['access_level'] <= $_SESSION['permessi'])) {

                echo '<div class="link_menu_presenti">';
                if (empty($link_menu['image_file']) === false) {
                    echo '<img src="' . $link_menu['image_file'] . '" class "link_menu_point" />';
                }
                echo '<a href="' . $link_menu['url'] . '">' . gdrcd_filter('out', $link_menu['text']) . '</a></div>';
            }//if
        }//foreach
        ?>
    </div>
</div>
<?php /*HELP: Il menu viene generato automaticamente attingendo dalle informazioni contenute in config.inc.php. La versione supporta link testuali ed immagini e può essere modificata direttamente nel file config.ing.php, impostando url di destinazione, testo e selezionado le immagini. Se il link è un'immagine il testo viene interpretato automaticamente come testo alternativo all'immagine. Per realizzare un menu di altro tipo suggeriamo di commentare o cancellare il contenuto di questa pagina e sostituirlo con il codice del nuovo menu. */ ?>
