<html>
<head>
  <title>HackUPC - Baggage</title>
  <link rel="stylesheet" href="assets/css/main.css">
  <?php include "assets/functions/functions.php" ?>
  <script src="assets/js/form.js" type="text/javascript"></script>
  <script src="https://code.jquery.com/jquery-1.9.1.min.js" type="text/javascript"></script>
</head>
<body>
  <div id="main-wrap">
    <div id="user-wrap">
      <div class="user-tab">
        <button class="user-tabs" onclick="openTab(event, 'user-checkin')">Check-in</button>
        <button class="user-tabs" onclick="openTab(event, 'user-list')">List</button>
        <button class="user-tabs" onclick="openTab(event, 'user-history')">History</button>
        <?php
          if(isset($_GET['rem_row'])&&isset($_GET['rem_col'])){
            ?>
              <button class="user-tabs" onclick="openTab(event, 'user-details')">Details</button>
            <?php
          }
        ?>
      </div>
      <div id="user-checkin" class="user-content" <?php if(!(isset($_GET['rem_row'])&&isset($_GET['rem_col']))){ echo 'style="display: block;"'; } ?>>
        <form method="post" id="reg_form" action="" onsubmit="return verifyForm();">
          <div>
            <h2>Baggage check-in</h2>
          </div>
          <div>
            <input type="text" id="reg_id" name="reg_id" placeholder="DNI/Passport">
            <input type="text" id="reg_name" name="reg_name" placeholder="Name">
            <input type="text" id="reg_surname" name="reg_surname" placeholder="Surname">
            <input type="text" id="reg_desc" name="reg_desc" placeholder="Description">
            <input type="submit" id="reg_submit" name="reg_submit" value="Submit">
          </div>
        </form>
      </div>
      <div id="user-list" class="user-content">
        <div>
          <h2>Baggage list</h2>
        </div>
        <?php
          $users = getBaggages();
          foreach($users as $user){
            echo $user["id"].' ';
            echo $user["name"].' ';
            echo $user["surname"].' ';
            echo $user["created"].'</br>';
          }
        ?>
      </div>
      <div id="user-history" class="user-content">
        <div>
          <h2>Baggage history</h2>
        </div>
        <?php
          $users = getHistory();
          foreach($users as $user){
            echo $user["id"].' ';
            echo $user["name"].' ';
            echo $user["surname"].' ';
            echo $user["deleted"].'</br>';
          }
        ?>
      </div>
      <?php
        if(isset($_GET['rem_row'])&&isset($_GET['rem_col'])){
          ?>
            <div id="user-details" class="user-content" style="display: block;">
              <div>
                <h2>User details</h2>
              </div>
              <?php
                $details_row = chr($_GET['rem_row']+65);
                $details_col = ord($_GET['rem_col'])-48;
                $details = getInfo($details_row, $details_col);
                echo $details["id"].' ';
                echo $details["name"].' ';
                echo $details["surname"].'</br>';
                ?>
                  <a href="<?php echo 'assets/functions/remove_old.php?rem_row='.$_GET['rem_row'].'&rem_col='.$_GET['rem_col']; ?>">Remove baggage</a>
                <?php
              ?>
            </div>
          <?php
        }
      ?>
    </div>
    <div id="pos-wrap">
    <?php
      global $rows;
      global $cols;
      $ini_row = 0;
      $ini_col = 0;
      $med_col = $cols/2;
    ?>
      <table>
        <?php
          while($ini_row<$rows){
            ?><tr><?php
              $corridor = false;
              while($ini_col<$cols){
                if((!$corridor)&&($ini_col==$med_col)){
                  ?><td class="pos-med" id="<?php echo 'hupc-pos_'.$ini_row.'-corridor'; ?>"><?php
                  ?></td><?php
                  $corridor = true;
                }
                else if(getOcupation(numToChar($ini_row), $ini_col)!=NULL){
                  ?><td id="<?php echo 'hupc-pos_'.$ini_row.'-'.$ini_col; ?>" style="background-color: #db4646;"><?php
                    ?><a href="<?php echo '?rem_row='.$ini_row.'&rem_col='.$ini_col; ?>"><?php echo numToChar($ini_row).$ini_col; ?></a><?php
                  ?></td><?php
                  $ini_col++;
                }
                else{
                  ?><td id="<?php echo 'hupc-pos_'.$ini_row.'-'.$ini_col; ?>"><?php
                    echo numToChar($ini_row).$ini_col;
                  ?></td><?php
                  $ini_col++;
                }
              }
            ?></tr><?php
            $ini_col = 0;
            $ini_row++;
          }
        ?>
      </table>
    </div>
  </div>
</body>
</html>
<script>
  function openTab(evt, tabName){
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("user-content");
    for(i=0; i<tabcontent.length; i++){
      tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for(i=0; i<tablinks.length; i++){
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
  }
</script>