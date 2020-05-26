<?php

  $sql_category = "SELECT COUNT(*) as num FROM category";
  $total_category = mysqli_query($connect, $sql_category);
  $total_category = mysqli_fetch_array($total_category);
  $total_category = $total_category['num'];

  $sql_news = "SELECT COUNT(*) as num FROM news";
  $total_radio = mysqli_query($connect, $sql_news);
  $total_radio = mysqli_fetch_array($total_radio);
  $total_radio = $total_radio['num'];

  $sql_comments = "SELECT COUNT(*) as num FROM comments";
  $total_comments = mysqli_query($connect, $sql_comments);
  $total_comments = mysqli_fetch_array($total_comments);
  $total_comments = $total_comments['num'];

  $sql_user = "SELECT COUNT(*) as num FROM user";
  $total_user = mysqli_query($connect, $sql_user);
  $total_user = mysqli_fetch_array($total_user);
  $total_user = $total_user['num'];

?>

    <section class="content">

    <ol class="breadcrumb">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li class="active">Home</a></li>
    </ol>

        <div class="container-fluid">
             
             <div class="row">

             
             <!-- Widgets -->
             <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box bg-pink hover-expand-effect">
                            <div class="icon">
                                <i class="material-icons">playlist_add_check</i>
                            </div>
                            <div class="content">
                                <div class="text">NEW CATEGORIES</div>
                                <div class="number count-to" data-from="0" data-to="125" data-speed="15" data-fresh-interval="20"><?php echo $total_category; ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box bg-cyan hover-expand-effect">
                            <div class="icon">
                                <i class="material-icons">help</i>
                            </div>
                            <div class="content">
                                <div class="text">DAILY NEWS</div>
                                <div class="number count-to" data-from="0" data-to="257" data-speed="1000" data-fresh-interval="20"><?php echo $total_radio; ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box bg-light-green hover-expand-effect">
                            <div class="icon">
                                <i class="material-icons">forum</i>
                            </div>
                            <div class="content">
                                <div class="text">NEW COMMENTS</div>
                                <div class="number count-to" data-from="0" data-to="243" data-speed="1000" data-fresh-interval="20"><?php echo $total_comments; ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box bg-orange hover-expand-effect">
                            <div class="icon">
                                <i class="material-icons">person_add</i>
                            </div>
                            <div class="content">
                                <div class="text">NEW MEMBERS</div>
                                <div class="number count-to" data-from="0" data-to="1225" data-speed="1000" data-fresh-interval="20"><?php echo $total_user; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Widgets -->





                <a href="manage-category.php">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="card demo-color-box bg-blue waves-effect col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="color-name">MANAGE CATEGORY</div>
                            <div class="color-name"><i class="material-icons">view_list</i></div>
                            <div class="color-class-name">Total ( <?php echo $total_category; ?> ) Categories</div>
                            <br>
                        </div>
                    </div>
                </a>

               <a href="manage-news.php">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="card demo-color-box bg-blue waves-effect col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="color-name">MANAGE NEWS</div>
                            <div class="color-name"><i class="material-icons">library_books</i></div>
                            <div class="color-class-name">Total ( <?php echo $total_radio; ?> ) News</div>
                            <br>
                        </div>
                    </div>
                </a>

                <a href="push-notification.php">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="card demo-color-box bg-blue waves-effect col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="color-name">PUSH NOTIFICATION</div>
                            <div class="color-name"><i class="material-icons">notifications</i></div>
                            <div class="color-class-name">Notify Your Users</div>
                            <br>
                        </div>
                    </div>
                </a>

                <a href="members.php">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="card demo-color-box bg-blue waves-effect col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="color-name">ADMINISTRATOR</div>
                            <div class="color-name"><i class="material-icons">people</i></div>
                            <div class="color-class-name">Admin Panel Privileges</div>
                            <br>
                        </div>
                    </div>
                </a>

                <a href="manage-user.php">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="card demo-color-box bg-blue waves-effect col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="color-name">MEMBERS</div>
                            <div class="color-name"><i class="material-icons">people</i></div>
                            <div class="color-class-name">Members of the App</div>
                            <br>
                        </div>
                    </div>
                </a>

                <a href="manage-comments.php">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="card demo-color-box bg-blue waves-effect col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="color-name">COMMENTS</div>
                            <div class="color-name"><i class="material-icons">comments</i></div>
                            <div class="color-class-name">Comments of the News</div>
                            <br>
                        </div>
                    </div>
                </a>

                <!--a href="settings.php">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="card demo-color-box bg-blue waves-effect col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="color-name">SETTINGS</div>
                            <div class="color-name"><i class="material-icons">settings</i></div>
                            <div class="color-class-name">Key and Privacy Settings</div>
                            <br>
                        </div>
                    </div>
                </a-->

            </div>
            
        </div>

    </section>