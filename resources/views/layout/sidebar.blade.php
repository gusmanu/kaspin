<div class="main-sidebar sidebar-style-2">
   <aside id="sidebar-wrapper">
      <div class="sidebar-brand">
         <a href="/"></a>
      </div>
      <div class="sidebar-brand sidebar-brand-sm">
        <a href="/"></a>
      </div>
      <ul class="sidebar-menu">
         <li class="menu-header">Dash</li>
         <li><a class="nav-link" href="/"><i class="fas fa-home"></i> <span>Home</span></a></li>
         @guest
            <li><a class="nav-link" href="{{route('login')}}"><i class="fas fa-sign-in-alt"></i> <span>Login</span></a></li>
         @endguest
      </ul>
   </aside>
</div>
