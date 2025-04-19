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


















            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="index.html">AI</a></li>
                    <li><a href="index-2.html">CRM</a></li>
                </ul>
            </li>














{{-- 
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:home-smile-angle-outline1" class="menu-icon"></iconify-icon>
                    <span>Ingredients</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('ingredients.create') }}"><i
                                class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Create</a>
                    </li>
                    <li>
                        <a href="{{ route('ingredients.index') }}"><i
                                class="ri-circle-fill circle-icon text-warning-main w-auto"></i> List</a>
                    </li>
                </ul>
            </li> --}}




            <li>
                <a href="{{ route('ingredients.index') }}">
                  <iconify-icon icon="bi:chat-dots" class="menu-icon"></iconify-icon>
                  <span>Ingredients</span>
              </a>
              
            </li>

            <li>
                <a href="{{ route('recipe-categories.index') }}">
                  <iconify-icon icon="bi:chat-dots" class="menu-icon"></iconify-icon>
                  <span>Recipee Category</span>
              </a>
              
            </li>
            <li>
                <a href="{{ route('costs.dashboard') }}">
                  <iconify-icon icon="bi:chat-dots" class="menu-icon"></iconify-icon>
                  <span>Cost Comparison</span>
              </a>
              
            </li>



            <li>
                <a href="{{ route('comparison.index') }}">
                  <iconify-icon icon="bi:chat-dots" class="menu-icon"></iconify-icon>
                  <span>Sale Comparison</span>
              </a>
              
            </li>























            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:home-smile-angle-outline1" class="menu-icon"></iconify-icon>
                    <span>Income</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('incomes.create') }}"><i
                                class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Create</a>
                    </li>
                    <li>
                        <a href="{{ route('incomes.index') }}"><i
                                class="ri-circle-fill circle-icon text-warning-main w-auto"></i> List</a>
                    </li>
                </ul>

            </li>






            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:home-smile-angle-outline1" class="menu-icon"></iconify-icon>
                    <span>Recipe</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('recipes.create') }}"><i
                                class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Create</a>
                    </li>
                    <li>
                        <a href="{{ route('recipes.index') }}"><i
                                class="ri-circle-fill circle-icon text-warning-main w-auto"></i> List</a>
                    </li>
                </ul>

            </li>























            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:home-smile-angle-outline1" class="menu-icon"></iconify-icon>
                    <span>External Supplies</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('external-supplies.create') }}"><i
                                class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Create</a>
                    </li>
                    <li>
                        <a href="{{ route('external-supplies.index') }}"><i
                                class="ri-circle-fill circle-icon text-warning-main w-auto"></i> List</a>
                    </li>
                </ul>

            </li>



            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:home-smile-angle-outline1" class="menu-icon"></iconify-icon>
                    <span>Clietn</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('clients.create') }}"><i
                                class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Create</a>
                    </li>
                    <li>
                        <a href="{{ route('clients.index') }}"><i
                                class="ri-circle-fill circle-icon text-warning-main w-auto"></i> List</a>
                    </li>
                </ul>

            </li>










































            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:home-smile-angle-outline1" class="menu-icon"></iconify-icon>
                    <span>Showcase</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('showcase.create') }}"><i
                                class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Create</a>
                    </li>
                    <li>
                        <a href="{{ route('showcase.index') }}"><i
                                class="ri-circle-fill circle-icon text-warning-main w-auto"></i> List</a>
                    </li>
                </ul>


            </li>





            
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:home-smile-angle-outline1" class="menu-icon"></iconify-icon>
                    <span>Categories</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('cost_categories.create') }}"><i
                                class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Create</a>
                    </li>
                    <li>
                        <a href="{{ route('cost_categories.index') }}"><i
                                class="ri-circle-fill circle-icon text-warning-main w-auto"></i> List</a>
                    </li>
                </ul>
            </li>




            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:home-smile-angle-outline1" class="menu-icon"></iconify-icon>
                    <span>Cost Managment</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('costs.create') }}"><i
                                class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Create</a>
                    </li>
                    <li>
                        <a href="{{ route('costs.index') }}"><i
                                class="ri-circle-fill circle-icon text-warning-main w-auto"></i> List</a>
                    </li>
                </ul>
            </li>


            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:home-smile-angle-outline1" class="menu-icon"></iconify-icon>
                    <span>Department Managment</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('departments.create') }}"><i
                                class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Create</a>
                    </li>
                    <li>
                        <a href="{{ route('departments.index') }}"><i
                                class="ri-circle-fill circle-icon text-warning-main w-auto"></i> List</a>
                    </li>
                </ul>
            </li>








            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:home-smile-angle-outline1" class="menu-icon"></iconify-icon>
                    <span>News</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('newss.create') }}"><i
                                class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Create</a>
                    </li>
                    <li>
                        <a href="{{ route('newss.index') }}"><i
                                class="ri-circle-fill circle-icon text-warning-main w-auto"></i> List</a>
                    </li>
                </ul>
            </li>








            
          <li class="dropdown">
            <a href="javascript:void(0)">
                <iconify-icon icon="solar:home-smile-angle-outline1" class="menu-icon"></iconify-icon>
                <span>Pastry-chefs</span>
            </a>
            <ul class="sidebar-submenu">
                <li>
                    <a href="{{ route('pastry-chefs.create') }}"><i
                            class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Create</a>
                </li>
                <li>
                    <a href="{{ route('pastry-chefs.index') }}"><i
                            class="ri-circle-fill circle-icon text-warning-main w-auto"></i> List</a>
                </li>
            </ul>
        </li>






        <li class="dropdown">
            <a href="javascript:void(0)">
                <iconify-icon icon="solar:home-smile-angle-outline1" class="menu-icon"></iconify-icon>
                <span>Equipments</span>
            </a>
            <ul class="sidebar-submenu">
                <li>
                    <a href="{{ route('equipment.create') }}"><i
                            class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Create</a>
                </li>
                <li>
                    <a href="{{ route('equipment.index') }}"><i
                            class="ri-circle-fill circle-icon text-warning-main w-auto"></i> List</a>
                </li>
            </ul>
        </li>










        <li class="dropdown">
            <a href="javascript:void(0)">
                <iconify-icon icon="solar:home-smile-angle-outline1" class="menu-icon"></iconify-icon>
                <span>Production</span>
            </a>
            <ul class="sidebar-submenu">
                <li>
                    <a href="{{ route('production.create') }}"><i
                            class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Create</a>
                </li>
                <li>
                    <a href="{{ route('production.index') }}"><i
                            class="ri-circle-fill circle-icon text-warning-main w-auto"></i> List</a>
                </li>
            </ul>
        </li>












        








            <li>
              <a href="{{ route('labor-cost.index') }}">
                <iconify-icon icon="bi:chat-dots" class="menu-icon"></iconify-icon>
                <span>Labor Cost</span>
            </a>
            
          </li>














































































        </ul>
    </div>
</aside>
