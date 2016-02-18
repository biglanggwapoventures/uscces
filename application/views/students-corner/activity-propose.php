<?php $url = base_url('index.php/students-corner/activities/') ?>
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
    <form class="form-horizontal" method="post" action="<?= "{$url}/propose"?>">
      <div class="box-body">
        <?php $errors = validation_errors() ?>
        <?php if(validation_errors($errors)):?>
          <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
              <div class="alert alert-danger">
                <h4><i class="icon fa fa-ban"></i> Ooops! Please review your input.</h4>
                <?= $errors?>
              </div>
            </div>
          </div>
        <?php endif;?>
        <div class="form-group">
          <label class="col-sm-2 control-label">Name</label>
          <div class="col-sm-8">
              <input type="text" class="form-control" name="name" value="<?= preset($activity, 'name', '')?>" />
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">Description</label>
          <div class="col-sm-8">
              <textarea class="form-control" rows="6" name="description"><?= preset($activity, 'description', '')?></textarea>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">Location</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" name="location" value="<?= preset($activity, 'location', '')?>" />
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">Date &amp; time</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" name="datetime" value="<?= isset($activity['datetime']) ? $activity['datetime'] : ''?>" />
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">Population</label>
          <div class="col-sm-8">
            <input type="number" class="form-control" name="population" min="1" value="<?= preset($activity, 'population', '')?>"/>
          </div>
        </div>
        <hr>
        <div class="form-group">
          <label class="col-sm-2 control-label">Nature of the activity</label>
          <div class="col-sm-8">
            <?= form_dropdown('nature_id', ['' => ''] + array_column($program_natures, 'name', 'id'), preset($activity, 'nature_id', ''), 'class="form-control"')?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">Activity area</label>
          <div class="col-sm-8">
            <?= form_dropdown('area_id', ['' => ''] + array_column($program_areas, 'name', 'id'), preset($activity, 'area_id', ''), 'class="form-control"')?>
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