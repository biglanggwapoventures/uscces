<?php $url = base_url('index.php/activities') ?>
<section class="content-header">
  <h1>
    Approved Activities
  </h1>
</section>
 <section class="content">
 <div class="box box-solid">
    <div class="box-header with-border">
      <h3 class="box-title"><?= $form_title ?></h3>
    </div><!-- /.box-header -->
    <!-- form start -->
      <div class="box-body">
        <table class="table table-condensed table-striped table-hover" 
        data-approve-url="<?= "{$url}/ajax_approve_students/{$activity_id}"?>"
        data-remove-url="<?= "{$url}/ajax_remove_students/{$activity_id}"?>">
        <thead><tr><th style="width:5%"></th><th>ID #</th><th>Full name</th><th></th></tr></thead>
          <tbody>
          <?php if(empty($participants)):?>
            <tr><td colspan="4" class="text-center">There are no join requests for this activity</td></tr>
          <?php endif; ?>
          <?php foreach($participants AS $index => $p):?>
            <tr data-pk="<?= $p['user_id']?>">
              <td><input type="checkbox" name="set_approved[]" class="check" value="<?= $p['user_id']?>" /></td>
              <td>
                <input type="hidden" name="participant[<?= $index?>][id]" value="<?= $p['user_id']?>">
                <?= $p['user_info']['username']?>
              </td>
              <td><?= "{$p['user_info']['lastname']}, {$p['user_info']['firstname']} {$p['user_info']['middlename']}"?></td>
              
              <td>
                <a class="process-one text-success" data-action="approve" href="javascript:void(0)"><i class="fa fa-check-circle"></i> Approve</a> 
                <a class="process-one text-danger" data-action="remove" style="margin-left: 5px;" href="javascript:void(0)"><i class="fa fa-times-circle"></i> Remove</a>
              </td>
            </tr>
          <?php endforeach;?>
          </tbody>
        </table>
        <p style="margin-top:15px">
          <label style="margin-right:20px;margin-left:5px;"><input type="checkbox" class="check-all" /> Check all </label> 
          With selected: 
          <a class="process-multiple text-success" data-action="approve" style="margin-left: 10px;" href="javascript:void(0)"><i class="fa fa-check-circle"></i> Approve</a> 
          <a class="process-multiple text-danger" data-action="remove" style="margin-left: 5px;" href="javascript:void(0)"><i class="fa fa-times-circle"></i> Remove</a>
        </p>

      </div><!-- /.box-body -->
      <div class="box-footer clearfix">
        <a class="btn btn-default cancel pull-right btn-flat" href="<?= $url?>">Go back</a>
        <a class="btn btn-primary btn-flat" href="<?= "{$url}/view_participants/{$activity_id}"?>" ><i class="fa fa-mail-forward"></i> Manage approved students</a>
      </div><!-- /.box-footer -->
  </div>
</section><!-- /.content -->
