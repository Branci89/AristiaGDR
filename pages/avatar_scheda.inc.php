<div style="text-align: center; margin-top: 50px;">
<?php
$query = "SELECT personaggio.url_img_chat, nome FROM personaggio WHERE nome = '".gdrcd_filter('in',$_SESSION['login'])."'";
$result = gdrcd_query($query, 'result');
$record = gdrcd_query($result, 'fetch');
gdrcd_query($result, 'free');

if (empty($record['url_img_chat']) === false) {
echo '<a title="Scheda" href="main.php?page=scheda&pg=' . $_SESSION['login'] . ' "><img class="avatar_scheda_img" src='.$record['url_img_chat'].'></a>';
}
else if (empty($record['url_img_chat']) === true) {
echo '<a title="Scheda" href="main.php?page=scheda&pg=' . $_SESSION['login'] . '"><img class="avatar_scheda_img" src="imgs/avatars/empty.png"></a>';
}				
?>
</div>