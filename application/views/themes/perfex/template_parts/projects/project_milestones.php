<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<table class="table dt-table table-milestones" data-order-col="0" data-order-type="asc">
    <thead>
        <tr>
            <th class="hidden"></th>
            <th width="20%"><?php echo _l('milestone_name'); ?></th>
            <th width="45%"><?php echo _l('milestone_description'); ?></th>
            <th width="20%"><?php echo _l('milestone_due_date'); ?></th>
            <?php if($project->settings->view_task_total_logged_time == 1){ ?>
                <th width="25%"><?php echo _l('milestone_total_logged_time'); ?></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach($milestones as $milestone){ ?>
            <tr>
                <td class="hide" data-order="<?php echo $milestone['milestone_order']; ?>"></td>
                <td><?php echo $milestone['name']; ?></td>
                <td>
                    <?php if($milestone['description_visible_to_customer'] == 1){
                        echo $milestone['description'];
                    }
                    ?>
                </td>
                <td data-order="<?php echo format_to_relative_time($milestone['due_date'], '', true); ?>"><?php echo format_to_relative_time($milestone['due_date'], '', true); ?></td>
                <?php if($project->settings->view_task_total_logged_time == 1){ ?>
                    <td><?php echo seconds_to_time_format($milestone['total_logged_time']); ?></td>
                <?php } ?>
            </tr>
        <?php } ?>
    </tbody>
</table>
