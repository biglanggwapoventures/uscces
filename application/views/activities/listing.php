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
                        <th>Starts at</th>
                        <th>Ends at</th>
                        <th>Facilitators</th>
                        <th>Population</th>
                        <th>Created by</th>
                        <th>Status</th>
                        <th class="text-right"><a href="javascript:void(0)" class="btn btn-xs btn-flat btn-info" data-toggle="modal" data-target="#advanced-search"><i class="fa fa-search"></i> Search</a></th>
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
                            <td><?= format_date($row['start_datetime'], 'd-M-Y h:i a')?></td>
                            <td><?= format_date($row['datetime'], 'd-M-Y h:i a')?></td>
                            <td>
                                <?php if(isset($row['facilitators'])):?>
                                    <?= implode($row['facilitators'], ',<br>')?>
                                <?php else:?>
                                    -
                                <?php endif;?>
                            </td>
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
                            <td>
                                <?php if(user_type(USER_TYPE_SUPERUSER)):?>
                                    <a href="<?= "{$url}/edit/{$row['id']}"?>" class="btn btn-xs btn-flat btn-info" ><i class="fa fa-pencil"></i> Edit
                                    </a>
                                <?php else:?>
                                     <a href="<?= "{$url}/view/{$row['id']}"?>" class="btn btn-xs btn-flat btn-info" ><i class="fa fa-eye"></i> View
                                    </a>
                                <?php endif;?>
                            </td>
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
            <form method="GET" action="<?= current_url()?>">
                <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="<?= $this->input->get('name')?>"/>
                        </div>
                        <div class="form-group">
                            <label>Search date start</label>
                            <input type="text" name="start_date" class="form-control datepicker" value="<?= $this->input->get('start_date')?>"/>
                        </div>
                        <div class="form-group">
                            <label>Search date end</label>
                            <input type="text" name="end_date" class="form-control datepicker" value="<?= $this->input->get('end_date')?>"/>
                        </div>
                         <div class="form-group">
                            <label>Status</label>
                            <?= form_dropdown('category', ['' => '-ALL ACTIVITIES-', APPROVED_ACTIVITIES => 'Approved activities', PAST_ACTIVITIES => 'Past activities', PROPOSED_ACTIVITIES => 'Pending proposals', DECLINED_ACTVITIES => 'Declined proposals'], $this->input->get('category'), 'class="form-control"')?>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-flat">Search</button>
                    <button type="reset" class="btn btn-default btn-flat">Reset</button>
                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>   