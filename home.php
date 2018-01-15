<?php
    include("common/header.php");
    include("controller/home.php");
?>
<div class="container page">
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-sm-10">
          <h3><?php  echo $text_counter;?><span id='counter'>0</span></h3>        
        </div>
        <div class="col-sm-2 text-right">
          <button type="button" class="btn btn-primary btn-lg btn-block" onclick="filemanager('')" ><?php echo $text_add; ?></button>        
         </div>
      </div>
    </div>
    <div class="card-body">
        <table class="table size-table">     
          <thead>
            <tr class="row top-items">
              <td class="text-center col-sm-2"><h4><?php echo $text_img ?></h4></td>
              <td class="text-center col-sm-7"><h4><?php echo $text_description ?></h4></td>
              <td class="text-center col-sm-3"><h4><?php echo $text_action ?></h4></td>                  
            </tr>
          </thead>          
          <tbody id="sortable"></tbody>    
        </table>

    </div>
  </div> 
</div>
<?php 
  include("filemanager.php");
  include("common/footer.php");
?>

<?php  // drag and drop ?>
<script type="text/javascript">
	$(document).ready(function(){ 
    var order_ini;
    var order_end;

    $("#sortable").sortable({
      placeholder: "ui-state-highlight",
      start: function (event, ui) {
          order_ini = ui.item.index();
      },
      update:  function (event, ui) {
        var eorder =  ui.item.index();   
        // sort-order in data base
        $.ajax({
          type: "POST",       
          url: "ajax.php?sortOrder",	
          data:{ ini: order_ini, end: eorder},
			    success:function(data){

          }
 		    });          
      }  
    });
  });
</script> 

<?php //get items list  ?>
<script type="text/javascript">
	$(document).ready(function(){   
    $("#sortable").html('<br><div class="alert alert-info text-center"><h2><?php echo $text_loading;?></h2></div>');
 		$.ajax({
      type: "POST",       
      url: "ajax.php?getItems",	
      data:{ access: ''},
      dataType: "json",  
			success:function(data){
        var cad;

        $("#counter").html(data.itemsCounter);

        $("#sortable").html('');
        $.each(data.itemsData, function(index, value){
          cad = '<tr id="item-' + value.id  + '" class="row ui-state-default">' +
                '<td class="col-sm-2"><div class="img-size"><img id="item-img-' + value.id  + '" src="<?php echo HTTP_SERVER;?>img/' + value.image + '" class="img-fluid" name="' + value.image + '"/></div></td>' +   
                '<td class="col-sm-7"><p id="item-desc-' + value.id  + '">' + value.description + '</p></td>' +
                '<td class="col-sm-3"><div class="row"><div class="col-md-12">' + 
                '<button type="button"  class="btn btn-primary btn-lg btn-block" onclick="filemanager(' + value.id  + ')"><? echo $text_edit;?></button></div></div>' +
                '<div class="row"><div class="col-md-12">' + 
                '<button type="button" class="btn btn-danger btn-lg btn-block" onclick="delete_item(' + value.id  + ')"><?php echo $text_delete;?></button></div></div></td>' + 
                '</tr>';          
         
          $("#sortable").append(cad);       
        }); 
      }
 		});
  });  
</script> 