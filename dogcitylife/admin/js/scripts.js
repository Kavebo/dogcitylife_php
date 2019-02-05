$(document).ready(function(){
	$(".type_filter ul a").click(function(e){
		e.preventDefault();
		$(this).parents(".type_filter").find("select").val($(this).attr("href"));
		$("#filter_form").submit();
	});

	$("form#zarizeni").validate();

	$( ".datepicker" ).datepicker({
		dateFormat: "yy-mm-dd"
	});

	$(".checkboxradio input").checkboxradio();

	$(".tooltip").tooltip();


  
  $('.delete_review').click(function(e){
    e.preventDefault();
    if (confirm("Vážně chcete odstranit tuto recenzi?")) {
        $(".loader").show();
        $("#delete_review").val($(this).attr("data-id"));
        $("#admin_reviews").submit();
    } else {
        // Do nothing!
    }
    
  });

  $('.delete_user').click(function(e){
    e.preventDefault();
    if (confirm("Vážně chcete odstranit tohoto uživatele?")) {
        $(".loader").show();
        $("#delete_user").val($(this).attr("data-id"));
        $("form#admin_users").submit();
    } else {
        // Do nothing!
    }
    
  });

});

tinymce.init({
  selector: 'textarea.tinymce',
  height: 500,
  theme: 'modern',
  plugins: [
    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
    'searchreplace wordcount visualblocks visualchars code fullscreen',
    'insertdatetime media nonbreaking save table contextmenu directionality',
    'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help'
  ],
  toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  toolbar2: 'print preview media | forecolor backcolor emoticons | codesample help',
  image_advtab: true,
  templates: [
    { title: 'Test template 1', content: 'Test 1' },
    { title: 'Test template 2', content: 'Test 2' }
  ],
  content_css: [
    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
    '//www.tinymce.com/css/codepen.min.css'
  ]
 });

