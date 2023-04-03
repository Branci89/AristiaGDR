
<section style="border: 2px solid black; border-radius: 50%; padding: 50px 20px 20px 20px; height: 500px; width: 500px">
    <div class="w3-row" style="width: 70%; margin: auto;">
        <div class="w3-half w3-container">
            <p><a href="index.php?page=homepage&content=iscrizione"><?php echo $MESSAGE['homepage']['registration']; ?></a></p>
            <p><a href="index.php?page=homepage&content=user_regolamento"><?php echo $MESSAGE['homepage']['rules']; ?></a></p>
        </div>

        <div class="w3-half w3-container">
            <p><a href="index.php?page=homepage&content=user_ambientazione"><?php echo $MESSAGE['homepage']['storyline']; ?></a></p>
            <p><a href="index.php?page=homepage&content=user_razze"><?php echo $MESSAGE['homepage']['races']; ?></a></p>
        </div>
        <div>

        </div>
        
    </div>
    <div class="login_form">
        <form action="login.php" id="do_login" method="post"
        <?php
        if ($PARAMETERS['mode']['popup_choise'] == 'ON') {
            echo ' onsubmit="check_login(); return false;"';
        }
        ?>
              >
            <div>
                <span class="form_label"><label for="username"><?php echo $MESSAGE['homepage']['forms']['username']; ?></label></span>
                <input type="text" id="username" name="login1"/>
            </div>
            <div>
                <span class="form_label"><label for="password"><?php echo $MESSAGE['homepage']['forms']['password']; ?></label></span>
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
                    <span class="form_label"><label for="allow_popup"><?php echo $MESSAGE['homepage']['forms']['open_in_popup']; ?></label></span>
                    <input type="checkbox" id="allow_popup"/>
                    <input type="hidden" value="0" name="popup" id="popup">
                </div>
            <?php } ?>
            <input type="submit" value="<?php echo $MESSAGE['homepage']['forms']['login']; ?>"/>
        </form>
    </div>
</section>