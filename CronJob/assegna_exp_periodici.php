<?php 

require_once('../includes/required.php');
/*Connessione al database*/
$handleDBConnection = gdrcd_connect();
/*Brancix
Questa routine seleziona ed assegna i px periodici dei PG. 
 * Viene lanciata periodicamente dal servizio Cron JOB di altervista.
 * 
 *  */
            //Devo contare solo le azioni con un messaggio più lungo di $PARAMETERS['settings']['valid_action_lengh'] (che si trova nel config.inc.php
            //
            // il limite delle azioni sono di 1200 caratteri, ma i punti li ottieni già a $PARAMETERS['settings']['valid_action_lengh'].
            // L'idea era che raggiungendo un certo numero di azioni si guadagnava un tot di punti exp/gloria ogni due settimane (lo puoi configurare con il config, ma poi va cambiato il CronJob su altervista) con un cap. 
            // Tutte le chat vanno bene. I "pacchetti" di exp arrivano ogni due settimane, divisi in 
            //    1) pacchetto: 10 azioni = 10 exp 
            //    2) pacchetto: 100 azioni = 100 exp 
            //    3) pacchetto: 300 azioni = 200 exp

/* Seleziono i record da un mese fa e raggruppo **/
$queryChat = "SELECT COUNT(*) AS azioni , mittente FROM chat WHERE ora>=DATE_SUB(now(), INTERVAL ".$PARAMETERS['settings']['day_per_exp']." DAY) AND length(testo) > ".$PARAMETERS['settings']['valid_action_lengh']." AND tipo in ('P','A') GROUP BY mittente";
$query = gdrcd_query($queryChat,'result');
$row_count = gdrcd_query($query,'num_rows');
/*Per ogi pg che corrisponde al criterio, assegno i px*/
while($row = gdrcd_query($query,'fetch')){
    
    $pgQry = "SELECT lastExp FROM personaggio WHERE nome = '".$row['mittente']."' LIMIT 1";
    $result = gdrcd_query($pgQry,'query');
    $lastExp = new DateTime($result['lastExp']);
    $oggi = new DateTime("now");
    $diff = $oggi->diff($lastExp)->days;
    if($diff >= $PARAMETERS['settings']['day_per_exp']){
        $exp_package = 0;    
        if($row['azioni'] >= 10 && $row['azioni'] < 100 ){ $exp_package = 10 ;}
        if($row['azioni'] >= 100 && $row['azioni'] < 300 ){ $exp_package = 100 ;}
        if($row['azioni'] >= 300  ){ $exp_package = 200 ;}
        $queryUpdate = "UPDATE personaggio set esperienza = esperienza + ".$exp_package." , lastExp = NOW() WHERE nome = '".gdrcd_filter('in', $row['mittente'])."' LIMIT 1";
        gdrcd_query($queryUpdate);
        if($exp_package > 0 ){
        $queryAggiornaTabella = "INSERT INTO log(nome_interessato,autore,data_evento,codice_evento,descrizione_evento) VALUES ('".$row['mittente']."' ,'SistemaEXP',NOW(), '82' , 'Consegnato pacchetto: ".$exp_package." EXP')";
        }else{
            $queryAggiornaTabella = "INSERT INTO log(nome_interessato,autore,data_evento,codice_evento,descrizione_evento) VALUES ('".$row['mittente']."' ,'Sistema',NOW(), '82' , 'Il Pg soddisfa i parametri per exp')";
        }
    gdrcd_query($queryAggiornaTabella);
    
    }
}
 echo 'fine job :)' ;
?>