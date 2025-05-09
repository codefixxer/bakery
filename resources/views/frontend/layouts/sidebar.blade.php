@auth
<style>
  /* Logout Button */
.sidebar-logout {
  margin-top: auto; /* Push logout button to the bottom */
}

.logout-btn {
  display: flex;
  align-items: center;
  padding: 12px;
  background-color: #ff4d4d; /* Red background for logout */
  color: white;
  border-radius: 6px;
  transition: background-color 0.3s ease;
  text-decoration: none;
}

.logout-btn:hover {
  background-color: #e63946; /* Darker red on hover */
}

.logout-icon {
  margin-right: 8px;
  font-size: 20px;
}

</style>

<aside class="sidebar">
  <button type="button" class="sidebar-close-btn">
    <iconify-icon icon="radix-icons:cross-2" style="color: #e2ae76;"></iconify-icon>
  </button>
  <div>
    <a href="{{ route('dashboard') }}" class="sidebar-logo">
      <img src="{{ asset('assets/images/logo.png') }}" alt="site logo" class="light-logo">
      <img src="{{ asset('assets/images/logo-light.png') }}" alt="site logo" class="dark-logo">
      <img src="{{ asset('assets/images/logo-icon.png') }}" alt="site logo" class="logo-icon">
    </a>
  </div>

  <div class="sidebar-menu-area">
    <ul class="sidebar-menu" id="sidebar-menu">

      {{-- Dashboard --}}
      <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}">
          <!-- Icon with color change -->
          <iconify-icon icon="mdi:view-dashboard-outline" class="menu-icon" style="color: #e2ae76;"></iconify-icon>
          <!-- Text with color change -->
          <span style="color: #e2ae76;">Dashboard</span>
        </a>
      </li>

      {{-- User Management --}}
      @can('manage-users')
      <li class="dropdown">
        <a href="javascript:void(0)">
          <!-- Icon with color change -->
          <iconify-icon icon="mdi:account-cog-outline" class="menu-icon" style="color: #e2ae76;"></iconify-icon>
          <!-- Text with color change -->
          <span style="color: #e2ae76;">User Management</span>
        </a>
        
        <ul class="sidebar-submenu">
          @can('view users')
          <li>
            <a href="{{ route('users.index') }}">
              <iconify-icon icon="mdi:account-multiple-outline" class="circle-icon" style="color: #e2ae76;"></iconify-icon>
              <span style="color: #e2ae76;">Users</span>
            </a>
          </li>
          @endcan

          @canany(['view roles','view permissions'])
          <li>
            <a href="{{ route('roles.index') }}">
              <iconify-icon icon="mdi:shield-key-outline" class="circle-icon" style="color: #e2ae76;"></iconify-icon>
              <span style="color: #e2ae76;">Roles & Permissions</span>
            </a>
          </li>
          @endcanany

          @can('view permissions')
          <li>
            <a href="{{ route('permissions.index') }}">
              <iconify-icon icon="mdi:shield-key-outline" class="circle-icon" style="color: #e2ae76;"></iconify-icon>
              <span style="color: #e2ae76;">Permissions</span>
            </a>
          </li>
          @endcan
        </ul>
      </li>
      @endcan

      {{-- Ingredients --}}
      @can('ingredients')
      <li>
        <a href="{{ route('ingredients.index') }}">
          <!-- Ingredients Icon with Gold Color -->
          <svg height="35px" width="35px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
               viewBox="0 0 512 512" xml:space="preserve" style="fill: #e2ae76; margin-right: 8px;">
            <path style="fill:#e2ae76;" d="M479.605,91.769c-23.376,23.376-66.058,33.092-79.268,19.882
              c-13.21-13.21-3.494-55.892,19.883-79.268s85.999-26.614,85.999-26.614S502.982,68.393,479.605,91.769z"/>
            <g>
              <path style="fill:#e2ae76;" d="M506.218,5.785L400.345,111.658c13.218,13.2,55.888,3.483,79.26-19.889
                C502.864,68.511,506.186,6.411,506.218,5.785z"/>
              <path style="fill:#e2ae76;" d="M432.367,200.156c-33.059,0-70.11-23.311-70.11-41.992s37.052-41.992,70.11-41.992
                s79.629,41.992,79.629,41.992S465.426,200.156,432.367,200.156z"/>
            </g>
            <path style="fill:#e2ae76;" d="M311.84,79.629c0,33.059,23.311,70.11,41.992,70.11s41.992-37.052,41.992-70.11S353.832,0,353.832,0
              S311.84,46.571,311.84,79.629z"/>
            <path style="fill:#e2ae76;" d="M367.516,265.006c-33.059,0-70.11-23.311-70.11-41.992s37.052-41.992,70.11-41.992
              s79.629,41.992,79.629,41.992S400.575,265.006,367.516,265.006z"/>
            <path style="fill:#e2ae76;" d="M246.99,144.48c0,33.059,23.311,70.11,41.992,70.11c18.681,0,41.992-37.052,41.992-70.11
              S288.982,64.85,288.982,64.85S246.99,111.421,246.99,144.48z"/>
            <path style="fill:#e2ae76;" d="M302.666,329.857c-33.059,0-70.11-23.311-70.11-41.992c0-18.681,37.052-41.992,70.11-41.992
              s79.629,41.992,79.629,41.992S335.726,329.857,302.666,329.857z"/>
            <path style="fill:#e2ae76;" d="M182.14,209.33c0,33.059,23.311,70.11,41.992,70.11s41.992-37.052,41.992-70.11
              s-41.992-79.629-41.992-79.629S182.14,176.27,182.14,209.33z"/>
            <path style="fill:#e2ae76;" d="M237.025,395.498c-33.059,0-70.11-23.311-70.11-41.992c0-18.681,37.052-41.992,70.11-41.992
              s79.629,41.992,79.629,41.992S270.085,395.498,237.025,395.498z"/>
            <path style="fill:#e2ae76;" d="M116.498,274.97c0,33.059,23.31,70.11,41.992,70.11s41.992-37.052,41.992-70.11
              s-41.992-79.629-41.992-79.629S116.498,241.912,116.498,274.97z"/>
            <path style="fill:#e2ae76;" d="M170.438,462.084c-33.059,0-70.11-23.311-70.11-41.992c0-18.681,37.052-41.992,70.11-41.992
              s79.629,41.992,79.629,41.992S203.497,462.084,170.438,462.084z"/>
            <path style="fill:#e2ae76;" d="M49.912,341.558c0,33.059,23.31,70.11,41.992,70.11s41.992-37.052,41.992-70.11
              s-41.992-79.629-41.992-79.629S49.912,308.499,49.912,341.558z"/>
            <path style="fill:#F29C2A;" d="M4.917,507.087c-6.552-6.552-6.552-17.174,0-23.725L404.75,83.527c6.552-6.552,17.174-6.552,23.725,0
              c6.552,6.552,6.552,17.174,0,23.725L28.643,507.087C22.091,513.637,11.468,513.637,4.917,507.087z"/>
          </svg>
        
          <!-- Text Next to the Icon (also in gold) -->
          <span style="color: #e2ae76;">Ingredients</span>
        </a>
      </li>
      @endcan

      {{-- Sale Comparison --}}
      @can('sale comparison')
      <li>
        <a href="{{ route('comparison.index') }}">
          <iconify-icon icon="mdi:chart-line" class="menu-icon" style="color: #e2ae76;"></iconify-icon>
          <span style="color: #e2ae76;">Sale Comparison</span>
        </a>
      </li>
      @endcan

      {{-- Recipe --}}
      @can('recipe')
      <li class="dropdown">
        <a href="javascript:void(0)">
          <iconify-icon icon="mdi:food-variant" class="menu-icon" style="color: #e2ae76;"></iconify-icon>
          <span style="color: #e2ae76;">Recipe</span>
        </a>
        <ul class="sidebar-submenu">
          <li>
            <a href="{{ route('recipes.create') }}">
              <iconify-icon icon="mdi:plus-circle-outline" class="circle-icon text-primary-600" style="color: #e2ae76;"></iconify-icon>
              <span style="color: #e2ae76;">Create</span>
            </a>
          </li>
          <li>
            <a href="{{ route('recipes.index') }}">
              <iconify-icon icon="mdi:format-list-bulleted" class="circle-icon text-warning-main" style="color: #e2ae76;"></iconify-icon>
              <span style="color: #e2ae76;">List</span>
            </a>
          </li>
        </ul>
      </li>
      @endcan

      {{-- External Supplies --}}
      @can('external supplies')
      <li class="dropdown">
        <a href="javascript:void(0)">
          <iconify-icon icon="mdi:truck-delivery" class="menu-icon" style="color: #e2ae76;"></iconify-icon>
          <span style="color: #e2ae76;">External Supplies</span>
        </a>
        <ul class="sidebar-submenu">
          <li>
            <a href="{{ route('external-supplies.create') }}">
              <iconify-icon icon="mdi:plus-circle-outline" class="circle-icon text-primary-600" style="color: #e2ae76;"></iconify-icon>
              <span style="color: #e2ae76;">Create</span>
            </a>
          </li>
          <li>
            <a href="{{ route('external-supplies.index') }}">
              <iconify-icon icon="mdi:format-list-bulleted" class="circle-icon text-warning-main" style="color: #e2ae76;"></iconify-icon>
              <span style="color: #e2ae76;">List</span>
            </a>
          </li>
        </ul>
      </li>
      @endcan

      {{-- Returned Goods --}}
      @can('returned goods')
      <li class="dropdown">
        <a href="javascript:void(0)">
          <iconify-icon icon="mdi:truck-delivery" class="menu-icon" style="color: #e2ae76;"></iconify-icon>
          <span style="color: #e2ae76;">Returned Goods</span>
        </a>
        <ul class="sidebar-submenu">
          <li>
            <a href="{{ route('returned-goods.index') }}">
              <iconify-icon icon="mdi:format-list-bulleted" class="circle-icon text-warning-main" style="color: #e2ae76;"></iconify-icon>
              <span style="color: #e2ae76;">Sale/Return Comparison</span>
            </a>
          </li>
        </ul>
      </li>
      @endcan
      
      {{-- Management --}}
      @canany([
        'recipe categories','clients','cost categories',
        'departments','pastry chefs','equipment'
      ])
      <li class="dropdown">
        <a href="javascript:void(0)">
          <iconify-icon icon="mdi:account-cog-outline" class="menu-icon" style="color: #e2ae76;"></iconify-icon>
          <span style="color: #e2ae76;">Management</span>
        </a>
        <ul class="sidebar-submenu">
          @can('recipe categories')
          <li>
            <a href="{{ route('recipe-categories.index') }}">
              <iconify-icon icon="mdi:shape-outline" class="circle-icon text-primary-600" style="color: #e2ae76;"></iconify-icon>
              <span style="color: #e2ae76;">Recipe Categories</span>
            </a>
          </li>
          @endcan
      
          @can('clients')
          <li>
            <a href="{{ route('clients.index') }}">
              <iconify-icon icon="mdi:account-group-outline" class="circle-icon text-warning-main" style="color: #e2ae76;"></iconify-icon>
              <span style="color: #e2ae76;">Clients</span>
            </a>
          </li>
          @endcan
      
          @can('cost categories')
          <li>
            <a href="{{ route('cost_categories.index') }}">
              <iconify-icon icon="mdi:tag-outline" class="circle-icon text-primary-600" style="color: #e2ae76;"></iconify-icon>
              <span style="color: #e2ae76;">Cost Categories</span>
            </a>
          </li>
          @endcan
      
          @can('departments')
          <li>
            <a href="{{ route('departments.index') }}">
              <iconify-icon icon="mdi:office-building-outline" class="circle-icon text-warning-main" style="color: #e2ae76;"></iconify-icon>
              <span style="color: #e2ae76;">Departments</span>
            </a>
          </li>
          @endcan
      
          @can('pastry chefs')
          <li>
            <a href="{{ route('pastry-chefs.index') }}">
              <iconify-icon icon="mdi:chef-hat" class="circle-icon text-primary-600" style="color: #e2ae76;"></iconify-icon>
              <span style="color: #e2ae76;">Pastry Chefs</span>
            </a>
          </li>
          @endcan
      
          @can('equipment')
          <li>
            <a href="{{ route('equipment.index') }}">
              <iconify-icon icon="mdi:tools" class="circle-icon text-warning-main" style="color: #e2ae76;"></iconify-icon>
              <span style="color: #e2ae76;">Equipment</span>
            </a>
          </li>
          @endcan
        </ul>
      </li>
      @endcanany
      
      {{-- Showcase --}}
      @can('showcase')
      <li class="dropdown">
        <a href="javascript:void(0)">
          <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
               viewBox="0 0 512.005 512.005" xml:space="preserve" style="width: 24px; height: 24px; margin-right: 8px; fill: #e2ae76;">
            <g>
              <path style="fill:#e2ae76;" d="M159.669,238.344L159.669,238.344c-26.601,0-48.166-21.564-48.166-48.166V21.609h96.331v168.57
                  C207.835,216.779,186.269,238.344,159.669,238.344z"/>
              <path style="fill:#e2ae76;" d="M352.331,238.344L352.331,238.344c-26.601,0-48.166-21.564-48.166-48.166V21.609h96.331v168.57
                  C400.496,216.779,378.932,238.344,352.331,238.344z"/>
              <rect x="191.378" y="312.192" style="fill:#e2ae76;" width="129.249" height="178.209"/>
            </g>
            <path style="fill:#e2ae76;" d="M496.828,104.985c8.379,0,15.172-6.792,15.172-15.172V58.537c0-28.728-23.372-52.099-52.099-52.099
                h-59.404h-96.332h-96.331h-96.332H52.099C23.372,6.437,0,29.809,0,58.537V190.18c0,20.106,9.428,38.04,24.084,49.651v250.563
                c0,8.379,6.792,15.172,15.172,15.172h152.122h129.244h152.124c8.379,0,15.172-6.792,15.172-15.172V312.189
                c0-8.379-6.792-15.172-15.172-15.172c-8.379,0-15.172,6.792-15.172,15.172v163.032h-121.78V312.189
                c0-8.379-6.792-15.172-15.172-15.172H191.378c-8.379,0-15.172,6.792-15.172,15.172v163.032H54.428V252.878
                c2.913,0.413,5.885,0.639,8.91,0.639c19.267,0,36.54-8.659,48.166-22.275c11.626,13.617,28.899,22.275,48.166,22.275
                s36.54-8.659,48.166-22.275c11.626,13.617,28.899,22.275,48.166,22.275s36.54-8.659,48.166-22.275
                c11.626,13.617,28.899,22.275,48.166,22.275c19.267,0,36.54-8.659,48.166-22.275c11.626,13.617,28.899,22.275,48.166,22.275
                c34.924,0,63.338-28.414,63.338-63.338v-26.232c0-8.379-6.792-15.172-15.172-15.172s-15.172,6.792-15.172,15.172v26.232
                c0,18.193-14.8,32.994-32.994,32.994s-32.994-14.8-32.994-32.994V36.78h44.232c11.996,0,21.755,9.76,21.755,21.755v31.277
                C481.656,98.193,488.449,104.985,496.828,104.985z M206.55,327.361h98.901v147.86H206.55V327.361z M63.338,223.173
                c-18.194,0-32.994-14.802-32.994-32.994V58.537c0-11.996,9.76-21.755,21.755-21.755h44.232V190.18
                C96.331,208.371,81.531,223.173,63.338,223.173z M159.669,223.173c-18.193,0-32.994-14.8-32.994-32.994V36.78h65.988v153.398
                C192.663,208.371,177.861,223.173,159.669,223.173z M255.999,223.173c-18.193,0-32.994-14.8-32.994-32.994V36.78h65.987v153.398
                C288.993,208.371,274.193,223.173,255.999,223.173z M352.331,223.173c-18.193,0-32.994-14.8-32.994-32.994V36.78h65.988v153.398
                C385.326,208.371,370.524,223.173,352.331,223.173z"/>
          </svg>
          
          <span style="color: #e2ae76; font-size: 16px;">Showcase</span>
        </a>
        
        <ul class="sidebar-submenu">
          <li>
            <a href="{{ route('showcase.create') }}">
              <iconify-icon icon="mdi:plus-circle-outline" class="circle-icon text-primary-600" style="color: #e2ae76;"></iconify-icon>
              <span style="color: #e2ae76;">Create</span>
            </a>
          </li>
          <li>
            <a href="{{ route('showcase.index') }}">
              <iconify-icon icon="mdi:format-list-bulleted" class="circle-icon text-warning-main" style="color: #e2ae76;"></iconify-icon>
              <span style="color: #e2ae76;">List</span>
            </a>
          </li>
        </ul>
      </li>
      @endcan
      
      {{-- Finentials --}}
      @canany(['costs','income','cost comparison'])
      <li class="dropdown">
        <a href="javascript:void(0)">
          <svg height="30px" width="30px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
             viewBox="0 0 512 512" xml:space="preserve" style="fill: #e2ae76;">
            <circle style="fill:#FEE187;" cx="255.997" cy="255.997" r="167.991"/>
            <g>
              <path style="fill:#FFC61B;" d="M256,0c-7.664,0-13.877,6.213-13.877,13.877S248.336,27.753,256,27.753
                c57.945,0,110.905,21.716,151.199,57.422l-32.781,32.781C341.468,89.6,299.928,74.132,256,74.132
                c-45.156,0-86.517,16.549-118.35,43.892L95.044,75.42c-0.075-0.075-0.158-0.139-0.235-0.212c-0.071-0.075-0.132-0.154-0.205-0.228
                c-5.417-5.419-14.206-5.419-19.624,0C26.628,123.332,0,187.62,0,256c0,141.159,114.841,256,256,256
                c68.38,0,132.667-26.628,181.02-74.98C485.372,388.668,512,324.38,512,256C512,114.841,397.159,0,256,0z M365.043,147.093
                c5.416,5.423,14.203,5.429,19.624,0.011c0.402-0.402,0.766-0.828,1.109-1.264c0.029-0.029,0.061-0.053,0.09-0.082l40.957-40.957
                c32.834,37.054,53.823,84.82,56.987,137.322h-15.439c-7.664,0-13.877,6.213-13.877,13.877s6.213,13.877,13.877,13.877h15.443
                c-3.047,51.144-22.904,99.082-56.912,137.403l-32.929-32.929c27.344-31.833,43.892-73.193,43.892-118.35
                c0-7.664-6.213-13.877-13.877-13.877s-13.877,6.213-13.877,13.877c0,84.978-69.135,154.115-154.115,154.115
                S101.883,340.979,101.883,256s69.135-154.115,154.115-154.115C297.201,101.885,335.927,117.941,365.043,147.093z M256,453.159
                c-7.664,0-13.877,6.213-13.877,13.877v16.777c-52.502-3.165-100.269-24.154-137.322-56.987l32.849-32.849
                c31.833,27.344,73.193,43.892,118.35,43.892s86.517-16.549,118.35-43.892l32.929,32.929
                c-38.319,34.009-86.259,53.867-137.403,56.912v-16.782C269.877,459.371,263.664,453.159,256,453.159z M28.188,269.877h46.47
                c3.011,39.73,18.85,75.932,43.367,104.473l-32.85,32.849C52.342,370.146,31.353,322.379,28.188,269.877z M85.096,104.72
                l32.929,32.929c-24.517,28.542-40.355,64.743-43.367,104.473H28.182C31.229,190.979,51.087,143.041,85.096,104.72z"/>
              <path style="fill:#FFC61B;" d="M336.905,276.043c-0.803-0.884-1.943-1.388-3.136-1.388h-19.005c-2.054,0-3.813,1.472-4.174,3.496
                c-1.987,11.118-4.751,19.626-8.47,26.042c-6.72,11.84-16.295,17.596-29.27,17.596c-14.231,0-24.569-5.483-31.631-16.813
                c-3.643-5.669-6.433-12.354-8.326-19.928h55.85c1.869,0,3.521-1.225,4.06-3.017l5.023-16.658c0.387-1.284,0.143-2.675-0.656-3.752
                c-0.799-1.077-2.062-1.712-3.404-1.712h-63.723c-0.001-0.534-0.001-1.069-0.001-1.6c0-3.101,0.058-5.864,0.173-8.351h58.636
                c1.88,0,3.534-1.238,4.066-3.039l4.915-16.658c0.379-1.284,0.13-2.668-0.67-3.74c-0.799-1.07-2.058-1.701-3.396-1.701h-59.829
                c1.998-7.478,4.771-13.605,8.408-18.61c7.994-10.839,18.152-16.108,31.051-16.108c10.599,0,18.471,2.766,24.115,8.505
                c5.777,5.681,9.712,13.764,11.697,24.023c0.387,1.994,2.133,3.434,4.164,3.434h19.115c0.017,0,0.033,0,0.042,0
                c2.344,0,4.241-1.898,4.241-4.241c0-0.329-0.037-0.651-0.108-0.956c-1.359-15.216-7.356-28.97-17.84-40.895
                c-10.877-12.353-26.233-18.616-45.645-18.616c-22.777,0-40.892,9.521-53.835,28.283c-6.99,10.061-11.999,21.881-14.916,35.177
                h-21.279c-1.88,0-3.534,1.238-4.066,3.039l-4.915,16.658c-0.379,1.284-0.13,2.668,0.67,3.74c0.799,1.07,2.058,1.701,3.396,1.701
                h23.366c-0.055,1.47-0.083,2.938-0.083,4.389c0,1.857,0.035,3.716,0.101,5.562h-18.468c-1.876,0-3.53,1.234-4.064,3.033
                l-4.915,16.549c-0.382,1.284-0.135,2.671,0.666,3.744c0.799,1.073,2.061,1.704,3.397,1.704h25.823
                c3.397,19.452,10.532,35,21.22,46.232c12.135,12.918,27.351,19.466,45.226,19.466c20.753,0,37.462-7.975,49.655-23.694
                c10.047-12.88,16.047-28.911,17.836-47.644C338.104,278.11,337.71,276.927,336.905,276.043z"/>
            </g>
          </svg>
          <span style="color: #e2ae76;">Financials</span>
        </a>
      
        <ul class="sidebar-submenu">
          @can('costs')
          <li>
            <a href="{{ route('costs.index') }}">
              <iconify-icon icon="mdi:format-list-bulleted" class="circle-icon text-warning-main" style="color: #e2ae76;"></iconify-icon>
              <span style="color: #e2ae76;">Costs</span>
            </a>
          </li>
          @endcan
      
          @can('income')
          <li>
            <a href="{{ route('incomes.index') }}">
              <iconify-icon icon="mdi:currency-usd" class="menu-icon" style="color: #e2ae76;"></iconify-icon>
              <span style="color: #e2ae76;">Income</span>
            </a>
          </li>
          @endcan
      
          @can('cost comparison')
          <li>
            <a href="{{ route('costs.dashboard') }}">
              <iconify-icon icon="mdi:currency-usd-circle" class="menu-icon" style="color: #e2ae76;"></iconify-icon>
              <span style="color: #e2ae76;">Cost Comparison</span>
            </a>
          </li>
          @endcan
        </ul>
      </li>
      @endcanany
      
     {{-- News --}}
