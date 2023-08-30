<div style="text-align: center; margin-top: 50px;">
<?php
$query = "SELECT nome_cultura FROM personaggio AS pg LEFT JOIN cultura AS ct ON pg.id_cultura=ct.id_cultura WHERE pg.nome = '".gdrcd_filter('in',$_SESSION['login'])."'";
$result = gdrcd_query($query, 'result');
$record = gdrcd_query($result, 'fetch');
gdrcd_query($result, 'free');

if (empty($record['nome_cultura']) === false) { ?>
<img class="avatar_scheda_img" src="../imgs/culture/<?php echo $record['nome_cultura'].'_ico.png'; ?>" />
<?php }
else if (empty($record['url_img_chat']) === true) {
echo '<img class="avatar_scheda_img" src="imgs/avatars/empty.png" />';
}				
?>
</div>