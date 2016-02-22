<section class="content-header">
  <h1>
    My Status
  </h1>
</section>

<!-- Main content -->
<section class="content">

  <div class="row">
    <div class="col-md-3">

      <!-- Profile Image -->
      <div class="box box-solid">
        <div class="box-body box-profile">
          <img class="profile-user-img img-responsive img-circle" src="<?= base_url('assets/img/display-photo-placeholder.png')?>" alt="User profile picture">
          <h3 class="profile-username text-center"><?= "{$user['firstname']} {$user['middlename']} {$user['lastname']}" ?></h3>
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b>Status</b> 
              <?php if($user['status'] ):?>
              <a class="pull-right"><span class="label label-success"><i class="fa fa-check"></i> Cleared</span></a>
              <?php else:?>
              <a class="pull-right"><span class="label label-danger"><i class="fa fa-times"></i> Lacking</span></a>
              <?php endif;?>
            </li>
            <li class="list-group-item">
              <b>CES Attended</b> <a class="pull-right"><?= count($attended)?></a>
            </li>
            <li class="list-group-item">
              <b>CES Proposed</b> <a class="pull-right"><?= $proposed_count?></a>
            </li>
            
          </ul>

        </div><!-- /.box-body -->
      </div><!-- /.box -->
       <?php if($user['type'] === USER_TYPE_STUDENT):?>
        <!-- About Me Box -->
        <div class="box box-solid">
          <div class="box-header with-border">
            <h3 class="box-title">About Me</h3>
          </div><!-- /.box-header -->
          <div class="box-body">
            <strong><i class="fa fa-file-text-o margin-r-5"></i> Talent</strong>
            <p><?= $user['talent'] ? $user['talent'] : '<em>None</em>'?></p>
          </div><!-- /.box-body -->
        </div><!-- /.box -->
      <?php endif;?>
    </div><!-- /.col -->
    <div class="col-md-9">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#basic" data-toggle="tab">Basic information</a></li>
          <li class=""><a href="#history" data-toggle="tab">CES history</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="basic">
            <div class="alert alert-info">
                <p>Fields marked with <i class="fa fa-asterisk text-danger"></i> (asterisk) are required.</p>
            </div>
            <div class="alert alert-danger hidden">
                <ul class="list-unstyled"></ul>
            </div>
            <form class="form-horizontal" data-action="<?= base_url("profile/update/{$user['id']}")?>">
              <!-- ID NUMBER -->
              <div class="form-group">
                <label class="col-sm-2 control-label">ID Number</label>
                <div class="col-sm-9">
                  <p class="form-control-static"><?= $user['username']?></p>
                </div>
              </div>
              <?php if(user_type(USER_TYPE_STUDENT)):?>
                <!-- FIRST NAME -->
                <div class="form-group">
                  <label class="col-sm-2 control-label">First Name</label>
                  <div class="col-sm-9">
                    <p class="form-control-static"><?= $user['firstname']?></p>
                  </div>
                </div>
                <!-- MIDDLE NAME -->
                <div class="form-group">
                  <label class="col-sm-2 control-label">Middle Name</label>
                  <div class="col-sm-9">
                    <p class="form-control-static"><?= $user['middlename']?></p>
                  </div>
                </div>
                <!-- LAST NAME -->
                <div class="form-group">
                  <label class="col-sm-2 control-label">Last Name</label>
                  <div class="col-sm-9">
                    <p class="form-control-static"><?= $user['lastname']?></p>
                  </div>
                </div>
              <?php else:?>
                <!-- FIRST NAME -->
                <div class="form-group">
                  <label class="col-sm-2 control-label"><i class="fa fa-asterisk text-danger"></i> First Name</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="firstname" value="<?= $user['firstname']?>">
                  </div>
                </div>
                <!-- MIDDLE NAME -->
                <div class="form-group">
                  <label class="col-sm-2 control-label"><i class="fa fa-asterisk text-danger"></i> Middle Name</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="middlename" value="<?= $user['middlename']?>">
                  </div>
                </div>
                <!-- LAST NAME -->
                <div class="form-group">
                  <label class="col-sm-2 control-label"><i class="fa fa-asterisk text-danger"></i> Last Name</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="lastname" value="<?= $user['lastname']?>">
                  </div>
                </div>
              <?php endif;?>

              <!-- YEAR LEVEL -->
              <?php if($user['type'] === USER_TYPE_STUDENT):?>
              <div class="form-group">
                <label class="col-sm-2 control-label">
                  <?php if(!user_type(USER_TYPE_STUDENT)):?>
                  <i class="fa fa-asterisk text-danger"></i> 
                  <?php endif;?>
                  Year level
                </label>
                <div class="col-sm-2">
                  <?php if(user_type(USER_TYPE_STUDENT)):?>
                    <p class="form-control-static"><?= $user['year_level'] ?></p>
                  <?php else:?>
                    <input type="number" step="1" min="1" min="4" class="form-control" value="<?= $user['year_level'] ?>" />
                  <?php endif;?>
                </div>
              </div>
              <?php endif;?>
              <hr>
              <!-- DATE OF BIRTH -->
              <div class="form-group">
                <label class="col-sm-2 control-label">
                  <?php if(user_type(USER_TYPE_STUDENT)):?>
                    <i class="fa fa-asterisk text-danger"></i> 
                  <?php endif;?>
                  Date of birth
                </label>
                <div class="col-sm-9">
                  <input type="text" class="form-control datepicker" name="dob" value="<?= $user['dob'] ? date('m/d/Y', strtotime($user['dob'])) : ''?>"/>
                  <span class="help-block">eg: 06/20/1995</span>
                </div>
              </div>
              <!-- GENDER -->
              <div class="form-group">
                <label class="col-sm-2 control-label">
                    <?php if(user_type(USER_TYPE_STUDENT)):?>
                      <i class="fa fa-asterisk text-danger"></i> 
                    <?php endif;?>
                    Gender
                </label>
                <div class="col-sm-9">
                  <?= form_dropdown('gender', ['' => '', 'm' => 'Male', 'f' => 'Female'], $user['gender'], 'class="form-control"');?>
                </div>
              </div>
              <?php if($user['type'] === USER_TYPE_STUDENT):?>
                <!-- COURSE -->
                <div class="form-group">
                  <label class="col-sm-2 control-label">
                    <?php if(user_type(USER_TYPE_STUDENT)):?>
                      <i class="fa fa-asterisk text-danger"></i> 
                    <?php endif;?>
                    Course
                  </label>
                  <div class="col-sm-9">
                    <?= course_dropdown('course', $user['course'], 'class="form-control"')?>
                  </div>
                </div>
              <?php endif;?>

              <div class="form-group">
                <label class="col-sm-2 control-label">
                  <?php if(user_type(USER_TYPE_STUDENT)):?>
                    <i class="fa fa-asterisk text-danger"></i> 
                  <?php endif;?> 
                  Mobile
                </label>
                <div class="col-sm-9">
                  <input type="text" name="mobile" value="<?= $user['mobile']?>" class="form-control" />
                  <span class="help-block">eg: 09234567890</span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">
                  <?php if(user_type(USER_TYPE_STUDENT)):?>
                    <i class="fa fa-asterisk text-danger"></i> 
                  <?php endif;?> 
                  Email
                </label>
                <div class="col-sm-9">
                  <input type="email" name="email" value="<?= $user['email']?>" class="form-control" />
                </div>
              </div>
              <hr>
              <div class="form-group">
                <label for="talents" class="col-sm-2 control-label">Password</label>
                <div class="col-sm-9">
                  <input type="password" name="password" class="form-control" />
                  <span class="help-block">Leave blank to remain unchanged</span>
                </div>
              </div>
              <div class="form-group">
                <label for="talents" class="col-sm-2 control-label">Confirm</label>
                <div class="col-sm-9">
                  <input type="password" name="confirm_password" class="form-control" />
                </div>
              </div>
              <hr>
              <?php if($user['type'] === USER_TYPE_STUDENT):?>
                <div class="form-group">
                  <label for="talents" class="col-sm-2 control-label">Talent</label>
                  <div class="col-sm-9">
                    <textarea class="form-control" id="talents" name="talent"><?= $user['talent']?></textarea>
                  </div>
                </div>
              <?php endif;?>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-success btn-flat">Submit</button>
                </div>
              </div>
            </form>
          </div><!-- /.tab-pane -->
          <div class="tab-pane" id="history">
            <table class="table table-condensed table-hover">
              <thead>
                <tr><th>Name</th><th>Datetime</th><th>Location</th><th></th></tr>
              </thead>
              <tbody>
                <?php foreach($attended AS $row):?>
                  <tr>
                    <td><?= $row['name']?></td>
                    <td><?= date('d-M-Y H:i a', strtotime($row['datetime']))?></td>
                    <td><?= $row['location']?></td>
                  </tr>
                <?php endforeach;?>
                <?php if(!$attended):?>
                  <tr><td colspan="3" class="text-center">You have not participated in any activities.</td></tr>
                <?php endif;?>
              </tbody>
            </table>
          </div>
        </div><!-- /.tab-content -->
      </div><!-- /.nav-tabs-custom -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section>    