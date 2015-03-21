/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function () {
    $('#login').click(function () {
        var isOffline = '<?php echo $output; ?>'; 
        if (isOffline.toString() === 'Du är offline.')
        {
            alert("Fel användarnamn eller lösenord!")
        }
    });
});
