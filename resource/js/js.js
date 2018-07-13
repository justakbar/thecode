/*jQuery(function($) {
	
	$('#more').click(function(){
        var btn_more = $(this);
        var count_show = parseInt($(this).attr('count_show'));
        var count_add  = $(this).attr('count_add');
        btn_more.val('Подождите...');
                 
        $.ajax({
                    url: "ajax.php", // куда отправляем
                    type: "post", // метод передачи
                    dataType: "json", // тип передачи данных
                    data: { // что отправляем
                        "count_show":   count_show,
                        "count_add":    count_add
                    },
                    // после получения ответа сервера
                    success: function(data){
			            if(data.result == "success"){
			                $('#cont').append(data.html);
			                    btn_more.val('Показать еще');
			                    btn_more.attr('count_show', (count_show+3));
			            }else{
			                btn_more.val('Больше нечего показывать');
			            }
		            }
                });
            });

	$('#send').click(function(){

		$.ajax({

			url: location.href,
			type: 'post';
			date: {'nothing':3},
			success: function(data)
			{
				document.write("hello");
			}
		});

	});
});

*/
jQuery(function($){

	$('#invisibile, #visibile').click(function() {
		var a = $(this).attr('name');
		var length = a.length;
		if (a == 'visibile_' + a[length-1]) {
			id = a[length - 1];
			type = '0';
		} else if (a == 'invisibile_' + a[length-1]) {
			id = a[length - 1];
			type = '1';
		}

		$.ajax({
			url: location.href,
			type: 'post',
			data: {'id':id, 'type': type},
			success: function() {
				if (a == 'visibile_' + a[length - 1]) {
					$('#icon').removeClass('fas fa-eye-slash');
					$('#icon').addClass('fas fa-eye');
					$('#visibile').attr('name','invisibile_' + a[length-1]);
					$('#visibile').attr('id','invisibile');
				}
				else if (a == 'invisibile_' + a[length-1]) {
					$('#icon').removeClass('fas fa-eye');
					$('#icon').addClass('fas fa-eye-slash');
					$('#invisibile').attr('name','visibile_' + a[length-1]);
					$('#invisibile').attr('id','visibile');
				}
			}
		});
	});

	$('#delete').click(function() {
		var a = $(this).attr('name');
		var length = a.length;
		if (a == 'delete_' + a[length-1]) {
			id = a[length - 1];
			query = 'delete';
		}

		$.ajax({
			url: location.href,
			type: 'post',
			data: {'id' : id, 'query' : query},
			success: function() {
				$('#accordion_' + id).remove();
			}
		})


	});
});