<?php $url = base_url('student') ?>
<section class="content-header">
    <h1> Track Proposals </h1>
</section>
<section class="content">
    <div class="box box-solid">
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Date submitted</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($items AS $row):?>
                        <tr data-pk="<?= $row['id']?>">
                            <td><a href="<?= "{$url}/edit_proposed_activity/{$row['id']}"?>"><?= $row['name']?></a></td>
                            <td><?= date('d-M-Y', strtotime($row['created_at']))?></td>
                            <td>
                                <?php if($row['status'] === 'a'):?>
                                    <span class="label label-success">Approved</span>
                                <?php elseif($row['status'] === 'p'):?>
                                    <span class="label label-warning">Pending</span>
                                <?php else:?>
                                    <span class="label label-danger">Declined</span>
                                <?php endif;?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                    <?php if(empty($items)):?>
                        <tr><td colspan="3" class="text-center">You do not have any proposed activities</td></tr>
                    <?php endif;?>
                </tbody>
            </table>
        </div><!-- /.box-body -->
    </div>
</section>