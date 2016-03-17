<?php $url = base_url('student') ?>
<section class="content-header">
    <h1> Browse Activities </h1>
</section>
<section class="content">
    <div class="box box-solid">
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Facilitators</th>
                        <th>Population</th>
                        <th><a class="hidden" href="javascript:void(0)" data-toggle="modal" data-target="#advanced-search"><i class="fa fa-search"></i> Search</a></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($items AS $row):?>
                        <tr data-pk="<?= $row['id']?>">
                            <td><a href="<?= "{$url}/view_activity/{$row['id']}"?>"><?= $row['name']?></a></td>
                            <td><?= $row['location']?></td>
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
                            <td></td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div><!-- /.box-body -->
    </div>
</section>