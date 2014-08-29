<div align="right"><form id="search" name="search" method="post" action="search.php">
    <strong style="color:#FFFFFF">Search: </strong>
    <label for="crn"></label>
    <input name="crn" type="text" id="crn" value="" />
    <input type="submit" name="button" id="button" value="Search" />
  </form></div>
<ul class="nav navbar-nav navbar-right navbar-user">
            <li class="dropdown user-dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <? echo $_SESSION['MM_Username']; ?> <b class="caret"></b></a>
              <ul class="dropdown-menu">
               <!-- <li><a href="#"><i class="fa fa-user"></i> Profile</a></li>
                <li><a href="#"><i class="fa fa-envelope"></i> Inbox <span class="badge">7</span></a></li>
                <li><a href="#"><i class="fa fa-gear"></i> Settings</a></li>-->
                <li class="divider"></li>
                <li><a href="<?php echo $logoutAction ?>"><i class="fa fa-power-off"></i> Log Out</a></li>
              </ul>
            </li>
          </ul>