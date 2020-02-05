<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="style.css" rel="stylesheet" type="text/css">
    <script src="scripts.js"></script>

    <title>Flugplan</title>

</head>

<body>
<?php
/*$host_name = 'db5000286797.hosting-data.io';
$database = 'dbs280009';
$user_name = 'dbu394562';
$password = 'Mastermind1324!';
$connect = mysqli_connect($host_name, $user_name, $password, $database);
*/
$host_name = 'localhost';
$database = 'flightplandb';
$user_name = 'Christian';
$password = 'Mastermind1324';
$connect = mysqli_connect($host_name, $user_name, $password, $database);
session_start();
if (mysqli_connect_errno()) {
    die('<p>Verbindung zum MySQL Server fehlgeschlagen: ' . mysqli_connect_error() . '</p>');
} else {
    echo '<p>Verbindung zum MySQL Server erfolgreich aufgebaut.</p >';
}
$Route = 'TEST';
$Treibstoff = null;
$Alternate = null;
//$DEP = null;
if (isset($_POST['name_departure_input'])) {
    $DEP = $_POST['name_departure_input'];
    sqlAbfrageDeparture($connect, $DEP);
    if (isset($_POST['name_arrival_input'])) {
        $ARR = $_POST['name_arrival_input'];
        $Route = routeLaden($DEP, $ARR, $connect);
    }
}
if (isset($_POST['name_arrival_input'])) {
    $ARR = $_POST['name_arrival_input'];
    sqlAbfrageArrival($connect, $ARR);
}


?>


<iframe src="dbhandler.php" style="display: none"></iframe>
<header>
    <div class="logo">
        <img style="vertical-align:middle; background-color: #5f5f5f" width="60px" src="flugzeug.png">
        FlightPlan Manager
    </div>

</header>
<form id="id_suche" method="post">
    <input type="text" id="id_route_input" name="name_departure_input" placeholder="Departure ICAO">
    <input type="text" id="id_route_input" name="name_arrival_input" placeholder="Arrival ICAO">
    <button type="submit" id="id_create_flightplan">Erstellen</button>

</form>

<!--Datalists Departure-Frequenzen-->
<datalist id="list_ground_dep" name="list_ground_dep"></datalist>
<datalist id="list_atis_dep" name="list_atis_dep"></datalist>
<datalist id="list_tower_dep" name="list_tower_dep"></datalist>
<datalist id="list_delivery_dep" name="list_delivery_dep"></datalist>
<!--Departure Runway-->
<datalist id="list_rwy_dep" name="list_rwy_dep"></datalist>


<!--Datalists Arrival-Frequenzen-->
<datalist id="list_ground_arr" name="list_ground_arr"></datalist>
<datalist id="list_atis_arr" name="list_atis_arr"></datalist>
<datalist id="list_tower_arr" name="list_tower_arr"></datalist>
<datalist id="list_approach_" name="list_approach"></datalist>
<!--Arrival Runway-->
<datalist id="list_rwy_arr" name="list_rwy_arr"></datalist>


