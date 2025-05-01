

  <aside class="sidebar">
      <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
      </button>
    
      <div>
        <a href="index.html" class="sidebar-logo">
          <img src="{{ asset('assets/images/logo.png') }}" alt="site logo" class="light-logo">
          <img src="{{ asset('assets/images/logo-light.png') }}" alt="site logo" class="dark-logo">
          <img src="{{ asset('assets/images/logo-icon.png') }}" alt="site logo" class="logo-icon">
        </a>
      </div>
    
      <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">
    
          {{-- Dashboard --}}
          <li>
            <a href="#">
              <iconify-icon icon="mdi:view-dashboard-outline" class="menu-icon"></iconify-icon>
              <span>Dashboard</span>
            </a>
          </li>
    
          {{-- Ingredients --}}
          <li>
            <a href="{{ route('ingredients.index') }}">
              <iconify-icon icon="mdi:silverware-fork-knife" class="menu-icon"></iconify-icon>
              <span>Ingredients</span>
            </a>
          </li>
    
          {{-- Cost Comparison --}}
    
    
          {{-- Sale Comparison --}}
          <li>
            <a href="{{ route('comparison.index') }}">
              <iconify-icon icon="mdi:chart-line" class="menu-icon"></iconify-icon>
              <span>Sale Comparison</span>
            </a>
          </li>
          {{-- <li>
            <a href="{{ route('incomes.index') }}">
              <iconify-icon icon="mdi:currency-usd" class="menu-icon"></iconify-icon>
                            <span>Income</span>
            </a>
          </li> --}}
    
          {{-- Income --}}
          {{-- <li class="dropdown">
            <a href="javascript:void(0)">
              <iconify-icon icon="mdi:currency-usd" class="menu-icon"></iconify-icon>
              <span>Finentials</span>
            </a>
            <ul class="sidebar-submenu">
              <li>
                <a href="{{ route('incomes.index') }}">
                  <iconify-icon icon="mdi:plus-circle-outline" class="circle-icon text-primary-600"></iconify-icon>
                  Cost
                </a>
              </li>
              <li>
                <a href="{{ route('incomes.index') }}">
                  <iconify-icon icon="mdi:plus-circle-outline" class="circle-icon text-primary-600"></iconify-icon>
                  Income
                </a>
              </li>
              <li>
                <a href="{{ route('incomes.index') }}">
                  <iconify-icon icon="mdi:plus-circle-outline" class="circle-icon text-primary-600"></iconify-icon>
                  Cost Comparison
                </a>
              </li>
           
            </ul>
          </li> --}}
    
          {{-- Recipes --}}
          <li class="dropdown">
              <a href="javascript:void(0)">
                <!-- use a “food‐variant” icon instead of the old “recipe” one -->
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
            
    
          {{-- External Supplies --}}
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
          <li class="dropdown">
            <a href="javascript:void(0)">
              <iconify-icon icon="mdi:truck-delivery" class="menu-icon"></iconify-icon>
              <span>Returned Goods</span>
            </a>
            <ul class="sidebar-submenu">
              {{-- <li>
                <a href="{{ route('returned-goods.create') }}">
                  <iconify-icon icon="mdi:plus-circle-outline" class="circle-icon text-primary-600"></iconify-icon>
                  Create
                </a>
              </li> --}}
              <li>
                <a href="{{ route('returned-goods.index') }}">
                  <iconify-icon icon="mdi:format-list-bulleted" class="circle-icon text-warning-main"></iconify-icon>
                  Sale/Return Comparision 
                </a>
              </li>
            </ul>
          </li>
    
          {{-- Management --}}
          <li class="dropdown">
            <a href="javascript:void(0)">
              <iconify-icon icon="mdi:account-cog-outline" class="menu-icon"></iconify-icon>
              <span>Management</span>
            </a>
            <ul class="sidebar-submenu">
              <li>
                <a href="{{ route('recipe-categories.index') }}">
                  <iconify-icon icon="mdi:shape-outline" class="circle-icon text-primary-600"></iconify-icon>
                  Recipe Categories
                </a>
              </li>
              <li>
                <a href="{{ route('clients.index') }}">
                  <iconify-icon icon="mdi:account-group-outline" class="circle-icon text-warning-main"></iconify-icon>
                  Clients
                </a>
              </li>
              <li>
                <a href="{{ route('cost_categories.index') }}">
                  <iconify-icon icon="mdi:tag-outline" class="circle-icon text-primary-600"></iconify-icon>
                  Cost Categories
                </a>
              </li>
              <li>
                <a href="{{ route('departments.index') }}">
                  <iconify-icon icon="mdi:office-building-outline" class="circle-icon text-warning-main"></iconify-icon>
                  Departments
                </a>
              </li>
              <li>
                <a href="{{ route('pastry-chefs.index') }}">
                  <iconify-icon icon="mdi:chef-hat" class="circle-icon text-primary-600"></iconify-icon>
                  Pastry Chefs
                </a>
              </li>
              <li>
                <a href="{{ route('equipment.index') }}">
                  <iconify-icon icon="mdi:tools" class="circle-icon text-warning-main"></iconify-icon>
                  Equipment
                </a>
              </li>
            </ul>
          </li>
    
          {{-- Showcase --}}
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
    
          {{-- Cost Management --}}
          <li class="dropdown">
            <a href="javascript:void(0)">
              <iconify-icon icon="mdi:folder-cog-outline" class="menu-icon"></iconify-icon>
              <span>Finentials</span>
            </a>
            <ul class="sidebar-submenu">
              {{-- <li>
                <a href="{{ route('costs.create') }}">
                  <iconify-icon icon="mdi:plus-circle-outline" class="circle-icon text-primary-600"></iconify-icon>
                  Create
                </a>
              </li> --}}
              <li>
                <a href="{{ route('costs.index') }}">
                  <iconify-icon icon="mdi:format-list-bulleted" class="circle-icon text-warning-main"></iconify-icon>
                  Costs
                </a>
              </li>
              <li>
                <a href="{{ route('incomes.index') }}">
                  <iconify-icon icon="mdi:currency-usd" class="menu-icon"></iconify-icon>
                  Income
                </a>
              </li>
              <li>
                <a href="{{ route('costs.dashboard') }}">
                  <iconify-icon icon="mdi:currency-usd-circle" class="menu-icon"></iconify-icon>
                  Cost Comparison
                </a>
              </li>
            </ul>
          </li>


     
    
          {{-- News --}}
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
    
    {{-- Production --}}
  <li class="dropdown">
      <a href="javascript:void(0)">
        <!-- “factory” is a reliable mdi icon for production/factory -->
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
    
    
          {{-- Labor Cost --}}
          <li>
              <a href="{{ route('labor-cost.index') }}">
                <iconify-icon icon="mdi:clock-outline" class="menu-icon"></iconify-icon>
                <span>Labor Cost</span>
              </a>
            </li>


