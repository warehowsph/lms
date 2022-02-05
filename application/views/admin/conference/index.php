<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-gears"></i> <small class="pull-right">
                <a type="button" class="btn btn-primary btn-sm"><?php echo $this->lang->line('setting') ?></a>
            </small>
        </h1>
    </section>   
    <section class="content">
        <div class="row">
            <div class="col-md-12">             
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-envelope"></i> <?php echo $this->lang->line('setting') ?></h3>
                    </div>   
                    <form id="form1" action="<?php echo base_url() ?>admin/conference"   name="employeeform" class="form-horizontal form-label-left" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php echo $this->session->flashdata('msg') ?>
                            <?php } ?>   
                            <?php echo $this->customlib->getCSRF(); ?>
                            <input type="hidden" name="id" value="<?php echo set_value('id', $setting->id); ?>">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exampleInputEmail1">
                                    <?php echo $this->lang->line('zoom_api_key'); ?><small class="req"> *</small>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="name" name="zoom_api_key" placeholder="" type="text" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('zoom_api_key', $setting->zoom_api_key); ?>" />
                                    <span class="text-danger"><?php echo form_error('zoom_api_key'); ?></span>
                                </div>
                            </div> 
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exampleInputEmail1">
                                    <?php echo $this->lang->line('zoom_api_secret'); ?><small class="req"> *</small>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="name" name="zoom_api_secret" placeholder="" type="text" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('zoom_api_secret', $setting->zoom_api_secret); ?>" />
                                    <span class="text-danger"><?php echo form_error('zoom_api_secret'); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="col-md-6 col-sm-6 col-xs-6 col-md-offset-3">
                                <?php
                                if ($this->rbac->hasPrivilege('zoom_api_setting', 'can_edit')) {
                                    ?>
                                    <button type="submit" class="btn btn-info pull-left"><?php echo $this->lang->line('save'); ?></button>
                                    <?php
                                }
                                ?>                           
                            </div>
                            <div class="pull-right"><?php echo $this->lang->line('version') . " " . $version; ?></div>
                        </div>
                    </form>
                </div>
            </div>           
        </div>
    </section>
</div>