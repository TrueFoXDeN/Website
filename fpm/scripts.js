function hideButton(){
    setTimeout(function() {hideHelp(); }, 150)
}
function hideHelp(){
    document.getElementById('id_create_flightplan').style.display="none";
}

function showButton(){
    setTimeout(function() {showHelp(); }, 300)
}

function showHelp() {
    document.getElementById('id_create_flightplan').style.display="inline-block";
}