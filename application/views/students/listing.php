<?php $url = base_url('students') ?>
<section class="content-header">
  <h1>
    Students
    <a href="<?= "{$url}/create"?>" class="btn btn-flat btn-default  btn-sm pull-right"><i class="fa fa-plus"></i> New student</a>
  </h1>
 
</section>
 <section class="content">
 	<div class="box box-solid">
    <div class="box-body table-responsive no-padding">
      <table class="table table-hover" id="listing" data-get="<?= "{$url}/ajax_all"?>">
      	<thead>
      		<tr>
  					<th>ID #</th>
  					<th>Full name</th>
            <th>Course</th>
            <th>CES status</th>
            <th class="text-right"><a data-toggle="modal" data-target="#search" class="btn btn-info btn-xs btn-flat" href="javascript:void(0)"><i class="fa fa-search"></i> Search</a></th>
      		</tr>
  		</thead>
        <tbody>
        <?php if(isset($items) && is_array($items)):?>
          <?php foreach($items AS $row):?>
              <tr data-pk="<?= $row['id']?>">
                  <td><a href="<?= base_url("profile/?id={$row['id']}")?>"> <?= $row['username'] ?> </a></td>
                <td> <?= "{$row['lastname']}, {$row['firstname']} {$row['middlename']}" ?> </td>
                <td> <?= course($row['course'], TRUE).'-'.$row['year_level']?></td>
                <td>
                  <?php if($row['status']):?>
                    <span class="label label-success"><i class="fa fa-check"></i> Cleared</span>
                  <?php else:?>
                    <span class="label label-warning"><i class="fa fa-exclamation-circle"></i> Lacking</span>
                  <?php endif;?>
                </td> 
                <td>
                    <a data-target="#confirm" data-toggle="modal" href="javascript:void(0)" class="text-info"><i class="fa fa-refresh"></i> Reset PW</a>
                </td>
              </tr>
          <?php endforeach;?>
          <?php if(empty($items)):?>
            <tr><td colspan="6" class="text-center">No results found</td></tr>
          <?php endif;?>
        <?php endif;?>
        <?php if(!isset($items)):?>
          <tr><td colspan="6" class="text-center">Click on search at the top to start.</td></tr>
        <?php endif;?>
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


<!-- Modal -->
<div class="modal fade" id="search" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel1">Search students</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label>Firstname</label>
                <input class="form-control" name="firstname" type="text" value="<?= $this->input->get('firstname') ?>"/>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label>Middlename</label>
                <input class="form-control" name="middlename" type="text" value="<?= $this->input->get('middlename') ?>"/>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label>Lastname</label>
                <input class="form-control" name="lastname" type="text" value="<?= $this->input->get('lastname') ?>"/>
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label>ID Number</label>
                <input class="form-control" name="id_number" type="text" value="<?= $this->input->get('id_number') ?>"/>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label>Year level</label>
                <input class="form-control" type="number" min="1" max="4" name="year_level" value="<?= $this->input->get('year_level') ?>"/>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label>Course</label>
                <?= form_dropdown('course', ['' => '', 'it' => 'BS IT', 'ict' => 'BS ICT', 'cs' => 'BS CS'], $this->input->get('course'), 'class="form-control"')?>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label>Status</label>
                <?= form_dropdown('status', ['' => '', 0 => 'Lacking', 1 => 'Completed'], $this->input->get('status'), 'class="form-control"')?>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer clearfix">
          <button class="pull-left btn btn-default btn-flat" type="reset">Reset fields</button>
          <button class="btn btn-success btn-flat" type="submit">Search</button>
          <a data-dismiss="modal" class="btn btn-default btn-flat">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>