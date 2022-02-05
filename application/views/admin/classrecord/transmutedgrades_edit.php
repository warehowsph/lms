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
            <?php if ($this->rbac->hasPrivilege('transmuted_grades', 'can_add') || $this->rbac->hasPrivilege('quarter', 'can_edit')) { ?>        
                <div class="col-md-4">          
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('edit_transmuted_grade'); ?></h3>
                        </div>
                        <form id="form1" action="<?php echo site_url('admin/classrecord/transmutedgrades_edit/'.$id) ?>"  id="transmutedgradesform" name="transmutedgradesform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg') ?>
                                <?php } ?>     
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group">
                                    <label for="grade"><?php echo $this->lang->line('grade'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="grade" name="grade" placeholder="" step="any" min="0" max="100" class="form-control" value="<?php echo set_value('grade', $transmutedgrade['grade']); ?>" />
                                    <span class="text-danger"><?php echo form_error('grade'); ?></span>
                                </div>
                               
                                <div class="form-group">
                                    <label for="transmute_from"><?php echo $this->lang->line('transmute_from'); ?></label><small class="req"> *</small>
                                    <input id="transmute_from" name="transmute_from" placeholder="" type="number" step="any" min="0" max="100" class="form-control"  value="<?php echo set_value('transmute_from', $transmutedgrade['transmute_from']); ?>" />
                                    <span class="text-danger"><?php echo form_error('transmute_from'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="transmute_to"><?php echo $this->lang->line('transmute_to'); ?></label><small class="req"> *</small>
                                    <input id="transmute_to" name="transmute_to" placeholder="" type="number" step="any" min="0" max="100" class="form-control"  value="<?php echo set_value('transmute_to', $transmutedgrade['transmute_to']); ?>" />
                                    <span class="text-danger"><?php echo form_error('transmute_to'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="remarks"><?php echo $this->lang->line('remarks'); ?></label><small class="req"> *</small>
                                    <input id="remarks" name="remarks" placeholder="" type="text" class="form-control"  value="<?php echo set_value('remarks', $transmutedgrade['remarks']); ?>" />
                                    <span class="text-danger"><?php echo form_error('remarks'); ?></span>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
            <div class="col-md-<?php if ($this->rbac->hasPrivilege('transmuted_grades', 'can_add')) { echo "8"; } else { echo "12"; } ?>">            
                <div class="box box-primary" id="sublist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('transmuted_grade_list'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('transmuted_grade_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover example nowrap">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('grade'); ?></th>
                                        <th><?php echo $this->lang->line('transmuted_grade'); ?></th>
                                        <th><?php echo $this->lang->line('remarks'); ?></th>
                                        </th>
                                        <th class="text-right no-print"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    foreach ($transmutedgradelist as $transmutedgrade) { ?>
                                        <tr>
                                            <td class="mailbox-name"> <?php echo $transmutedgrade['grade'] ?></td>
                                            <?php if ($transmutedgrade['grade'] == 100) { ?>
                                                <td class="mailbox-name"><?php echo $transmutedgrade['grade']?></td>
                                            <?php } else { ?>
                                                <td class="mailbox-name"><?php echo ($transmutedgrade['transmute_from'] .' To '. $transmutedgrade['transmute_to'])?></td>
                                            <?php } ?>
                                            <td class="mailbox-name"><?php echo $transmutedgrade['remarks'] ?></td>
                                            <td class="mailbox-date pull-right no-print">
                                                <?php if ($this->rbac->hasPrivilege('transmuted_grades', 'can_edit')) { ?>
                                                    <a data-placement="left" href="<?php echo base_url(); ?>admin/classrecord/transmutedgrades_edit/<?php echo $transmutedgrade['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <?php
                                                }
                                                if ($this->rbac->hasPrivilege('transmuted_grades', 'can_delete')) { ?>
                                                    <a data-placement="left" href="<?php echo base_url(); ?>admin/classrecord/transmutedgrades_delete/<?php echo $transmutedgrade['id'] ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }

                                    $count++; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> 

        </div> 
    </section>
</div>