<div id="id_flugplan">
    <form id="id_form_plan">
        <br>
        <fieldset id="id_fieldset_beforeflight">
            <legend id="id_fieldset_beforeflight_legend">Before flight</legend>
            Date: <input type="text" id="id_date">
            ETD: <input type="text" id="id_etd">
            DEP Apt: <input type="text" id="id_dep_apt" value="<?php echo @$DEP; ?>"/>
            ARR Apt: <input type="text" id="id_arr_apt" value="<?php echo @$ARR; ?>"/>
            ALTN Apt: <input type="text" id="id_altn_apt" value="<?php echo @$Alternate; ?>">
            Callsign: <input type="text" id="id_calllsign">
            Stand: <input type="text" id="id_stand"><br><br>
            ACT Runways: <input type="text" id="id_act_rwys">
            TA: <input type="text" id="id_ta">
            Cruise FL: <input type="text" id="id_cruise_fl">
            ATIS Info: <input type="text" id="id_atis_info_dep">
            QNH: <input type="text" id="id_qnh_dep">
            Temp: <input type="text" id="id_temp_dep">
            Enroute Time: <input type="text" id="id_enroute_time">
            Pax: <input type="text" id="id_pax">
            Cargo: <input type="text" id="id_cargo">
            <br><br>
            Block Fuel: <input type="text" id="id_block_fuel">
            ZFW: <input type="text" id="id_zfw">
            TOW: <input type="text" id="id_tow">
            Trip Fuel: <input type="text" id="id_tripfuel" value="<?php echo @$Treibstoff; ?>">
            Fuel hours: <input type="text" id="id_fuel_hours">
            <br><br>
            ATIS Freq: <input type="text" list="list_atis_dep" id="id_atis_freq_dep" PLACEHOLDER="Select frequency...">
            Delivery Freq: <input type="text" list="list_delivery_dep" id="id_delivery_freq"
                                  PLACEHOLDER="Select frequency...">
            Ground 1 Freq: <input list="list_ground_dep" type="text" id="id_gnd_1_freq_dep"
                                  PLACEHOLDER="Select frequency...">
            Ground 2 Freq: <input list="list_ground_dep" type="text" id="id_gnd_2_freq_dep"
                                  PLACEHOLDER="Select frequency...">
            Tower Freq: <input type="text" list="list_tower_dep" id="id_twr_freq_dep" PLACEHOLDER="Select frequency...">


        </fieldset>
        <br>
        <fieldset>
            <legend>ATC Clearance</legend>
            SID: <input type="text" id="id_sid">
            RWY: <input type="text" id="id_rwy_takeoff">
            Init CLB: <input type="text" id="id_init_clb">
            Squawk: <input type="text" id="id_squawk">
            <br><br>
            <textarea id="id_further_information" placeholder="Further Information:"></textarea>
            <textarea id="id_route" name="name_route" placeholder="Route:"><?php echo htmlspecialchars($Route);?></textarea>
        </fieldset>
        <br>
        <fieldset>
            <legend>Taxi</legend>
            Time: <input type="text" id="id_taxi_time">
            <br><br>
            <textarea id="id_taxi_route" placeholder="Taxiroute:"></textarea>
        </fieldset>
        <br>
        <fieldset>
            <legend>Takeoff</legend>
            Time: <input type="text" id="id_takeoff_time">
            Approach Freq: <input type="text" id="id_dep_freq" list="list_approach" placeholder="Select frequency...">
            Radar 1: <input type="text" id="id_rdr_1_freq">
            Radar 2: <input type="text" id="id_rdr_2_freq">
            Radar 3: <input type="text" id="id_rdr_3_freq">
            Radar 4: <input type="text" id="id_rdr_4_freq">
            <br> <br>
            <textarea id="id_notes_dep" placeholder="Notizen:"></textarea>
        </fieldset>
        <br>
        <fieldset>
            <legend>Descending, landing, taxiing</legend>
            Time: <input type="text" id="id_time_landing">
            Runway: <input type="text" id="id_rwy_landing">
            STAR: <input type="text" id="id_star">
            Approach: <input type="text" id="id_approach">
            Stand: <input type="text" id="id_stand_arr">
            <br><br>
            ATIS Freq: <input type="text" id="id_atis_freq_arr" list="list_atis_arr" placeholder="Select frequency...">
            Approach Freq: <input type="text" id="id_app_freq_arr" list="list_approach"
                                  placeholder="Select frequency...">
            Tower Freq: <input type="text" id="id_twr_freq_arr" list="list_tower_arr" placeholder="Select frequency...">
            Ground 1: <input type="text" id="id_gnd_1_freq_arr" list="list_ground_arr"
                             placeholder="Select frequency...">
            Ground 2: <input type="text" id="id_gnd_2_freq_arr" list="list_ground_arr"
                             placeholder="Select frequency...">
            <br><br>
            QNH: <input type="text" id="id_qnh_arr">
            Active Runways: <input type="text" id="id_act_rwys_arr">
            Temp: <input type="text" id="id_temp_arr">
            Transition Level: <input type="text" id="id_tl">
            ATIS Info: <input type="text" id="id_atis_info_arr">
            <br><br>
            <textarea id="id_notes_arr" placeholder="Notizen:"></textarea>

        </fieldset>
        <br>

    </form>
</div>

