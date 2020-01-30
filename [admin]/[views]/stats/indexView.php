<h1>Статистика</h1>


<script>

    var opts = {
    }




    function list1()
    {
        opts.dateFrom = $('#dateFrom').val()
        opts.dateTo= $('#dateTo').val()
        opts.doctorId= $('#doctorId').val()
        opts.serviceId1= $('#serviceId').val()

        $.ajax({
            url: '/<?=ADMIN_URL_SIGN?>/stats/list',
            data: opts,
            beforeSend: function(){$.fancybox.showLoading()},
            complete: function(){ $("#cats").css("opacity", "1"); $('#cats').slideDown('fast'); $('#items').slideUp('fast');  $.fancybox.hideLoading() },
            success: function(data){
                $('#cats').html(data)
            },
            error: function(e){}

        });
    }



    $(document).ready(function(){
        list1()
    });
</script>



<div id="cats" style="display: none; "> Загрузка....</div>