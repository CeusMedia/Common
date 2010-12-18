jQuery(document).ready(function(){
  $("area").click(function(){
    var package=$(this).attr('title'); 
    var tab=$("#tabs-packages li#package_"+package);
    $("a",tab).trigger('click');
    return false;
  });
});

