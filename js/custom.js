$('#menu').slicknav();
var html = document.getElementsByTagName("html")[0];
function youtube(){
        var url = $('#video_url').val();
        html.className = "loading";
        $.ajax({
            type:'POST',
            url:'includes/youtube.php',
            data:'url='+url,
            success:function(html){
                $('.abc').empty();
                $('.abc').html(html);
                $('.abc').show();
                var html = document.getElementsByTagName("html")[0];
                html.className = html.className.replace(/loading/, '');
            },
            error: function(xmlhttprequest, textstatus, message) {
          	if(textstatus==="timeout") {
          		var html = document.getElementsByTagName('html')[0];
            		html.className = html.className.replace(/loading/, '');
            		alert("Request Timeout, Please Try Again.");
          	} else {
          		var html = document.getElementsByTagName('html')[0];
            		html.className = html.className.replace(/loading/, '');
            		alert(textstatus);
          	}
            }
        });
}
$(document).ready(function(){
    $(document).on('click','.contactform',function(){
        var name = $('#name').val();
        var email = $('#email').val();
        var subject = $('#subject').val();
        var message = $('#message').val();
        $('.ajax-loadingc').show();
        $.ajax({
            type:'POST',
            url:'includes/contact.php',
            data:'name='+ name + '&email='+ email + '&subject='+ subject + '&message='+ message,
            success:function(html){
                $('body').append(html);
                $('.ajax-loadingc').hide();
                $('#contact').modal('hide');
                $('#contactsuccess').modal('show');
            }
        });
    });
});
$(document).on('hidden.bs.modal','#myModal', function () {
	$('#myModal').remove();
});
