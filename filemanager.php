<?php
	include("controller/common/filemanager.php");
?>
<div id="fm" class="modal fade" role="dialog">
	<div class="modal-dialog">
    	<div class="modal-content">  
			<div class="modal-header">
				<h4 class="modal-title"><?php echo $text_title_update;?></h4>
        		<button type="button" class="close" data-dismiss="modal">&times;</button>        		
      		</div>
    		<div class="modal-body">	
				<div id="fm_message"></div>
					<div id="image_preview" class="text-center">
						<img id="previewing" src="<?php echo HTTP_SERVER;?>img/np.jpg" class="img-thumbnail" width="320" height="320" accept="image/jpg, image/gif, image/png"/>
					</div>
					<div class="form-group">
						<label><?php echo $text_img;?></label>						
						<input  id="img_upload"  name='fileToUpload' type="file" class="form-control"/> 						
					</div>				
					<div class="form-row">
    					<div class="form-group required col-md-12">
            				<label><?php echo $text_description;?></label>
            				<input id="desc_upload" maxlength="300" type="text" class="form-control"  placeholder="<?php echo $text_description;?>">
          				</div>  
					</div>   					
					<div class="text-right">	
        				<button id="ajaxAddEdit" class="btn btn-primary" data-dismiss="modal"><?php echo $text_fm_upload;?></button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>	
			</div>
    	</div>
  	</div>
</div>


<div id="del_item" class="modal fade" role="dialog">
	<div class="modal-dialog">
    	<div class="modal-content">
      
			<div class="modal-header">
				<h4 class="modal-title"><?php echo $text_title_confirmation;?></h4>
        		<button type="button" class="close" data-dismiss="modal">&times;</button>        		
      		</div>

    		<div class="modal-body">									
				<div class="text-center">				
					<p><?php echo $text_fm_confirmation;?></p>
 				</div>		

				<div class="text-center">	
					<button id="ajaxDelete" class="btn btn-primary" data-dismiss="modal"><?php echo $text_fm_delete;?></button>		
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>		
			</div>
    	</div>
  	</div>
</div>

<div id="info_operation" class="modal fade" role="dialog">
	<div class="modal-dialog">
    	<div class="modal-content">  
			<div class="modal-header">
				<h4 class="modal-title"><?php echo$text_title_transaction;?></h4>
        		<button type="button" class="close" data-dismiss="modal">&times;</button>        		
      		</div>

    		<div class="modal-body">									
				<div class="text-center">								
					<div id=user-info></div>
 				</div>		

				<div class="text-center">				
    				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>		
			</div>
    	</div>
  	</div>
</div>

<?php /* 
Here start all functionalities of upload the item
include validation of each one
*/
?>

