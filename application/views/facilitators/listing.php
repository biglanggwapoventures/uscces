<?php $url = base_url('facilitators') ?>
<section class="content-header">
    <h1>
        Facilitators
        <a href="<?= "{$url}/create" ?>" class="btn btn-flat btn-default  btn-sm pull-right"><i class="fa fa-plus"></i> New facilitator</a>
    </h1>

</section>
<section class="content">
    <div class="box box-solid">
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover" id="listing" data-get="<?= "{$url}/ajax_all" ?>">
                <thead>
                    <tr>
                        <th>ID #</th>
                        <th>Login status</th>
                        <th>Name</th>
                        <th>Email</th>
                        
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                   <?php foreach($items AS $row):?>
                        <tr data-pk="<?= $row['id']?>">
                            <td><a href="<?= base_url("profile?id={$row['id']}")?>"><?= $row['username']?></a></td>
                            <td>
                                <?php if(intval($row['is_locked'])):?>
                                    <span class="label label-warning"><i class="fa fa-exclamation-circle"></i> Locked</span>
                                <?php else:?>
                                    <span class="label label-success"><i class="fa fa-check"></i> Active</span>
                                <?php endif;?>
                            </td>
                            <td> <?= "{$row['lastname']}, {$row['firstname']} {$row['middlename']}" ?> </td>
                            <td><?= $row['email'] ?></td>
                            
                            <td>
                                <a data-target="#confirm" data-toggle="modal" href="javascript:void(0)" class="text-info"><i class="fa fa-refresh"></i> Reset PW</a>
                            </td>
                        </tr>
                   <?php endforeach; ?>
                </tbody>
            </table>
        </div><!-- /.box-body -->
    </div>
</section><!-- /.content -->

<!-- Modal -->
<div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Confirm action</h4>
      </div>
      <div class="modal-body">
            <input type="hidden" data-name="reset-pw-url" data-value="<?= "{$url}/reset_password"?>" disabled="disabled">
          <p class="lead text-info text-center" data-text-init="Are you sure?"></p>
      </div>
    <div class="modal-footer">
        <a class="btn btn-info btn-flat" id="confirm-button">Yes</a>
        <a data-dismiss="modal" class="btn btn-default btn-flat">Go back</a>
    </div>
    </div>
  </div>
</div>