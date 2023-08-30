<?php
//Includio i parametri, la configurazione, la lingua e le funzioni
require ('includes/required.php');

//Eseguo la connessione al database
$handleDBConnection = gdrcd_connect();

/** * Aggiorno l'ora di uscita del pg
 * @author Blancks
 */
gdrcd_query("UPDATE personaggio SET ora_uscita = NOW() WHERE nome='" . gdrcd_filter('in', $_SESSION['login']) . "'");
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content='text/html; charset=utf-8'>
        <link rel="stylesheet" href="themes/<?php echo $PARAMETERS['themes']['current_theme']; ?>/main.css" type='text/css'>
        <link rel="shortcut icon" href="imgs/favicon.ico"/>
    </head>
    <body class="logout_body">
        <div class="logout_box">
            <span class="logout_text"><?php echo gdrcd_filter('out', $_SESSION['login']) . ' ' . $MESSAGE['logout']['confirmation']; ?></span>
            <div class="logout_message">La tua leggenda attendera` il tuo ritorno!</div>
            <div class="logout_image_box">
                <div class="logout_text">
                    <a class="link_home_personal" href="index.php">
                        HOMEPAGE
                    </a>
                </div> 
            </div>

            <span class="logout_text"><?php echo gdrcd_filter('out', $MESSAGE['logout']['greeting']); ?></span>
        </div>
    </body>
</html>
<?php
/* Chiudo la connessione al database */
gdrcd_close_connection($handleDBConnection);

/** * Per ottimizzare le risorse impiegate le liberiamo dopo che non ne abbiamo più bisogno
 * @author Blancks
 */
unset($MESSAGE);
unset($PARAMETERS);

session_unset();
session_destroy();
?>
