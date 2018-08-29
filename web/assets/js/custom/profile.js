$(document).ready(function (){
    var ias = jQuery.ias({
       container: '.profile-box #user-publications',
       item: '.publication-item',
       pagination: '.profile-box .pagination',
       next: '.profile-box .pagination .next_link',
       triggerPageThreshold: 4
    });
    
    ias.extension(new IASTriggerExtension({
        text: 'Ver mas publicaciones',
        offset: 3
    }));
    
    ias.extension(new IASSpinnerExtension({
        src : URL + '/../assets/images/ajax-loader.gif'
    }));
    
    ias.extension(new IASNoneLeftExtension({
        text: 'No hay mas publicaciones'
    }));
    
    ias.on('ready',function (event) {
        Buttons();
    })
    
    ias.on('rendered',function (event) {
        Buttons();
    })
});

function Buttons(){
     $('[data-toggle="tooltip"]').tooltip(); 
    
    $(".btn-img").unbind("click").click(function (){
       $(this).parent().find('.pub-image').fadeToggle(); 
    });
    
    $(".btn-delete-pub").unbind("click").click(function (){
       $(this).parent().parent().addClass('hidden');
       
       $.ajax({
           url: URL+'/publications/remove/'+$(this).attr("data-id"),
           type: 'GET',
           success: function (response) {
                console.log(response);
            }
        });
    });
    
    $(".btn-like").unbind("click").click(function (){
        $(this).addClass('hidden');
        $(this).parent().find('.btn-unlike').removeClass("hidden");
        $.ajax({
           url: URL+'/like/'+$(this).attr("data-id"),
           type: 'GET',
           success: function (response) {
                console.log(response);
            }
        });
    });
    
    $(".btn-unlike").unbind("click").click(function (){
        $(this).addClass('hidden');
        $(this).parent().find('.btn-like').removeClass("hidden");
        $.ajax({
           url: URL+'/unlike/'+$(this).attr("data-id"),
           type: 'GET',
           success: function (response) {
                console.log(response);
            }
        });
    });
}


