$(document).ready(function (){
    var ias = jQuery.ias({
       container: '#timeline .box-content',
       item: '.publication-item',
       pagination: '#timeline .pagination',
       next: '#timeline .pagination .next_link',
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
    $(".btn-img").unbind("click").click(function (){
       $(this).parent().find('.pub-image').fadeToggle(); 
    });
}


