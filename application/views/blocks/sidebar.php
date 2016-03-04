<?php $url = base_url(); ?>
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= base_url('assets/img/display-photo-placeholder.png') ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?= user_id_number() ?></p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <?php if($this->session->userdata('type') === 'su'):?>
            
                <li class="header">ADMINISTRATORS PANEL</li>
                <!-- Optionally, you can add icons to the links -->
                <li class="<?= $active_nav === NAV_ADM_STUDENTS ? 'active' : '' ?>"><a href="<?= "{$url}students" ?>"><i class="fa fa-graduation-cap"></i> <span>Students</span></a></li>
                <li class="<?= $active_nav === NAV_ADM_FACI ? 'active' : '' ?>"><a href="<?= "{$url}facilitators" ?>"><i class="fa fa-user-secret"></i> <span>Facilitators</span></a></li>
                <li class="<?= $active_nav === NAV_ADM_ACTIVITIES ? 'active' : '' ?>"><a href="<?= "{$url}activities" ?>"><i class="fa fa-newspaper-o"></i> <span>Activities</span></a></li>
                <li>
                    <a href="<?= "{$url}students/print_master_list"?>" class="print-page"><i class="fa fa-list"></i> <span>Print Student List</span></a>
                </li>
                <li class="<?= $active_nav === NAV_USR_STATUS ? 'active' : '' ?>">
                    <a href="<?= "{$url}profile?id=".pk()?>"><i class="fa fa-user"></i> <span>My Account</span></a>
                </li>

            <?php elseif($this->session->userdata('type') === 'f'):?>
                <li class="header">FACILITATORS PANEL</li>
                <li class="<?= $active_nav === NAV_ADM_ACTIVITIES ? 'active' : '' ?>"><a href="<?= "{$url}activities" ?>"><i class="fa fa-newspaper-o"></i> <span>Activities</span></a></li>
                <li class="<?= $active_nav === NAV_USR_STATUS ? 'active' : '' ?>">
                    <a href="<?= "{$url}profile?id=".pk()?>" ><i class="fa fa-user"></i> <span>My Account</span></a>
                </li>
            <?php else:?>
                <li class="header">STUDENTS PANEL</li>
                <!-- Optionally, you can add icons to the links -->
                <li class="<?= $active_nav === NAV_USR_VIEW_ACTIVITIES ? 'active' : '' ?>">
                    <a href="<?= "{$url}student/view_activities" ?>"><i class="fa fa-newspaper-o"></i> <span>Browse Activities</span></a>
                </li>
                <li class="<?= $active_nav === NAV_USR_PROPOSE_ACTIVITIES ? 'active' : '' ?>">
                    <a href="<?= "{$url}student/propose_activity" ?>" ><i class="fa fa-pencil-square-o"></i> <span>Propose Activity</span></a>
                </li>
                <li class="<?= $active_nav === NAV_USR_TRACK_PROPOSALS ? 'active' : '' ?>">
                    <a href="<?= "{$url}student/track_proposals" ?>" ><i class="fa fa-binoculars"></i> <span>Track Proposals</span></a>
                </li>
                <li class="<?= $active_nav === NAV_USR_STATUS ? 'active' : '' ?>">
                    <a href="<?= "{$url}profile?id=".pk()?>"><i class="fa fa-user"></i> <span>My Status</span></a>
                </li>
            <?php endif;?>
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>