<?php
function sqlAbfrageDeparture($connect, $DEP)
{

    $sql = "SELECT concat(frequenz, ' ',bezeichnung) as ergebnis FROM airports join ground on airports.icao = ground.icao where airports.icao ='$DEP'";
    $result = $connect->query($sql);
    if (mysqli_num_rows($result) > 0) {
        echo "<datalist id ='list_ground_dep'>";
        while ($row = $result->fetch_assoc()) {
            echo "<option value=" . $row['ergebnis'] . ">";
            echo $row['ergebnis'];
        }
        echo "</datalist>";
    } else {
        echo "Ground DEP";
    }

    $sql = "SELECT concat(frequenz, ' ', bezeichnung) as ergebnis FROM airports join atis a on airports.icao = a.icao where a.icao ='$DEP'";
    $result = $connect->query($sql);
    if (mysqli_num_rows($result) > 0) {
        echo "<datalist id ='list_atis_dep'>";
        while ($row = $result->fetch_assoc()) {
            echo "<option value=" . $row['ergebnis'] . ">";
            echo $row['ergebnis'];
        }
        echo "</datalist>";
    } else {
        echo "atis DEP";
    }

    $sql = "SELECT concat(frequenz,' ',bezeichnung) as ergebnis FROM airports join tower a on airports.icao = a.icao where a.icao ='$DEP'";
    $result = $connect->query($sql);
    if (mysqli_num_rows($result) > 0) {
        echo "<datalist id ='list_tower_dep'>";
        while ($row = $result->fetch_assoc()) {
            echo "<option value=" . $row['ergebnis'] . ">";
            echo $row['ergebnis'];
        }
        echo "</datalist>";
    } else {
        echo "Tower DEP";
    }

    $sql = "SELECT concat(frequenz,' ',bezeichnung) as ergebnis FROM airports join delivery a on airports.icao = a.icao where a.icao ='$DEP'";
    $result = $connect->query($sql);
    if (mysqli_num_rows($result) > 0) {
        echo "<datalist id ='list_delivery_dep'>";
        while ($row = $result->fetch_assoc()) {
            echo "<option value=" . $row['ergebnis'] . ">";
            echo $row['ergebnis'];
        }
        echo "</datalist>";
    } else {
        echo "Delivery";
    }

    /*$sql = "SELECT richtung FROM runways join airport_runway a on runways.id  = a.runway_id join airports b on  where a.icao ='$DEP'";
    $result = $connect -> query($sql);
    if (mysqli_num_rows($result) > 0) {
        echo "<datalist id ='list_delivery_dep'>";
        while($row = $result->fetch_assoc()) {
            echo "<option value=".$row['ergebnis'].">";
            echo $row['ergebnis'];
        }
        echo "</datalist>";
    } else {
        echo "0 results";
    }*/
}


function sqlAbfrageArrival($connect, $ARR)
{
    $sql = "SELECT concat (frequenz, ' ', bezeichnung) as ergebnis FROM airports join atis a on airports.icao = a.icao where a.icao = '$ARR'";
    $result = $connect->query($sql);
    if (mysqli_num_rows($result) > 0) {
        echo "<datalist id ='list_atis_arr'>";
        while ($row = $result->fetch_assoc()) {
            echo "<option value=" . $row['ergebnis'] . ">";
            echo $row['ergebnis'];
        }
        echo "</datalist>";
    } else {
        echo "atis ARR";
    }

    $sql = "SELECT concat (frequenz, ' ', bezeichnung) as ergebnis FROM airports join approach a on airports.icao = a.icao where a.icao = '$ARR'";
    $result = $connect->query($sql);
    if (mysqli_num_rows($result) > 0) {
        echo "<datalist id ='list_approach'>";
        while ($row = $result->fetch_assoc()) {
            echo "<option value=" . $row['ergebnis'] . ">";
            echo $row['ergebnis'];
        }
        echo "</datalist>";
    } else {
        echo "Approach";
    }

    $sql = "SELECT concat (frequenz, ' ', bezeichnung) as ergebnis FROM airports join tower a on airports.icao = a.icao where a.icao = '$ARR'";
    $result = $connect->query($sql);
    if (mysqli_num_rows($result) > 0) {
        echo "<datalist id ='list_tower_arr'>";
        while ($row = $result->fetch_assoc()) {
            echo "<option value=" . $row['ergebnis'] . ">";
            echo $row['ergebnis'];
        }
        echo "</datalist>";
    } else {
        echo "Tower ARR";
    }

    $sql = "SELECT concat (frequenz, ' ', bezeichnung) as ergebnis FROM airports join ground a on airports.icao = a.icao where a.icao = '$ARR'";
    $result = $connect->query($sql);
    if (mysqli_num_rows($result) > 0) {
        echo "<datalist id ='list_ground_arr'>";
        while ($row = $result->fetch_assoc()) {
            echo "<option value=" . $row['ergebnis'] . ">";
            echo $row['ergebnis'];
        }
        echo "</datalist>";
    } else {
        echo "Ground ARR";
    }

}

function routeLaden($DEP, $ARR, $connect)
{
    $sql = "SELECT route from routen where routen.start_flughafen = '$DEP' AND routen.ziel_flughafen ='$ARR'";
    $ergebnis= '';
    $result = $connect->query($sql);
    if (mysqli_num_rows($result) > 0) {

        while ($row = $result->fetch_assoc()) {
            $ergebnis = $row['route'];
        }
        return $ergebnis;
    } else {
        echo "0 results";
    }
}
?>


</body>
</html>