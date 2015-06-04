'use strict';


function onModalClose() {
    $(".modal-backdrop, #myModal .close, #myModal .btn").on("click", function() {
        $("#myModal iframe").attr("src", $("#myModal iframe").attr("src"));
    });
};

$(function() {
    onModalClose(); 
});




