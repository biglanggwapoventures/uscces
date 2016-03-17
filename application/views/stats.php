<section class="content-header">
  <h1>
    Statistics
  </h1>
 
</section>
 <section class="content">
 	<div class="row">
    <div class="col-sm-4">
      <div class="box box-solid">
        <div class="box-body table-responsive no-padding">
          <table class="table">
            <tbody>
              <tr class="active"><td colspan="2" class="text-center text-bold text-uppercase">Summary</td></tr>
              <tr><td>Total number of activities</td><td><?= $total_activity_count?></td></tr>
              <tr>
                <td>Total number of unique students joined</td>
                <td><?= preset($participants, USER_TYPE_STUDENT, 0)?></td>
              </tr>
              <tr>
                <td>Total number of unique facilitators joined</td>
                <td><?= preset($participants, USER_TYPE_FACILITATOR, 0) + preset($participants, USER_TYPE_SUPERUSER, 0)?></td>
              </tr>
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div>
    </div>
    <div class="col-sm-4">
      <div class="box box-solid">
        <div class="box-body table-responsive no-padding">
          <table class="table">
            <tbody>
              <tr class="active"><td colspan="2" class="text-center text-bold text-uppercase">Area of the activities</td></tr>
              <?php foreach($area_stats AS $area):?>
                <tr><td><?= $area['name']?></td><td><?= $area['total']?></td></tr>
              <?php endforeach;?>
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div>
    </div>
    <div class="col-sm-4">
      <div class="box box-solid">
        <div class="box-body table-responsive no-padding">
          <table class="table">
            <tbody>
              <tr class="active"><td colspan="2" class="text-center text-bold text-uppercase">Nature of the activities</td></tr>
              <?php foreach($nature_stats AS $nature):?>
                <tr><td><?= $nature['name']?></td><td><?= $nature['total']?></td></tr>
              <?php endforeach;?>
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div>
    </div>
  </div>
</section><!-- /.content -->