<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h4 class="customer-profile-group-heading"><?php echo _l('feedback'); ?></h4>
<div class="row">
 <?php foreach ($feedback_array as $fd) {?>
   <div class="col-md-12" id="small-table">
      <div class="panel_s">
        <div class="panel-body">
            <div class="clearfix"></div>
                <section class="write-review py-5 bg-light" id="write-review">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                            <div class="col-md-6">
                                <h5><?php echo _l('overall_rating'); ?></h5>
                            </div>
                            <div class="col-md-6 text-warning">
                                <?php
                                  
                                  $coding=$fd['coding'];
                                  $communication=$fd['communication'];
                                  $services=$fd['services'];
                                  $recommendation=$fd['recommendation'];
                                   
                                  $count = $coding + $communication + $services + $recommendation;
                                  $avg = $count / 4;
                                ?>
                                <?php $count = $avg; for($c=0;$c < $count;$c++){?><i class="fa fa-star"></i><?php } ?>
                            </div>
                        </div>
                        <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><?php echo _l('rate_coding'); ?></p>
                                </div>
                                <div class="col-md-6 text-warning">
                                <?php $count = $fd['coding']; for($c=0;$c < $count;$c++){?><i class="fa fa-star"></i><?php } ?>
                            </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><?php echo _l('communication'); ?></p>
                                </div>
                                <div class="col-md-6 text-warning">
                                <?php $count = $fd['communication']; for($c=0;$c < $count;$c++){?><i class="fa fa-star"></i><?php } ?>
                            </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><?php echo _l('services'); ?></p>
                                </div>
                                <div class="col-md-6 text-warning">
                                <?php $count = $fd['services']; for($c=0;$c < $count;$c++){?><i class="fa fa-star"></i><?php } ?>
                            </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><?php echo _l('recommendation'); ?></p>
                                </div>
                                <div class="col-md-6 text-warning">
                                <?php $count = $fd['recommendation']; for($c=0;$c < $count;$c++){?><i class="fa fa-star"></i><?php } ?>
                            </div>
                            </div>
                            <hr>
                            <div class="row pt-3">
                                <div class="col-md-12">
                                    <b><?php echo _l('project_fd'); ?>: </b><?php echo project_name_by_id($fd['projectid']);?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 my-5 py-5">
                            <h6><?php echo _l('review_comments'); ?><?php 
                            $client = $this->clients_model->get_contacts($fd['customerid']);
                            ?></h6>
                            <p><b class="text-danger"><?php echo $client[0]['firstname']." ".$client[0]['lastname']."</b> : ".$fd['message'];?></p>
                            

                        </div>
                    </div>
                </section>
            </div>
        </div>
   </div>
<?php } ?>
   
</div>