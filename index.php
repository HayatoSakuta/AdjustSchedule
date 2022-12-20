<!-- DBへ接続する処理 -->
<?php
require('dbconnect.php');
$dbh = db_connect();
if(!empty($_GET['id'])){
    $id = $_GET['id'];
}else{
    $id = 10;
}
if(!empty($_GET['scheid'])){
    $scheid = $_GET['scheid'];
}else{
    $scheid = 7;
}
if(!empty($_GET['scheaction'])){
    $scheaction = $_GET['scheaction'];
}
$sql_event = "SELECT * FROM event";
$sql_schedule = "SELECT * FROM schedule where fk_event_id = $id";
$sql_event_target = "SELECT eventname FROM event where pk_event_id = $id";
$sql_schedule_target = "SELECT * FROM schedule where pk_schedule_id = $scheid";
$sth_event = $dbh->query($sql_event);
$sth_schedule = $dbh->query($sql_schedule);
$sth_event_target = $dbh->query($sql_event_target);
$sth_schedule_target = $dbh->query($sql_schedule_target);
$eventname_target = $sth_event_target->fetch(PDO::FETCH_COLUMN);
$date_target = $sth_schedule_target->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>イベント管理</title>
        <link rel="stylesheet" href="stylesheet.css">
    </head>
    <body>
        <header>
            <h1>日程調整くん</h1>
        </header>

        <div id="content">

            <div id="left">

                <div id="title_left">
                    <h2>イベント一覧</h2>
                    <a href="mkevent.php"><img src="icon/add.png" alt="イベント追加画面へ" id="addevent" class="imglink"></a>
                </div>
                
                <?php
                while($table = $sth_event->fetch(PDO::FETCH_ASSOC)){
                ?>
                <div class="event">
                    
                    <?php
                    if($table['pk_event_id']==$id){
                        echo '<a href="index.php?id='.$table['pk_event_id'].'" id="activeevent" class="eventlink">';
                    }else{
                        echo '<a href="index.php?id='.$table['pk_event_id'].'" class="eventlink">';
                    }
                    ?>
                        <?php print(htmlspecialchars($table ['eventname'])); ?>
                    </a>

                    <?php
                    echo '<img src="icon/arrow_forward.png" alt="イベント詳細を開く" class="arrow_forward">';
                    ?>

                </div>
                <?php
                }
                ?>

            </div>
            
            <div id="right">

                <div id="title_right">
                    <h2>詳細</h2>
                </div>

                <div id="eventdetail">

                    <div id="eventdetail_title">
                        <h3>
                            <?php print(htmlspecialchars($eventname_target));?>
                        </h3>
                        <?php
                        echo '<a href="delete_event.php?id='.$id.'"><img src="icon/delete.png" alt="イベント削除" id="delete_event" class="imglink"></a>';
                        ?>
                    </div>

                    <div id="scheduletable">

                        <table id="table">
                            <tr>
                                <th>　日程</th>
                                <th>申込者</th>
                            </tr>
                            <?php
                            while($table = $sth_schedule->fetch(PDO::FETCH_ASSOC)){;
                            ?>
                            <tr class="schedule">
                                <?php
                                $week = array('日', '月', '火', '水', '木', '金', '土');
                                $date = new DateTime($table ['date']);
                                $time_start = new DateTime($table ['time_start']);
                                $time_end = new DateTime($table ['time_end']);
                                $w = $date->format('w');
                                $applicant = $table ['applicant'];
                                echo '<td class="datetime">・'.$date->format('n/j').'（'.$week[$w].'）'.$time_start->format('G:i').'〜'.$time_end->format('G:i').'</td>';
                                if(isset($applicant)){
                                    echo '<td class="applicant">'.$applicant.'</td>';
                                }else{
                                    echo '<td class="applicant_yet">未定</td>';
                                }
                                ?>
                                <td class="td_edit_schedule">
                                    <?php echo '<a href="index.php?id='.$id.'&scheid='.$table['pk_schedule_id'].'&scheaction=edit#modal-editschedule">'; ?>
                                        <img src="icon/edit.png" alt="日程編集" class="edit_schedule">
                                    </a>
                                </td>
                                <td class="td_delete_schedule">
                                    <?php echo '<a href="delete_schedule.php?id='.$id.'&scheid='.$table['pk_schedule_id'].'">'; ?>
                                        <img src="icon/delete.png" alt="日程削除" class="delete_schedule">
                                    </a>
                                </td>
                            </tr>
                            <?php
                            }
                            ?>
                            <tr id="tr_addschedule">
                                <td colspan="4" id="td_addschedule">
                                    <?php
                                    echo '<a href="index.php?id='.$id.'&scheaction=add#modal-editschedule" id="addschedule">';
                                    ?>
                                    　＋日程を追加する 　
                                    </a>
                                </td>
                            </tr>
                        </table>
                        
                        <!--　ここからモーダルウインドウに関するコード　日程編集ボタンにモーダル機能を仕込んでいる-->
                        <div class="modal-wrapper" id="modal-editschedule">
                            <?php echo '<a href="index.php?id='.$id.'#!" class="modal-overlay"></a>';?>
                            <div class="modal-window">
                                <div class="modal-content">
                                    <?php
                                    if(strcmp($scheaction,"edit")==0){
                                    ?>
                                    <p>日程編集</p>
                                    <?php
                                    echo '<form action="editschedule_do.php?id='.$id.'&scheid='.$scheid.'&scheaction='.$scheaction.'" method="post" class="form_modal">';
                                    ?>
                                        <label>日付<input type="date" name="date" value=<?= $date_target['date'] ?> class="input_modal"></label>
                                        <label>開始時刻<input type="time" name="time_start" value=<?= $date_target['time_start'] ?> class="input_modal"></label>
                                        <label>終了時刻<input type="time" name="time_end" value=<?= $date_target['time_end'] ?> class="input_modal"></label>
                                        <label>申込者<input type="text" name="applicant" value=<?= $date_target['applicant'] ?> class="input_modal"></label>
                                        <input type="submit" value="変更を保存" class="con">
                                    </form>
                                    <?php
                                    }elseif(strcmp($scheaction,"add")==0){
                                    ?>
                                    <p>日程追加</p>
                                    <?php
                                    echo '<form action="editschedule_do.php?id='.$id.'&scheid='.$scheid.'&scheaction='.$scheaction.'" method="post" class="form_modal">';
                                    ?>
                                        <label>日付<input type="date" name="date" class="input_modal"></label>
                                        <label>開始時刻<input type="time" name="time_start" class="input_modal"></label>
                                        <label>終了時刻<input type="time" name="time_end" class="input_modal"></label>
                                        <label>申込者<input type="text" name="applicant" class="input_modal"></label>
                                        <input type="submit" value="追加" class="con">
                                    </form>
                                    <?php
                                    }
                                    ?>
                                    
                                </div>
                            </div>
                        </div>
                        <!--　ここまでモーダルウインドウに関するコード　※モーダル部分だけなら消しても問題なし。-->

                    </div>

                </div>
                
            </div>

        </div>

        <footer>
            <p>製作者：寺田下心</p>
        </footer>

    </body>
</html>