<?php $url = base_url('activities') ?>
<section class="content-header">
  <h1>
    Activities
  </h1>
 
</section>
 <section class="content">
 <div class="box box-solid">
    <div class="box-header with-border">
      <h3 class="box-title"><?= $form_title ?></h3>
    </div><!-- /.box-header -->
    <!-- form start -->
      <div class="box-body">
        <table class="table table-condensed table-hover"
        data-remove-url="<?= "{$url}/ajax_remove_students/{$activity_id}"?>"
        data-mark-cleared-url="<?= "{$url}/ajax_mark_students_cleared/{$activity_id}"?>">
        <thead><tr><th style="width:5%"></th><th>ID #</th><th>Full name</th><th>Mobile</th><th>Status</th><th></th></tr></thead>
        <tbody>
        <?php if(empty($participants)):?>
          <tr><td colspan="5" class="text-center">There are no registered participants for this activity</td></tr>
        <?php endif; ?>
        <?php foreach($participants AS $index => $p):?>
          <tr data-pk="<?= $p['id']?>">
            <td><input type="checkbox" name="set_approved[]" class="check" value="<?= $p['id']?>" /></td>
            <td>
              <input type="hidden" name="participant[<?= $index?>][id]" value="<?= $p['id']?>">
              <?= $p['username']?>
            </td>
            <td><?= "{$p['fullname']}"?></td>
            <td><?= "{$p['contact_number']}"?></td>
            <td>
              <?php if(intval($p['status'])):?>
                  <span class="label label-success"><i class="fa fa-check"></i> Cleared</span>
              <?php else:?>
                  <span class="label label-danger"><i class="fa fa-times"></i> Lacking</span>
              <?php endif;?>
            </td>
            <td>
              <a class="process-one text-danger" data-action="remove" href="javascript:void(0)"><i class="fa fa-times-circle"></i> Remove</a>
              <?php if(!intval($p['status'])):?>
                  <a class="process-one text-success" data-action="mark-cleared" style="margin-left: 5px;" href="javascript:void(0)"><i class="fa fa-check-circle"></i> Mark as cleared</a>
              <?php endif;?>
            </td>
          </tr>
        <?php endforeach;?>
        </tbody>
        </table>
        <p style="margin-top:15px">
          <label style="margin-right:20px;margin-left:5px;"><input type="checkbox" class="check-all" /> Check all </label> 
          With selected: 
          <a class="process-multiple text-danger" data-action="remove" style="margin-left: 5px;" href="javascript:void(0)"><i class="fa fa-times-circle"></i> Remove</a>
          <a class="process-multiple text-success" data-action="mark-cleared" style="margin-left: 5px;" href="javascript:void(0)"><i class="fa fa-check-circle"></i> Mark as cleared</a>
        </p>
      </div><!-- /.box-body -->
      <div class="box-footer clearfix">
        <a class="btn btn-default cancel pull-right btn-flat" href="<?= "{$url}"?>">Go back</a>
      </div><!-- /.box-footer -->
  </div>
</section><!-- /.content -->