@can('news')
<li class="dropdown">
  <a href="javascript:void(0)">
    <iconify-icon icon="mdi:newspaper-variant-outline" class="menu-icon" style="color: #e2ae76;"></iconify-icon>
    <span style="color: #e2ae76;">News</span>
  </a>
  <ul class="sidebar-submenu">
    <li>
      <a href="{{ route('news.create') }}">
        <iconify-icon icon="mdi:plus-circle-outline" class="circle-icon text-primary-600" style="color: #e2ae76;"></iconify-icon>
        <span style="color: #e2ae76;">Create</span>
      </a>
    </li>
    <li>
      <a href="{{ route('news.index') }}">
        <iconify-icon icon="mdi:format-list-bulleted" class="circle-icon text-warning-main" style="color: #e2ae76;"></iconify-icon>
        <span style="color: #e2ae76;">List</span>
      </a>
    </li>
  </ul>
</li>
@endcan

{{-- Production --}}
@can('production')
<li class="dropdown">
  <a href="javascript:void(0)">
    <iconify-icon icon="mdi:factory" class="menu-icon" style="color: #e2ae76;"></iconify-icon>
    <span style="color: #e2ae76;">Production</span>
  </a>
  <ul class="sidebar-submenu">
    <li>
      <a href="{{ route('production.create') }}">
        <iconify-icon icon="mdi:plus-circle-outline" class="circle-icon text-primary-600" style="color: #e2ae76;"></iconify-icon>
        <span style="color: #e2ae76;">Create</span>
      </a>
    </li>
    <li>
      <a href="{{ route('production.index') }}">
        <iconify-icon icon="mdi:format-list-bulleted" class="circle-icon text-warning-main" style="color: #e2ae76;"></iconify-icon>
        <span style="color: #e2ae76;">List</span>
      </a>
    </li>
  </ul>
</li>
@endcan

{{-- Labor Cost --}}
@can('labor cost')
<li>
  <a href="{{ route('labor-cost.index') }}">
    <iconify-icon icon="mdi:clock-outline" class="menu-icon" style="color: #e2ae76;"></iconify-icon>
    <span style="color: #e2ae76;">Labor Cost</span>
  </a>
</li>
@endcan



      <li class="sidebar-logout">
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="logout-btn">
          <iconify-icon icon="mdi:exit-to-app" class="logout-icon"></iconify-icon>
          <span>Logout</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
      </li>

      {{-- Academy Link (always visible) --}}
      <li class="sidebar-academy">
        <a href="https://www.accademiadelpasticcereimprenditore.com/" target="_blank" rel="noopener">
          <iconify-icon icon="mdi:school" class="academy-icon"></iconify-icon>
          <span>Accedi all’Accademia</span>
        </a>
      </li>
      
      <!-- Beautiful Logout Button -->
  
      
    </ul>
  </div>
</aside>
@endauth
