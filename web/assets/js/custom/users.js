$(document).ready(function (){
    var ias = jQuery.ias({
       container: '.box-users',
       item: '.publication-item',
       pagination: '.pagination',
       next: '.pagination .next_link',
       triggerPageThreshold: 4
    });
    
    ias.extension(new IASTriggerExtension({
        text: 'Ver mas personas',
        offset: 3
    }));
    
    ias.extension(new IASSpinnerExtension({
        src : URL + '/../assets/images/ajax-loader.gif'
    }));
    
    ias.extension(new IASNoneLeftExtension({
        text: 'No hay mas personas'
    }));
    
    ias.on('ready',function (event) {
        followButtons();
    })
    
    ias.on('rendered',function (event) {
        followButtons();
    })
});

function followButtons(){
    $('.btn-follow').unbind("click").click(function () {
        $(this).addClass("hidden");
        $(this).parent().find('.btn-unfollow').removeClass('hidden');
        $.ajax({
           url: URL+'/follow',
           type: 'POST',
           data:{followed: $(this).attr("data-followed")},
           success: function (response) {
                console.log(response);
            }
        });
    });
    
    $('.btn-unfollow').unbind("click").click(function () {
        $(this).addClass("hidden");
        $(this).parent().find('.btn-follow').removeClass('hidden');
        $.ajax({
           url: URL+'/unfollow',
           type: 'POST',
           data:{followed: $(this).attr("data-followed")},
           success: function (response) {
                console.log(response);
            }
        });
    });
}