<style>
  /* 1) make the UL stretch and push last item down */
.sidebar-menu-area {
  display: flex;
  flex-direction: column;
  height: 100%;
}
.sidebar-menu {
  flex: 1;
  display: flex;
  flex-direction: column;
  padding: 0;
  margin: 0;
  list-style: none;
}
/* normal li’s keep their default spacing */
.sidebar-menu li {
  margin: 0;
}
/* push .sidebar-academy all the way to the bottom */
.sidebar-menu li.sidebar-academy {
  margin-top: auto;
  padding: 5vw 1vw ;
  text-align: center;
}
/* style the link like a big button */
.sidebar-menu li.sidebar-academy a {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: .5rem;
  background-color: #f06292;
  color: #fff;
  text-decoration: none;
  font-weight: 600;
  border-radius: .5rem;
  padding: .75rem 1.25rem;
  box-shadow: 0 2px 6px rgba(0,0,0,.2);
  transition: background-color .2s, transform .1s;
}
.sidebar-menu li.sidebar-academy a:hover {
  background-color: #ec407a;
  transform: translateY(-2px);
}
/* optional: make the icon a bit larger */
.sidebar-menu li.sidebar-academy .academy-icon {
  font-size: 1.25rem;
}

</style>



            <li class="sidebar-academy">
              <a href="https://www.accademiadelpasticcereimprenditore.com/" target="_blank" rel="noopener">
                <iconify-icon icon="mdi:school" class="academy-icon"></iconify-icon>
                <span>Accedi all’Accademia</span>
              </a>
            </li>
    
        </ul>
      </div>
    </aside>
    