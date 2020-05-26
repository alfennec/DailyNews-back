<?php include_once('functions.php'); ?>

    <section class="content">

        <ol class="breadcrumb">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li class="active">Push Notification</a></li>
        </ol>

       <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <form role="form" action="send-notification.php" method="post" id="form_validation">
                    <div class="card">
                        <div class="header">
                            <h2>PUSH NOTIFICATION</h2>
                        </div>
                        <div class="body">

                            <div class="row clearfix">

                                <div class="col-sm-12">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="title" id="title" required>
                                            <label class="form-label" for="title">Title</label>
                                        </div>
                                    </div>

                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="message" id="message" required>
                                            <label class="form-label">Message</label>
                                        </div>
                                    </div>

                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="link" id="link">
                                            <label class="form-label">URL (optional)</label>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-sm-12">
                                    <button class="btn bg-blue waves-effect pull-right" type="submit">SEND NOTIFICATION</button>
                                </div>


                            </div>
                        </div>
                    </div>
                    </form>

                </div>
            </div>
            
        </div>

    </section>