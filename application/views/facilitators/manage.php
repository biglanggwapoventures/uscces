<?php $url = base_url('facilitators') ?>
<section class="content-header">
    <h1>Facilitators</h1>
</section>
<section class="content">
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title"><?= $form_title ?></h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" method="post" data-action="<?= $action === ACTION_CREATE ? "{$url}/store" : "{$url}/update/{$data['id']}" ?>">
            <div class="box-body">
                <div class="alert alert-info">
                    <p>Fields marked with <i class="fa fa-asterisk text-danger"></i> (asterisk) are required.</p>
                </div>
                <div class="alert alert-danger hidden">
                    <ul class="list-unstyled"></ul>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> ID #</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="username" value="<?= preset($data, 'username', '') ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Name</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="firstname" value="<?= preset($data, 'firstname', '') ?>" />
                        <span class="help-block">First name</span>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="middlename" value="<?= preset($data, 'middlename', '') ?>" />
                        <span class="help-block">Middle name</span>
                    </div>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" name="lastname" value="<?= preset($data, 'lastname', '') ?>" />
                        <span class="help-block">Last name</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Email</label>
                    <div class="col-sm-6">
                        <input type="email" class="form-control" name="email" value="<?= preset($data, 'email', '') ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Mobile</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="mobile" value="<?= preset($data, 'mobile', '') ?>" />
                        <span class="help-block">eg: 09233887588</span>
                    </div>
                </div>
                <?php if($action === ACTION_UPDATE):?>
                    <hr>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">&nbsp;</label>
                        <div class="col-sm-6">
                            <div class="checkbox"><label><input type="checkbox" name="is_locked" <?= preset($data, 'is_locked', FALSE) ? 'checked="checked"' : '' ?>/> Lock this facilitator (Prevents login)</label></div>
                        </div>
                    </div>
                <?php endif;?>
            </div><!-- /.box-body -->
            <div class="box-footer clearfix">
                <a href="<?= $url ?>" class="btn btn-default cancel pull-right btn-flat">Cancel</a>
                <button type="submit" class="btn btn-success btn-flat">Submit</button>
            </div><!-- /.box-footer -->
        </form>
    </div>
</section><!-- /.content -->