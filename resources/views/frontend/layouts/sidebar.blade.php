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
    <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
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
          <iconify-icon icon="mdi:view-dashboard-outline" class="menu-icon"></iconify-icon>
          <span>Dashboard</span>
        </a>
      </li>

      {{-- User Management --}}
      @can('manage-users')
      <li class="dropdown">
        <a href="javascript:void(0)">
          <iconify-icon icon="mdi:account-cog-outline" class="menu-icon"></iconify-icon>
          <span>User Management</span>
        </a>
        <ul class="sidebar-submenu">
          @can('view users')
          <li>
            <a href="{{ route('users.index') }}">
              <iconify-icon icon="mdi:account-multiple-outline" class="circle-icon text-primary-600"></iconify-icon>
              Users
            </a>
          </li>
          @endcan

          @canany(['view roles','view permissions'])
          <li>
            <a href="{{ route('roles.index') }}">
              <iconify-icon icon="mdi:shield-key-outline" class="circle-icon text-warning-main"></iconify-icon>
              Roles & Permissions
            </a>
          </li>
          @endcanany

          @can('view permissions')
          <li>
            <a href="{{ route('permissions.index') }}">
              <iconify-icon icon="mdi:shield-key-outline" class="circle-icon text-warning-main"></iconify-icon>
              Permissions
            </a>
          </li>
          @endcan
        </ul>
      </li>
      @endcan

      {{-- Ingredients --}}
      @can('blogs')
      <li>
        <a href="{{ route('blogs') }}">
          <iconify-icon icon="mdi:silverware-fork-knife" class="menu-icon"></iconify-icon>
          <span>Blog</span>
        </a>
      </li>
      @endcan
      @can('ingredients')
      <li>
        <a href="{{ route('ingredients.index') }}">
          <iconify-icon icon="mdi:silverware-fork-knife" class="menu-icon"></iconify-icon>
          <span>Ingredients</span>
        </a>
      </li>
      @endcan

      {{-- Sale Comparison --}}
      @can('sale comparison')
      <li>
        <a href="{{ route('comparison.index') }}">
          <iconify-icon icon="mdi:chart-line" class="menu-icon"></iconify-icon>
          <span>Sale Comparison</span>
        </a>
      </li>
      @endcan

      {{-- Recipe --}}
      @can('recipe')
      <li class="dropdown">
        <a href="javascript:void(0)">
          <iconify-icon icon="mdi:food-variant" class="menu-icon"></iconify-icon>
          <span>Recipe</span>
        </a>
        <ul class="sidebar-submenu">
          <li>
            <a href="{{ route('recipes.create') }}">
              <iconify-icon icon="mdi:plus-circle-outline" class="circle-icon text-primary-600"></iconify-icon>
              Create
            </a>
          </li>
          <li>
            <a href="{{ route('recipes.index') }}">
              <iconify-icon icon="mdi:format-list-bulleted" class="circle-icon text-warning-main"></iconify-icon>
              List
            </a>
          </li>
        </ul>
      </li>
      @endcan

      {{-- External Supplies --}}
      @can('external supplies')
      <li class="dropdown">
        <a href="javascript:void(0)">
          <iconify-icon icon="mdi:truck-delivery" class="menu-icon"></iconify-icon>
          <span>External Supplies</span>
        </a>
        <ul class="sidebar-submenu">
          <li>
            <a href="{{ route('external-supplies.create') }}">
              <iconify-icon icon="mdi:plus-circle-outline" class="circle-icon text-primary-600"></iconify-icon>
              Create
            </a>
          </li>
          <li>
            <a href="{{ route('external-supplies.index') }}">
              <iconify-icon icon="mdi:format-list-bulleted" class="circle-icon text-warning-main"></iconify-icon>
              List
            </a>
          </li>
        </ul>
      </li>
      @endcan

      {{-- Returned Goods --}}
      @can('returned goods')
      <li class="dropdown">
        <a href="javascript:void(0)">
          <iconify-icon icon="mdi:truck-delivery" class="menu-icon"></iconify-icon>
          <span>Returned Goods</span>
        </a>
        <ul class="sidebar-submenu">
          <li>
            <a href="{{ route('returned-goods.index') }}">
              <iconify-icon icon="mdi:format-list-bulleted" class="circle-icon text-warning-main"></iconify-icon>
              Sale/Return Comparison
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
          <iconify-icon icon="mdi:account-cog-outline" class="menu-icon"></iconify-icon>
          <span>Management</span>
        </a>
        <ul class="sidebar-submenu">
          @can('recipe categories')
          <li>
            <a href="{{ route('recipe-categories.index') }}">
              <iconify-icon icon="mdi:shape-outline" class="circle-icon text-primary-600"></iconify-icon>
              Recipe Categories
            </a>
          </li>
          @endcan

          @can('clients')
          <li>
            <a href="{{ route('clients.index') }}">
              <iconify-icon icon="mdi:account-group-outline" class="circle-icon text-warning-main"></iconify-icon>
              Clients
            </a>
          </li>
          @endcan

          @can('cost categories')
          <li>
            <a href="{{ route('cost_categories.index') }}">
              <iconify-icon icon="mdi:tag-outline" class="circle-icon text-primary-600"></iconify-icon>
              Cost Categories
            </a>
          </li>
          @endcan

          @can('departments')
          <li>
            <a href="{{ route('departments.index') }}">
              <iconify-icon icon="mdi:office-building-outline" class="circle-icon text-warning-main"></iconify-icon>
              Departments
            </a>
          </li>
          @endcan

          @can('pastry chefs')
          <li>
            <a href="{{ route('pastry-chefs.index') }}">
              <iconify-icon icon="mdi:chef-hat" class="circle-icon text-primary-600"></iconify-icon>
              Pastry Chefs
            </a>
          </li>
          @endcan

          @can('equipment')
          <li>
            <a href="{{ route('equipment.index') }}">
              <iconify-icon icon="mdi:tools" class="circle-icon text-warning-main"></iconify-icon>
              Equipment
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
          <iconify-icon icon="mdi:eye-outline" class="menu-icon"></iconify-icon>
          <span>Showcase</span>
        </a>
        <ul class="sidebar-submenu">
          <li>
            <a href="{{ route('showcase.create') }}">
              <iconify-icon icon="mdi:plus-circle-outline" class="circle-icon text-primary-600"></iconify-icon>
              Create
            </a>
          </li>
          <li>
            <a href="{{ route('showcase.index') }}">
              <iconify-icon icon="mdi:format-list-bulleted" class="circle-icon text-warning-main"></iconify-icon>
              List
            </a>
          </li>
        </ul>
      </li>
      @endcan

      {{-- Finentials --}}
      @canany(['costs','income','cost comparison'])
      <li class="dropdown">
        <a href="javascript:void(0)">
          <iconify-icon icon="mdi:folder-cog-outline" class="menu-icon"></iconify-icon>
          <span>Finentials</span>
        </a>
        <ul class="sidebar-submenu">
          @can('costs')
          <li>
            <a href="{{ route('costs.index') }}">
              <iconify-icon icon="mdi:format-list-bulleted" class="circle-icon text-warning-main"></iconify-icon>
              Costs
            </a>
          </li>
          @endcan

          @can('income')
          <li>
            <a href="{{ route('incomes.index') }}">
              <iconify-icon icon="mdi:currency-usd" class="menu-icon"></iconify-icon>
              Income
            </a>
          </li>
          @endcan

          @can('cost comparison')
          <li>
            <a href="{{ route('costs.dashboard') }}">
              <iconify-icon icon="mdi:currency-usd-circle" class="menu-icon"></iconify-icon>
              Cost Comparison
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
          <iconify-icon icon="mdi:newspaper-variant-outline" class="menu-icon"></iconify-icon>
          <span>News</span>
        </a>
        <ul class="sidebar-submenu">
          <li>
            <a href="{{ route('news.create') }}">
              <iconify-icon icon="mdi:plus-circle-outline" class="circle-icon text-primary-600"></iconify-icon>
              Create
            </a>
          </li>
          <li>
            <a href="{{ route('news.index') }}">
              <iconify-icon icon="mdi:format-list-bulleted" class="circle-icon text-warning-main"></iconify-icon>
              List
            </a>
          </li>
        </ul>
      </li>
      @endcan

      {{-- Production --}}
      @can('production')
      <li class="dropdown">
        <a href="javascript:void(0)">
          <iconify-icon icon="mdi:factory" class="menu-icon"></iconify-icon>
          <span>Production</span>
        </a>
        <ul class="sidebar-submenu">
          <li>
            <a href="{{ route('production.create') }}">
              <iconify-icon icon="mdi:plus-circle-outline" class="circle-icon text-primary-600"></iconify-icon>
              Create
            </a>
          </li>
          <li>
            <a href="{{ route('production.index') }}">
              <iconify-icon icon="mdi:format-list-bulleted" class="circle-icon text-warning-main"></iconify-icon>
              List
            </a>
          </li>
        </ul>
      </li>
      @endcan

      {{-- Labor Cost --}}
      @can('labor cost')
      <li>
        <a href="{{ route('labor-cost.index') }}">
          <iconify-icon icon="mdi:clock-outline" class="menu-icon"></iconify-icon>
          <span>Labor Cost</span>
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
