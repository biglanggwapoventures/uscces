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
    <form class="form-horizontal" method="POST" data-action="<?= $action === ACTION_CREATE ? "{$url}/store" : "{$url}/update/{$data['id']}" ?>">
      <div class="box-body">
        <div class="alert alert-info">
            <p>Fields marked with <i class="fa fa-asterisk text-danger"></i> (asterisk) are required.</p>
        </div>
        <div class="alert alert-danger hidden">
            <ul class="list-unstyled"></ul>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><i class="fa fa-asterisk text-danger"></i> Name</label>
          <div class="col-sm-8">
              <input type="text" class="form-control" name="name" value="<?= preset($data, 'name', '')?>" />
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><i class="fa fa-asterisk text-danger"></i> Description</label>
          <div class="col-sm-8">
              <textarea class="textarea"  name="description" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                <?= preset($data, 'description', '')?>
              </textarea>          
            </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><i class="fa fa-asterisk text-danger"></i> Location</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" name="location" value="<?= preset($data, 'location', '')?>" />
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><i class="fa fa-asterisk text-danger"></i> Date &amp; time</label>
          <div class="col-sm-4">
            <input type="text" class="form-control datetimepicker" name="datetime" value="<?= preset($data, 'datetime', '') ? date('m/d/Y H:i A', strtotime($data['datetime'])) : ''?>" />
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><i class="fa fa-asterisk text-danger"></i> Participants limit</label>
          <div class="col-sm-4">
            <input type="number" class="form-control" name="population" min="1" value="<?= preset($data, 'population', '')?>"/>
          </div>
        </div>
        <?php if(user_type(USER_TYPE_SUPERUSER)):?>
          <div class="form-group">
            <label class="col-sm-2 control-label"><i class="fa fa-asterisk text-danger"></i> Facilitators limit</label>
            <div class="col-sm-4">
              <input type="number" class="form-control" name="facilitator_limit" min="2" value="<?= preset($data, 'facilitator_limit', '')?>"/>
            </div>
          </div>
        <?php endif;?>
        <hr>
        <div class="form-group">
          <label class="col-sm-2 control-label"><i class="fa fa-asterisk text-danger"></i> Nature of the activity</label>
          <div class="col-sm-10">
              <?php foreach($program_natures AS $row):?>
                <?php $selected = '';?>
                <?php if(preset($data, 'nature_id', '') === $row['id']):?>
                     <?php $selected = 'checked="checked"';?>
                <?php endif;?>
                <div class="radio">
                  <label><input type="radio" name="nature_id" value="<?= $row['id']?>" <?= $selected?>><?= "<strong>{$row['name']}</strong> <em>{$row['description']}</em>"?></label>
                </div>
              <?php endforeach;?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><i class="fa fa-asterisk text-danger"></i> Area of the activity</label>
          <div class="col-sm-10">
              <?php foreach($program_areas AS $row):?>
                <?php $selected = '';?>
                <?php if(preset($data, 'area_id', '') === $row['id']):?>
                     <?php $selected = 'checked="checked"';?>
                <?php endif;?>
                <div class="radio">
                  <label><input type="radio" name="area_id" value="<?= $row['id']?>" <?= $selected?>><?= "<strong>{$row['name']}</strong>"?></label>
                </div>
              <?php endforeach;?>
          </div>
        </div>
        <hr>
        <?php $status = preset($data, 'status', ''); ?>
        <?php if(user_type(USER_TYPE_SUPERUSER)):?>
        <div class="form-group">
          <label class="col-sm-2 control-label"><i class="fa fa-asterisk text-danger"></i> Status</label>
          <div class="col-sm-8">
            <?= form_dropdown('status', ['' => '', 'a' => 'Approved', 'p' => 'Pending', 'd' => 'Declined'], $status, 'class="form-control"')?>
          </div>
        </div>
          <div class="form-group <?= $status !== 'd' ? 'hidden' : ''?>">
            <label class="col-sm-2 control-label"><i class="fa fa-asterisk text-danger"></i> Reason for declining</label>
            <div class="col-sm-8">
              <textarea class="form-control" name="decline_reason" <?= $status !== 'd' ? 'disabled="disabled"' : ''?>><?= preset($data, 'decline_reason', '')?></textarea>
            </div>
          </div>
        <?php endif;?>
        <div class="form-group">
          <label class="col-sm-2 control-label">&nbsp;</label>
          <div class="col-sm-8">
            <div class="checkbox">
              <label>
                <input type="checkbox" name="facilitate" <?= $is_facilitator ? 'checked="checked"' : ''?> <?= $status === 'd' ? 'disabled="disabled"' : ''?>/> 
                Facilitate this activity
              </label>
            </div>
          </div>
        </div>  
      </div><!-- /.box-body -->
      <div class="box-footer clearfix">
        <a href="<?=$url?>" class="btn btn-default cancel pull-right btn-flat">Cancel</a>
        <button type="submit" class="btn btn-success btn-flat">Submit</button>
      </div><!-- /.box-footer -->
    </form>
  </div>
</section><!-- /.content -->