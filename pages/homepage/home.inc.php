
<section class="box_home_aristia">
    <div>
        <div class="w3-container" style="display: flex; justify-content: space-evenly;">
            <p class="black_sfondo"><a class="tasti_home" href="index.php?page=homepage&content=iscrizione"><?php echo $MESSAGE['homepage']['registration']; ?></a></p>
            <p class="black_sfondo"><a class="tasti_home" href="index.php?page=homepage&content=user_regolamento"><?php echo $MESSAGE['homepage']['rules']; ?></a></p>
        </div>


    </div>
    <div class="login_form">
        <form class="login-box" action="login.php" id="do_login" method="post" 
        <?php
        if ($PARAMETERS['mode']['popup_choise'] == 'ON') {
            echo ' onsubmit="check_login(); return false;"';
        }
        ?>>
            <div>
                <div>
                    <span class="form_label"><label class="tasti_home" for="username"><?php echo $MESSAGE['homepage']['forms']['username']; ?></label></span>
                    <input type="text" id="username" name="login1"/>
                </div>
                <div>
                    <span class="form_label"><label class="tasti_home" for="password"><?php echo $MESSAGE['homepage']['forms']['password']; ?></label></span>
                    <input type="password" id="password" name="pass1"/>
                </div>
                <?php if (!empty($PARAMETERS['themes']['available']) and count($PARAMETERS['themes']['available']) > 1): ?>
                    <div>
                        <span class="form_label"><label for="theme"><?= gdrcd_filter('out', $MESSAGE['homepage']['forms']['theme_choice']) ?></label></span>
                        <select name="theme" id="theme">
                            <?php
                            foreach ($PARAMETERS['themes']['available'] as $k => $name) {
                                echo '<option value="' . gdrcd_filter('out', $k) . '"';
                                if ($k == $PARAMETERS['themes']['current_theme']) {
                                    echo ' selected="selected"';
                                }
                                echo '>' . gdrcd_filter('out', $name) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                <?php endif; ?>
                <?php if ($PARAMETERS['mode']['popup_choise'] == 'ON') { ?>
                    <div>
                        <span class="form_label"><label  for="allow_popup"><?php echo $MESSAGE['homepage']['forms']['open_in_popup']; ?></label></span>
                        <input type="checkbox" id="allow_popup"/>
                        <input type="hidden" value="0" name="popup" id="popup">
                    </div>
                <?php } ?>
            </div>
            <span class="sfondo_login"><input class="login_button_aristia" type="submit" value="<?php echo $MESSAGE['homepage']['forms']['login']; ?>"/></span>
        </form>

    </div>
    <div class="w3-container" style="display: flex; justify-content: space-evenly; margin-top:20px;">
        <p class="black_sfondo"><a class="tasti_home" href="index.php?page=homepage&content=user_ambientazione"><?php echo $MESSAGE['homepage']['storyline']; ?></a></p>
        <p class="black_sfondo"><a class="tasti_home" href="index.php?page=homepage&content=user_razze"><?php echo $MESSAGE['homepage']['races']; ?></a></p>
    </div>

    <div style="margin-top: 20px;">
        <?php
        // Include il modulo di reset della password
        include (__DIR__ . '/reset_password.inc.php');
        ?>
    </div>
</section>