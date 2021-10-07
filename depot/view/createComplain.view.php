<?php
  include '../includes/class-autoload.inc.php';
  session_start();

  if(!isset($_SESSION['userId'])){
    header("location:index.php");
  }
?>
<!DOCTYPE html>
<html>
  <head>

    <meta charset="utf-8">
    <meta name="description" content="Create a complain for a bus.">
    <meta name="viewport" content="width-device-width, initial-scaled=1">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <title>
      Create complain
    </title>

  </head>
  <body  style="background: #d3e5e5;">
    <header>
      <?php
        include "../includes/headerpart.php";
      ?>
    </header>

    <main class="wrapper"  style="margin: 26px 0px;font-family: 'Montserrat-Regular'!important;">

        <div class="inner">
            <form id="submitForm">

                <div>
                    <h1>Create a Complain form</h1>
                </div>


                <div id="enternamediv">
                  <label for="searchbox">Enter name:</label>
                  <input type="text" id="searchbox" onkeyup="showDutyRec(this.value)" placeholder="Conductor or Driver">
                </div>
                <script>
                  function showDutyRec(name) {
                    var xhttp;
                    xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function() {
                      if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("dutyrecordtable").innerHTML = this.responseText;
                      }
                    };
                    xhttp.open("GET", "../includes/loadRecords.php?name="+name+"&state=closed", true);
                    xhttp.send();
                  }
                </script>


                <div id=datacontent>
                  <table id = "dutyrecordtable">
                    <thead>
                        <tr>
                          <th>NumberPlate</th>
                          <th>Driver</th>
                          <th>Conductor</th>
                        </tr>
                    </thead>
                    <?php
                      $factory = new ControllerFactory();
                      $cashierObj = $factory->getController("CASHIER");
                      $results = $cashierObj->showDutyRecords("closed");
                      foreach ($results as $row){
                        echo "<tr onclick=\"displySelectedRec( {$row['dutyid']} )\">
                                <td class=\"Numplate\">{$row['busid']}</td>
                                <td>{$row['driverid']}</td>
                                <td>{$row['conductorid']}</td>
                              </tr>";
                      }
                    ?>
                  </table>
                  <div id="showSelected" >
                    <div id="recordDiv" value="notset">
                      <p>Select a Duty record from table</p>
                    </div>
                  </div>
                </div>
                <script>
                  function displySelectedRec(dutyid){
                    xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function() {
                      if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("showSelected").innerHTML = this.responseText;
                      }
                    };
                    xhttp.open("GET", "../includes/showRecord.php?dutyId="+dutyid+"&page=createComplain", true);
                    xhttp.send();
                  }
                </script>
                <div style="display: flex; flex-direction: column;">
                  <label for="complainText">Enter Complain :</label>
                  <textarea id="complainText" name="complainText" form="submitForm"></textarea>
                </div>


                <div>
                  <div>
                    <button class="pageButton" id="submit-button" type="button" name="submit-complain" onclick="submitComplainForm()">Submit</button>
                  </div>
                  <div id="err">

                  </div>
                </div>
                <script>
                  function submitComplainForm() {
                    var did = document.getElementById("recordDiv").getAttribute('value');
                    var complainText = document.getElementById("complainText").value;

                    if ((did=="notset") && (complainText=="")) {
                      document.getElementById("err").innerHTML = "<p>Select a record and enter complain</p>";
                    }else if ((did!="notset") && (complainText=="")) {
                      document.getElementById("err").innerHTML = "<p>Enter complain</p>";
                    }else if ((did=="notset") && (complainText!="")) {
                      document.getElementById("err").innerHTML = "<p>Select a record</p>";
                    }else{
                      document.getElementById("err").innerHTML = "";
                      xhttp = new XMLHttpRequest();
                      /*xhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                          document.getElementById("showSelected").innerHTML = this.responseText;
                        }
                      };*/
                      xhttp.open("GET", "../includes/submitForm.php?dutyId="+did+"&complainText="+complainText+"&page=complainCreate", true);
                      xhttp.send();
                      //window.location.reload();
                    }
                  }
                </script>


            </form>
        </div>
    </main>
    <?php
    include "../includes/footerpart.php";
    ?>
  </body>
</html>
