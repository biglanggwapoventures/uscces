<?php $url = base_url('activities') ?>
<section class="content-header">
    <h1> Activities
        <a href="<?= "{$url}/create" ?>" class="btn btn-flat btn-default  btn-sm pull-right"><i class="fa fa-plus"></i> New activity</a>
    </h1>
</section>
<section class="content">
    <div class="box box-solid">
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover" id="listing" data-get="<?= "{$url}/ajax_all" ?>">
                <thead>
                    <tr>
                        <th>Name</th>   
                        <th>Date &amp; time</th>
                        <th>Facilitators</th>
                        <th>Population</th>
                        <th>Created by</th>
                        <th>Status</th>
                        <th class="text-center"><a href="javascript:void(0)" data-toggle="modal" data-target="#advanced-search"><i class="fa fa-search"></i> Search</a></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($items AS $row):?>
                        <tr data-pk="<?= $row['id']?>">
                            <td>
                                <?php if($row['status'] === 'a'):?>
                                    <a href="<?="{$url}/view_participants/{$row['id']}"?>"><?= $row['name']?></a>
                                <?php else:?>
                                    <?= $row['name']?>
                                <?php endif;?>
                            </td>
                            <td><?= date('d-M-Y h:i a', strtotime($row['datetime']))?></td>
                            <td><?= "" ?></td>
                            <td><?= "{$row['student_count']}/{$row['population']}"?></td>
                            <td><?= $row['created_by']?></td>
                            <td>
                                <?php if($row['status'] === 'a'):?>
                                    <span class="label label-success">Approved</span>
                                <?php elseif($row['status'] === 'p'):?>
                                    <span class="label label-warning">Pending</span>
                                <?php else:?>
                                    <span class="label label-danger">Declined</span>
                                <?php endif;?>
                            </td>
                            <td><a href="<?= "{$url}/edit/{$row['id']}"?>" class="btn btn-xs btn-flat btn-info" ><i class="fa fa-pencil"></i> Edit</a></td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div><!-- /.box-body -->
    </div>
</section><!-- /.content -->
<div class="modal fade" id="activity-options" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Activity options</h4>
            </div>
            <div class="modal-body">
                <a id="opt-view-participants" data-href="" class="btn btn-block btn-default btn-flat options">View participants</a>
                
            </div>
            <div class="modal-footer hidden">
                <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>   
<div class="modal fade" id="advanced-search" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Advanced search</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label>Date &amp; time start</label>
                        <input type="text" name="datetime_start" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label>Date &amp; time end</label>
                        <input type="text" name="datetime_end" class="form-control"/>
                    </div>
                     <div class="form-group">
                        <label>Status</label>
                        <?= form_dropdown('status', ['' => '', 'a' => 'Approved', 'p' => 'Pending', 'd' => 'Declined'], '', 'class="form-control"')?>
                    </div>
                </form>
            </div>
            <div class="modal-footer hidden">
                <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>   