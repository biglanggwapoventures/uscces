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
    <form class="form-horizontal">
      <div class="box-body">
        <div class="form-group">
          <label class="col-sm-2 control-label">Name</label>
          <div class="col-sm-8">
              <p class="form-control-static"><?= $activity['name'] ?></p>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">Description</label>
          <div class="col-sm-8">
              <p class="form-control-static"><?= $activity['description'] ?></p>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">Location</label>
          <div class="col-sm-8">
            <p class="form-control-static"><?= $activity['location'] ?></p>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">Date &amp; time</label>
          <div class="col-sm-8">
            <p class="form-control-static"><?= date('Y-m-d h:i a', strtotime($activity['datetime'])) ?></p>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">Population</label>
          <div class="col-sm-8">
            <p class="form-control-static"><?= "{$activity['participants_count']}/{$activity['population']}"?></p>
          </div>
        </div>
        <hr>
        <div class="form-group">
          <label class="col-sm-2 control-label">Nature of the activity</label>
          <div class="col-sm-8">
              <?php $natures = (['' => ''] + array_column($program_natures, 'name', 'id'))?>
              <p class="form-control-static"><?= $natures[$activity['nature_id']]?></p>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">Activity area</label>
          <div class="col-sm-8">
              <?php $areas = (['' => ''] + array_column($program_areas, 'name', 'id'))?>
              <p class="form-control-static"><?= $areas[$activity['area_id']]?></p>
          </div>
        </div>
      </div><!-- /.box-body -->
      <div class="box-footer clearfix">
        
        <?php if($request_status && $request_status['status'] !== 'w'):?>
          <a class="btn btn-warning btn-flat process" 
              data-url="<?= "{$url}/ajax_withdraw_activity" ?>"
              data-activity-id="<?= isset($activity['id']) ? $activity['id'] : '' ?>">Withdraw from activity
          </a>
        <?php else:?>
          <?php if($activity['participants_count'] >= $activity['population']):?>
            <a class="btn btn-success btn-flat disabled">This activity is already full</a>
          <?php else:?>
            <a class="btn btn-success btn-flat process" 
              data-url="<?= "{$url}/ajax_request_join_activity" ?>"
              data-activity-id="<?= isset($activity['id']) ? $activity['id'] : '' ?>">Request to join
            </a>
          <?php endif;?>
        <?php endif;?>
        <a href="<?=$url?>" class="btn btn-default cancel btn-flat pull-right">Go back</a>
      </div><!-- /.box-footer -->
    </form>
  </div>
</section><!-- /.content -->
<div class="modal fade modal-success" id="loading" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Message</h4>
            </div>
            <div class="modal-body">
                <p class="text-center"></p>
            </div>
            <div class="modal-footer hidden">
                <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>       