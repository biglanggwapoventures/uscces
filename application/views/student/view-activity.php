<style type="text/css">
  table#metadata > tbody > tr > td:nth-child(2){
    font-weight: bold;
  }
  .description{
    padding: 15px;
    margin-bottom: 20px;
    border: 2px dotted black;
  }
</style>
<?php $url = base_url('student') ?>
<section class="content-header">
  <h1>
    Browse Activities
  </h1>
 
</section>
 <section class="content">
  <div class="box box-solid">
    <div class="box-header with-border">
      <h3 class="box-title"><?= $data['name'] ?></h3>
    </div><!-- /.box-header -->
    <div class="box-body">
      
      <div class="description"><?= $data['description']?></div>
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <table class="table table-bordered" id="metadata">
            <thead><tr class="active"><th colspan="2" class="text-center">Activity metadata</th></tr></thead>
            <tbody>
              <tr><td>Activity date &amp; time</td><td><?= date('d-M-Y h:i a', strtotime($data['datetime']))?></td></tr>
              <tr><td>Location</td><td><?= $data['location']?></td></tr>
              <tr><td>Nature</td><td><?= $data['nature']?></td></tr>
              <tr><td>Area</td><td><?= $data['area']?></td></tr>
              <tr>
                <td>Facilitator(s)</td>
                <td>
                    <?php if($facilitators):?>
                        <ul class="list-unstyled">
                          <?php foreach($facilitators AS $faci):?>
                            <li>
                              <?= $faci['fullname']?>
                              <?php if($faci['mobile']):?>
                                (<?= $faci['mobile']?>)
                              <?php endif;?>
                            </li>
                          <?php endforeach;?>
                        </ul>
                        
                    <?php else:?>
                      This activity has no facilitators
                    <?php endif;?>
                </td>
              </tr>
              <tr><td>Population</td><td><?= $data['student_count']?> / <?= $data['population']?></td></tr>
              <tr>
                <td colspan="2" class="text-center">
                  <?php if($is_participant):?>
                   <p class="text-bold text-center bg-green">You are registered in this activity.</p>
                   <?php $today = date_create(date('Y-m-d'));?>
                   <?php $activity_date = date_create(date('Y-m-d', strtotime($data['datetime'])));?>
                   <?php $diff = date_diff($today, $activity_date)->format('%a')?>
                   <?php if($diff >= 3):?>
                      <a id="leave" data-pk="<?= $data['id']?>" data-action-url="<?= "{$url}/leave_activity"?>" class="btn btn-danger btn-flat"><i class="fa fa-minus-circle"></i> Leave this activity
                   <?php endif;?>
                  <?php else:?>
                      <?php if($data['student_count'] < $data['population']):?>
                        <a data-toggle="modal" data-target="#input-mobile" class="btn btn-success btn-flat btn-block"><i class="fa fa-plus"></i> Join this activity
                      <?php else:?>
                        <p class="text-bold text-center bg-yellow">This activity is already full.</p>
                      <?php endif;?>
                  <?php endif;?>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div><!-- /.box-body -->
    <div class="box-footer clearfix">
      <a href="<?= "{$url}/view_activities" ?>" class="btn btn-default cancel pull-right btn-flat">Back</a>
    </div><!-- /.box-footer -->
  </div>
</section><!-- /.content -->
<?php if(!$is_participant):?>
  <div class="modal fade" id="input-mobile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <?php $mobile = $this->session->userdata('mobile'); ?>
          <?php if(!$mobile):?>
            <div class="alert alert-warning">We noticed that you have not given us your contact number. For us to reach you, please input your contact number in the field below.</div>
          <?php else:?>
            <div class="alert alert-info">Please verify that the contact number provided below is still active.</div>
          <?php endif;?>
          <div class="alert alert-danger hidden">
            <ul class="list-unstyled">
              
            </ul>
          </div>
          <div class="form-group">
            <input type="text" class="form-control" name="mobile" value="<?= $mobile?>"/>
          </div>
        </div>
      <div class="modal-footer">
          <a class="btn btn-success btn-flat" data-pk="<?= $data['id']?>"  data-action-url="<?= "{$url}/join_activity"?>" id="join">Submit</a>
          <a data-dismiss="modal" class="btn btn-default btn-flat">Cancel</a>
      </div>
      </div>
    </div>
  </div>
<?php endif;?>