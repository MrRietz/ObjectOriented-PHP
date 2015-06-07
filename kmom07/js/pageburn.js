'use strict';


function onModalClose() {
    $(".modal-backdrop, #myModal .close, #myModal .btn").on("click", function() {
        $("#myModal iframe").attr("src", $("#myModal iframe").attr("src"));
    });
};

function allButtonListener() {
    $("#allButton").on("click", function() {
        // Ajax Request    
        $.ajax({
            url: "movies.php?", 
            success: function(content){
                var movies = $(content).find('#movieSection'); 
                $(movies).find('#allButton').addClass("active");
                $('#movieSection').html(movies); 
            }
        });
    }); 

};



function getGenreAjax() {
    $(".genre-btn").on("click", function() {
       
        var button = $(this),   
            url = $(button).attr("data-url"); 
        $.ajax({
            url: url, 
            success: function(content){
                var movies = $(content).find('#movieSection'); 
                $(movies).find("#" + button.attr("id")).addClass("active");
                $('#movieSection').html(movies); 
            }
        });
    });
};

$(function() {
    onModalClose();    
    allButtonListener(); 
    getGenreAjax(); 
});