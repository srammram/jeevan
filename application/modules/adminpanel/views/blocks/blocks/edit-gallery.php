
<div class="form-group">
	<label for="inputEmail3" class="col-sm-2 control-label"><?php echo 'Views'.get_required();?></label>
	<div class="col-sm-<?php echo get_form_size();?>"> <div class="input_box">
	 <?php echo form_dropdown("params[gallery_view]",$type_params['gallery_view'],$records["type_params"]["gallery_view"], "class=\"form-control required\""); ?>						 
		</div>
	</div>
</div>
