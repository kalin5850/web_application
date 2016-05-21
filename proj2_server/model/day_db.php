<?php
function get_system_day() {
    global $db;
    $query = 'SELECT dayNumber FROM systemday ';
    $statement = $db->prepare($query);
    $statement->execute();
    $currentday = $statement->fetch();
    $statement->closeCursor();
    $current_day = $currentday['dayNumber'];
    //$current_day = date('Y-m-d H:i:s');
    return $current_day;
}

function update_system_day($day){
    global $db;
    $query = "update systemday set dayNumber=:day ";
    $statement = $db->prepare($query);
    $statement->bindValue(':day',$day);
    $statement->execute();
    $statement->closeCursor();
}

?>