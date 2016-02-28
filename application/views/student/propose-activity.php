<?php $url = base_url('student') ?>
<section class="content-header">
  <h1>
    Propose Activity
  </h1>
 
</section>
 <section class="content">
 <div class="box box-solid">
    <div class="box-header with-border">
      <h3 class="box-title"><?= $form_title ?></h3>
    </div><!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" method="POST" data-action="<?= $action === ACTION_CREATE ? "{$url}/store_proposed_activity" : "{$url}/update_proposed_activity/{$data['id']}" ?>">
      <div class="box-body">
        <div class="alert alert-info">
            <p>Fields marked with <i class="fa fa-asterisk text-danger"></i> (asterisk) are required.</p>
        </div>
        <?php if(preset($data, 'status', '') === 'd'):?>
          <div class="callout callout-warning">
          This proposal has been declined for the reason:
          <p><b><?= $data['decline_reason']?></b></p>
          </div>
        <?php elseif(preset($data, 'status', '') === 'p'):?>
           <div class="callout callout-info">
            This proposal is still pending for approval.
            </div>
        <?php elseif(preset($data, 'status', '') === 'a'):?>
            <div class="callout callout-success">
              This proposal is approved!
            </div>
        <?php endif;?>
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
      </div><!-- /.box-body -->
      <div class="box-footer clearfix">
        
        <a href="<?= "{$url}/track_proposals" ?>" class="btn btn-default cancel pull-right btn-flat">Cancel</a>
        <?php if(in_array(preset($data, 'status', ''), ['', 'p'])):?>
          <button type="submit" class="btn btn-success btn-flat">Submit</button>
        <?php endif;?>
      </div><!-- /.box-footer -->
    </form>
  </div>
</section><!-- /.content -->