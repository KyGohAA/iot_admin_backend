
<!-- SIDE NAV-->
<nav>

<!-- LEFT SIDENAV-->
<ul id="nav-mobile-category" class="side-nav">
  <li class="profile">
    <div class="li-profile-info">
      <img src="{{isset($user['photo']) ? asset($user['photo']) : ''}}" alt="profile" style="margin-bottom:10px;">
      <h2>{{isset($user['fullname']) ? $user['fullname'] : ''}}</h2>
      <div class="emailprofile">{{isset($user['email']) ? $user['email'] : ''}}</div>
    </div>

  </li>
 

</ul>
<!-- END LEFT SIDENAV-->
<!-- RIGHT SIDENAV-->
<!-- END RIGHT SIDENAV-->

</nav>
<!-- END SIDENAV-->
