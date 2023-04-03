<?php
$pg = gdrcd_filter('out', $_REQUEST['pg']);
$me = gdrcd_filter('out', $_SESSION['login']);
$permessi = gdrcd_filter('out', $_SESSION['permessi']);

# Modifica
if (($pg == $me) || ($permessi >= GUILDMODERATOR)) {
    ?>
    <a class="w3-bar-item" href="main.php?page=scheda_modifica&pg=<?= $pg; ?>">
        <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['menu']['update']); ?>
    </a>
<?php } ?>

<a class="w3-bar-item" href="main.php?page=scheda_tratti&pg=<?= $pg; ?>">
    Tratti Caratteriali
</a>
<a class="w3-bar-item" href="main.php?page=scheda_abilita&pg=<?= $pg; ?>">
    Abilità
</a>
<!-- OGGETTI -->
<a class="w3-bar-item" href="main.php?page=scheda_oggetti&pg=<?= $pg; ?>">
    <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['menu']['inventory']); ?>
</a>

<!-- INVENTARIO -->
<a class="w3-bar-item" href="main.php?page=scheda_equip&pg=<?= $pg; ?>">
    <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['menu']['equipment']); ?>
</a>

<!-- Descrizione e Storia separate dalla pagina principale della scheda -->
<a class="w3-bar-item" href="main.php?page=scheda_descrizione&pg=<?= $pg; ?>">
    <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['menu']['detail']); ?>
</a>

<!-- TRASFERIMENTI -->
<a class="w3-bar-item" href="main.php?page=scheda_trans&pg=<?= $pg; ?>">
    <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['menu']['transictions']); ?>
</a>

<!-- ESPERIENZA -->
<a class="w3-bar-item" href="main.php?page=scheda_px&pg=<?= $pg; ?>">
    <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['menu']['experience']); ?>
</a>


<!-- DIARIO -->
<?php if (defined('PG_DIARY_ENABLED') and PG_DIARY_ENABLED) { ?>
    <a class="w3-bar-item" href="main.php?page=scheda_diario&pg=<?= $pg; ?>">
        <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['menu']['diary']); ?>
    </a>
<?php } ?>

<!-- ROLES -->
<?php if (( ($permessi >= ROLE_PERM) || ($pg == $me) ) && REG_ROLE) { ?>
    <a class="w3-bar-item" href="main.php?page=scheda_roles&pg=<?= $pg; ?>">
        Giocate registrate
    </a>
<?php } ?>

<!-- Se maggiore di moderatore -->
<?php if ($permessi >= MODERATOR) { ?>

    <!-- LOG -->
    <a class="w3-bar-item" href="main.php?page=scheda_log&pg=<?= $pg; ?>">
        <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['menu']['log']); ?>
    </a>

    <!-- AMMINISTRA -->
    <a class="w3-bar-item" href="main.php?page=scheda_gst&pg=<?= $pg; ?>">
        <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['menu']['gst']); ?>
    </a>
<?php
}