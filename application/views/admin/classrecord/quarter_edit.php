<style type="text/css">
    @media print
    {
        .no-print, .no-print *
        {
            display: none !important;
        }
    }
</style>
<div class="content-wrapper" style="min-height: 946px;">  
    <section class="content-header">
        <h1><i class="fa fa-mortar-board"></i> <?php echo $this->lang->line('academics'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('quarter', 'can_add') || $this->rbac->hasPrivilege('quarter', 'can_edit')) { ?>        
                <div class="col-md-4">          
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('edit_quarter'); ?></h3>
                        </div>
                        <form id="form1" action="<?php echo site_url('admin/classrecord/quarter_edit/'.$id) ?>"  id="quarterform" name="quarterform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg') ?>
                                <?php } ?>     
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group">
                                    <label for="name"><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="name" name="name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('name', $quarter['name']); ?>" />
                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                </div>
                               
                                <div class="form-group"><br>
                                    <label for="code"><?php echo $this->lang->line('code'); ?></label>
                                    <input id="code" name="code" placeholder="" type="text" class="form-control" value="<?php echo set_value('code', $quarter['code']); ?>" />
                                    <span class="text-danger"><?php echo form_error('code'); ?></span>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
            <div class="col-md-<?php if ($this->rbac->hasPrivilege('quarter', 'can_add')) { echo "8"; } else { echo "12"; } ?>">            
                <div class="box box-primary" id="sublist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('quarter_list'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('quarter_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover example nowrap">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('code'); ?></th>
                                        </th>
                                        <th class="text-right no-print"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    foreach ($quarterlist as $quarter) {
                                        ?>
                                        <tr>
                                            <td class="mailbox-name"> <?php echo $quarter['name'] ?></td>
                                            <td class="mailbox-name"><?php echo $quarter['code'] ?></td>
                                            <td class="mailbox-date pull-right no-print">
                                                <?php
                                                if ($this->rbac->hasPrivilege('quarter', 'can_edit')) {
                                                    ?>
                                                    <a data-placement="left" href="<?php echo base_url(); ?>admin/classrecord/quarter_edit/<?php echo $quarter['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <?php
                                                }
                                                if ($this->rbac->hasPrivilege('quarter', 'can_delete')) {
                                                    ?>
                                                    <a data-placement="left" href="<?php echo base_url(); ?>admin/classrecord/quarter_delete/<?php echo $quarter['id'] ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    $count++;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> 

        </div> 
    </section>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        //
    });
</script>

