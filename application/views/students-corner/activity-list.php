<?php $url = base_url('index.php/students-corner/activities/') ?>
<section class="content-header">
    <h1>
        Activities
        <a href="<?= "{$url}/propose" ?>" class="btn btn-flat btn-default  btn-sm pull-right"><i class="fa fa-plus"></i> New activity</a>
    </h1>

</section>
<section class="content">
    <div class="box">
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover" id="listing" data-get="<?= "{$url}/ajax_all" ?>">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Date &amp; time</th>
                        <th>Facilitator</th>
                        <th>Population</th>
                        <th>Created by</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($items AS $i):?>
                        <tr>
                            <td><?= $i['name']?></td>
                            <td><?= $i['location']?></td>
                            <td><?= date('d-M-Y h:i a', strtotime($i['datetime']))?></td>
                            <td><?= $i['facilitator']?></td>
                            <td>0/<?= $i['population']?></td>
                            <td><?= "{$i['lastname']}, {$i['firstname']} {$i['middlename']}"?></td>
                            <td>
                                <a href="<?= "{$url}/view/{$i['id']}"?>" class="btn btn-flat btn-xs btn-primary"><i class="fa fa-newspaper-o"></i> View</a>
                            </td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div><!-- /.box-body -->
    </div>
</section><!-- /.content -->