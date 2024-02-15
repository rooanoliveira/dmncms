<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div class="dmn-content">
	<div class="dmn-page-box">
		<div class="dmn-page-title">
			<h1><?php echo __($about['name']); ?></h1>
		</div>
		<div class="dmn-page-content">
			<div class="row">
				<div class="col-12">   
					<?php 
					if(isset($config_not_found)){
						echo '<div class="alert alert-danger" role="alert">'.$config_not_found.'</div>';
					} 
					else{
						if(isset($module_disabled)){
							echo '<div class="alert alert-danger" role="alert">'.$module_disabled.'</div>';
						} 
						else{
							if(isset($error)){
								echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
							}
							if(isset($success)){
								echo '<div class="alert alert-success" role="alert">'.$success.'</div>';
							}
							if(isset($char_list) && $char_list != false){		
							?>
							<form method="post" action="" id="transfer_char">
								<div class="form-group">
									<label class="control-label"><?php echo __('Char'); ?></label>
									<div>
										<select name="character" id="character" class="form-control">
											<?php foreach($char_list as $char){ ?>
												<option value="<?php echo $char['id']; ?>"><?php echo $char['Name']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label"><?php echo __('New Name'); ?></label>
									<input type="text" class="form-control validate[maxSize[10]]" type="text" name="new_name" id="new_name" value="" placeholder="<?php echo __('Empty if same');?>">
								</div>
								<div class="form-group mb-5">
									<div class="d-flex justify-content-center align-items-center"><button type="submit" name="transfer" class="btn btn-primary"><?php echo __('Submit'); ?></button></div>
								</div>	
							</form>
							<?php
							}
							else{
							?>
							<div class="alert alert-danger" role="alert"><?php echo __('No characters found.');?></div>
							<?php
							}	
						}
					}
					?>
				</div>	
			</div>	
		</div>	
	</div>	
</div>		
<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.right_sidebar');
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.footer');
?>