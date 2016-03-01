//获取用户名输入框中的值
$(document).ready(function () {
  $("#username").blur(function () {
    var username = $(this).val();
    if (username == '') {
      $("#show").html("");
    }
    else{
      $.ajax({
        url: "checkName.php?uname="+username
      }).done(function( data ) {
        $("#show").html(data);
      });
    }
  });
});