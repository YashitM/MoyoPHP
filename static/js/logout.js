//Logout Redirection

$("#logout_form").submit(function(){ //Handle the sumbit here.
            var url = $("#logout_form").attr("action");
            var formData = $("#logout_submit_link").serialize();
            console.log(url);
            $.post(url, formData, function(response){
                console.log(response);
            });//end post
  });//end submit

  $(document).on("click","#logout_submit_link",function(evt){
    evt.preventDefault();
    $("#logout_form").submit();
});

