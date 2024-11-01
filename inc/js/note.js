






jQuery(document).ready(function($) {


    $('textarea').each(function () {
	  this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
	}).on('input', function () {
	  this.style.height = 'auto';
	  this.style.height = (this.scrollHeight) + 'px';
	});


	$("#newnot").click(function (){

     var note = $("#foo").val() ;
     var data_checked = $(".can-toggle__switch").attr("data-checked"); 
     var data_unchecked = $(".can-toggle__switch").attr("data-unchecked"); 

     //alert(data_checked); 
       if(note) 
       {

       	$("#loader").show() ;
       	var note_security=$("#Amir_Note_security").val();
      
		var data = {action: 'Amir_Note_save',note:note,command:"new",note_security:note_security};
           $.post(the_lab_url.lab_url, data, function(response)
           { 

           	 var xn=$.parseJSON(response);
           	 var m=xn["id"]; 

       	var HTMLS='<div class="note-item" id="item-'+m+'">';

		 HTMLS+=' <div class="icon">';
			     HTMLS+='<i data-id="item-'+m+'" class="fa fa-times right remove-item" ></i>';
                HTMLS+=' <i data-id="item-'+m+'" class="fa fa-pencil left edit-item" ></i>';
			HTMLS+=' </div>';

			HTMLS+=' <p>';
			HTMLS+=note;
			HTMLS+=' </p>';
			 
			   HTMLS+='<div class="buttonHolder">';
                  HTMLS+=' <div class="can-toggle can-toggle--size-large">';
				 HTMLS+=' <input id="c'+m+'" type="checkbox">';
				 HTMLS+=' <label for="c'+m+'">';
				   HTMLS+=' <div class="can-toggle__switch" data-checked="'+data_checked+'" data-unchecked="'+data_unchecked+'"></div>';
				 HTMLS+=' </label>';
				 HTMLS+='<span class="date">'+xn["date"]+'</span>';
				HTMLS+='</div>';
               HTMLS+=' </div>';
			
			HTMLS+='</div>';

			$("#notes").append(HTMLS);
			$("#loader").hide() ;
		});


       }

	});
    $("body").on("click",".remove-item",function (){
        $("#loader").show() ;
    	var id_item = $(this).attr("data-id"); 
    	var note_security=$("#Amir_Note_security").val();
    	  var data = {action: 'Amir_Note_save',item:id_item,command:"remove",note_security:note_security};
	 		   $.post(the_lab_url.lab_url, data, function(response)
	           { 
	           	      	$("#"+id_item).remove() ;
	           	      	$("#loader").hide() ;

	           });
    });

    $("body").on("click",".edit-item",function (){

    	var id_item = $(this).attr("data-id");
    	var newnot_tetx= $("#newnot").text(); 
    	var edit_tetx= $("#id01").attr("data-text");
    	var note=$("#"+id_item+" p").text();
    	var  edit_note='<div class="note-item edit">';
			edit_note+='<h3>'+edit_tetx+'</h3>';
			  edit_note+='<textarea ';
			   edit_note+='id="fooedit" class="autoExpand" cols="11" var rows="7" data-min-rows="3">'+note+'</textarea>'; 
			   edit_note+='<div class="buttonHolder">';
                   edit_note+=' <button data-id="'+id_item+'" id="editnote">'+newnot_tetx+'&nbsp;<i class="fa fa-floppy-o" aria-hidden="true"></i></button>';
               edit_note+=' </div>';
			edit_note+='</div>';
		$("#id01 .container-modal").html(edit_note);
    	$("#id01").show();
    	//$("#"+id_item).remove() ;
    });

	$("body").on("click","#editnote",function (){

         var id_item = $(this).attr("data-id");
	     var note=$("#fooedit").val();
	       if(note) 
	       {
	        var note_security=$("#Amir_Note_security").val();
			var data = {action: 'Amir_Note_save',note:note,item:id_item,command:"edit",note_security:note_security};
	           $.post(the_lab_url.lab_url, data, function(response)
	           { 
	           	  $("#"+id_item+" p").text(note);
	           	  $("#id01").hide();
	           });

	       }
	       


	   });



	$("body").on("click",".boxclose",function (){

		$(".modal").hide();
	});


    $("body").on("hover",".note-item",function (){
	var id_item = $(this).attr("id");

	 $("#"+id_item+" .icon i").show();

	});

	 $("body").on("mouseleave",".note-item",function (){
	 var id_item = $(this).attr("id");
	 $("#"+id_item+" .icon i").hide();

	});


	 $(".can-toggle label").click(function ()
	 	{
	 		 var id_input = $(this).attr("for"); 
	 		 var done= 1;
	 		  if ($('#'+id_input).is(':checked')) 
	 		  {
	 		  	done=0; 
	 		  }
	 		   var note_security=$("#Amir_Note_security").val();
	 		   var data = {action: 'Amir_Note_save',done:done,item:id_input,command:"done",note_security:note_security};
	 		   $.post(the_lab_url.lab_url, data, function(response)
	           { 
	           	  
	           });


	 	});

});