<?php // function filemanager modal  ?>
<script type="text/javascript">
	var item_id;

	// open modal filemanager
	function filemanager(id) {		
		item_id = id;
		if (item_id !=''){ // load item
			$("#previewing").attr('src', $("#item-img-" + id).attr('src'));			
			$("#desc_upload").val($("#item-desc-" + id).text());	
		}
		$('#fm').modal('show');
	}	

	function delete_item(id) { 	
		item_id = id;	
		//security answer	
		$('#del_item').modal('show');
	}	

	$(document).ready(function(){

		// clean modal 
		$(".modal").on("hidden.bs.modal", function(){
    		$("#fm_message").html("");	
			$("#img_upload").val("");	
			$("#desc_upload").val("");
			$('#previewing').attr('src','<?php echo HTTP_SERVER;?>img/np.jpg');			
		});

		$("#ajaxAddEdit").click(function(e){
			e.preventDefault();
 			var value1 = $("#desc_upload").val();
			var change_img =true;  
			var form_data = new FormData();                  	
			

			if(value1 == ""){
				$("#fm_message").html('<div class="alert alert-danger"><?php echo $text_empty_description;?></div>');
				return false;				
			}

			form_data.append( "kind","new_img");
			
			if (item_id == ''){ // new item
				if ($("#img_upload").val() == ''){  // no picture
					form_data.append( "kind","np");	
					img_name = '<?php echo $text_noimage;?>';
				}
				else{  // selected image
					var file_data = $("#img_upload").prop("files")[0];  
					form_data.append( "fileToUpload", file_data);  
					img_name = file_data.name;
				}
			}
			else{   // edit item				
				if ($("#img_upload").val() == ''){// same picture
					form_data.append( "kind","old_img");
					change_img = false;
				}
				else{ // change picture
					var file_data = $("#img_upload").prop("files")[0];  
					form_data.append( "fileToUpload", file_data);
				}				
			}		
			form_data.append("id", item_id);                       
			form_data.append("description", value1);          


			//--- Upload image --
			$.ajax({
				url: "ajax.php?addEdit", 
				type: "POST",            
				data: form_data,
				contentType: false,       
				cache: false,             
				processData:false,  		     
				success: function(data){

					if (data.p_status){
						if (item_id == ''){ // new item
							$("#counter").html(data.itemsCounter);	
							item_id = data.last_id; 				        			
					 		cad = 	'<tr id="item-' + item_id  + '" class="row ui-state-default">' +
                					'<td class="col-sm-2"><div class="img-size"><img id="item-img-' + item_id  + '"src="<?php echo HTTP_SERVER;?>img/' + img_name + '" class="img-fluid"/></div></td>' +   
                					'<td class="col-sm-7"><p id="item-desc-' + item_id  + '">' + value1 + '</p></td>' +
                					'<td class="col-sm-3"><div class="row"><div class="col-md-12">' + 
                					'<button type="button"  class="btn btn-primary btn-lg btn-block"  onclick="filemanager(' + item_id  + ')"><? echo $text_edit;?></button></div></div>' +
                					'<div class="row"><div class="col-md-12">' + 
                					'<button type="button" class="btn btn-danger btn-lg btn-block" onclick="delete_item(' + item_id  + ')"><?php echo $text_delete;?></button></div></div></td>' + 
                					'</tr>';        
          					$("#sortable").append(cad);
						}
						else{
							if (change_img){
								$("#item-img-" + item_id).attr("src","<?php echo HTTP_SERVER;?>img/" +  file_data.name);						
								$("#item-img-" + item_id).attr("name", file_data.name);						
							}
							$("#item-desc-" + item_id).text(value1); 	
						}
						$("#user-info").html('<div class="alert alert-success"><?php echo $text_success;?></div>');
						$('#info_operation').modal('show');
					}
					else{
						$("#fm_message").html('<div class="alert alert-danger">' + data.message + '</div>');
						$('#fm').modal('show');
					}	
				}
			});

		});

		$("#ajaxDelete").click(function(e){
			e.preventDefault();

			$.ajax({
      			type: "POST",
      			url: "ajax.php?delete",				 
			    data:{ id: item_id},
				success:function(data){
					$("#counter").html(data.itemsCounter);	
					$("#item-" + item_id).remove();	
					$("#user-info").html('<div class="alert alert-success"><?php echo $text_success;?></div>');					
					$('#info_operation').modal('show'); 			
				}
 			});
		});


	});	
</script>

<?php // validation of image  ?>
<script type="text/javascript">
	$(document).ready(function (e) {

		$(function() {
			$("#img_upload").change(function() {
				$("#fm_message").empty(); 
				var file = this.files[0];
				var imagefile = file.type;
				var match= ["image/jpg","image/jpeg","image/gif","image/png"];
			

				if(!((imagefile === match[0]) || (imagefile === match[1]) || (imagefile === match[2]) || (imagefile === match[3])))
				{
					$('#previewing').attr('src','<?php echo HTTP_SERVER;?>img/np.jpg');
					$("#fm_message").html('<div class="alert alert-danger"><?php echo $text_title_kimg;?></div>');	
					return false;
				}
				else
				{
					var reader = new FileReader();
					reader.onload = imageIsLoaded;
					reader.readAsDataURL(this.files[0]);
				}
			});
		});

		function imageIsLoaded(e) {
			$('#previewing').attr('src', e.target.result);
			$('#previewing').attr('width', '320px');
			$('#previewing').attr('height', '320px');
		};
	});

</script>

