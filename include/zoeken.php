<?php
function Zoek($dbh, $q) {
    $Ret = array('PDORetCode'=>0);
    try {
        $prm = array([':qe'=>$q, ':qf'=>$q]);
        $stmt = $dbh->prepare("SELECT * FROM posts WHERE kopje LIKE CONCAT('%', :q1, '%') OR tekst LIKE CONCAT('%', :q2, '%')");
        $stmt->execute([':q1'=>$q, ':q2'=>$q]);
        $ForumData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt2 = $dbh->prepare("SELECT * FROM videos WHERE titel LIKE CONCAT('%', :q1, '%') OR samenvatting LIKE CONCAT('%', :q2, '%')");
        $stmt2->execute([':q1'=>$q, ':q2'=>$q]);
        $VideoData = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        $Ret = array('PDORetCode'=>1, 'data'=>array('VideoData'=>$VideoData, 'ForumData'=>$ForumData));
    } catch(PDOException $e) {
        $Ret = array('PDORetCode'=>0);
    }
    return $Ret;
}
