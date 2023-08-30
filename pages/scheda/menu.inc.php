<?php
$pg = gdrcd_filter('out', $_REQUEST['pg']);
$me = gdrcd_filter('out', $_SESSION['login']);
$permessi = gdrcd_filter('out', $_SESSION['permessi']);

?>


<div class="menu_scheda_interno">

    <a class="w3-bar-item tasto_menu_perg" href="main.php?page=scheda_tratti&pg=<?php echo $pg; ?>">
        Tratti Carat.
    </a>
    <a class="w3-bar-item tasto_menu_perg" href="main.php?page=scheda_abilita&pg=<?php echo $pg; ?>">
        Abilità
    </a>
    <!-- OGGETTI -->
    <a class="w3-bar-item tasto_menu_perg" href="main.php?page=scheda_oggetti&pg=<?php echo $pg; ?>">
        <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['menu']['inventory']); ?>
    </a>

       
    <!-- Descrizione e Storia separate dalla pagina principale della scheda -->
    <a class="w3-bar-item tasto_menu_perg" href="main.php?page=scheda_descrizione&pg=<?php echo $pg; ?>">
        <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['menu']['detail']); ?>
    </a>

    <!-- TRASFERIMENTI -->
    <a class="w3-bar-item tasto_menu_perg" href="main.php?page=scheda_trans&pg=<?php echo $pg; ?>">
        <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['menu']['transictions']); ?>
    </a>

    <!-- ESPERIENZA -->
    <a class="w3-bar-item tasto_menu_perg" href="main.php?page=scheda_px&pg=<?php echo $pg; ?>">
        <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['menu']['experience']); ?>
    </a>


    <!-- DIARIO -->
    <?php if (defined('PG_DIARY_ENABLED') and PG_DIARY_ENABLED) { ?>
        <a class="w3-bar-item tasto_menu_perg" href="main.php?page=scheda_diario&pg=<?php echo $pg; ?>">
            <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['menu']['diary']); ?>
        </a>
    <?php } ?>

    <!-- ROLES -->
    <?php if (( ($permessi >= ROLE_PERM) || ($pg == $me) ) && REG_ROLE) { ?>
        <a class="w3-bar-item tasto_menu_perg" href="main.php?page=scheda_roles&pg=<?php echo $pg; ?>">
            Registro Role
        </a>
    <?php } ?>
</div>