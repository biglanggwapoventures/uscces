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
<?php $url = base_url('activities') ?>
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
              <tr><td>Starts at</td><td><?= format_date($data['start_datetime'], 'd-M-Y h:i a')?></td></tr>
              <tr><td>Ends at</td><td><?= format_date($data['datetime'], 'd-M-Y h:i a')?></td></tr>
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
                   <p class="text-bold text-center bg-green">You are currently facilitating this activity.</p>
                      <a id="leave" data-pk="<?= $data['id']?>" data-action-url="<?= "{$url}/leave_activity"?>" class="btn btn-danger btn-flat"><i class="fa fa-minus-circle"></i> Leave this activity
                  <?php else:?>
                      <?php if($facilitators_count < MAX_FACI_LIMIT_PER_ACTIVITY):?>
                        <a data-action-url="<?= "{$url}/join_activity"?>" data-pk="<?= $data['id']?>" id="join" class="btn btn-success btn-flat btn-block"><i class="fa fa-plus" ></i> Facilitate this activity
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
      <a href="<?= "{$url}" ?>" class="btn btn-default cancel pull-right btn-flat">Back</a>
    </div><!-- /.box-footer -->
  </div>
</section><!-- /.content -->