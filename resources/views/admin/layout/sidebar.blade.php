<div class="sidebar-wrapper sidebar-theme">

    <nav id="sidebar">
        <div class="profile-info">
            <figure class="user-cover-image"></figure>
            <div class="user-info">
                <img src="{{url('uploads/admin/profile_image/'.Session::get('admin_image'))}}" alt="avatar">
                <h6 class=""><?= Session::get('admin_name'); ?></h6>
                <p class=""></p>
            </div>
        </div>

        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">

            <li class="menu ">
                <a href="{{ url('admin/home') }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <!--                                <span class="sidebar-icon"><i class="fas fa-home"></i></span>-->
                        <span>Dashboard</span>
                    </div>
                </a>
            </li>
            <li class="menu ">
                <a href="{{ url('admin/drivers/map_view') }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <span class="sidebar-icon"><i class="fas fa-map-marker-alt"></i></span>
                        <span>Map View</span>
                    </div>
                </a>
            </li>
            <li class="menu">
                <a href="#vehicles" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <!--                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-truck"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>-->
                        <span class="sidebar-icon"><i class="fas fa-car-alt"></i></span>
                        <span>Vehicles</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="vehicles" data-parent="#accordionExample">

                    <li>
                        <a href="#vehicle-type" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> Vehicle Type <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg> </a>
                        <ul class="collapse list-unstyled sub-submenu" id="vehicle-type" data-parent="#vehicles">
                            <li>
                                <a href="{{ url('admin/vehicletype/create') }}"> Add Vehicle Type </a>
                            </li>
                            <li>
                                <a href="{{ url('admin/vehicletype/') }}"> View Vehicle Type </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#vehicle-sub-type" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> Vehicle Sub Type <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg> </a>
                        <ul class="collapse list-unstyled sub-submenu" id="vehicle-sub-type" data-parent="#vehicles">
                            <li>
                                <a href="{{ url('admin/vehicle_subtype/create') }}"> Add Vehicle Sub Type </a>
                            </li>
                            <li>
                                <a href="{{ url('admin/vehicle_subtype/') }}"> View Vehicle Sub Type </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#vehicle-make" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> Vehicle Make <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg> </a>
                        <ul class="collapse list-unstyled sub-submenu" id="vehicle-make" data-parent="#vehicles">
                            <li>
                                <a href="{{ url('admin/vehicle_make/create') }}"> Add Vehicle Make </a>
                            </li>
                            <li>
                                <a href="{{ url('admin/vehicle_make/') }}"> View Vehicle Make </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#vehicle-model" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> Vehicle Model <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg> </a>
                        <ul class="collapse list-unstyled sub-submenu" id="vehicle-model" data-parent="#vehicles">
                            <li>
                                <a href="{{ url('admin/vehicle_model/create') }}"> Add Vehicle Model </a>
                            </li>
                            <li>
                                <a href="{{ url('admin/vehicle_model/') }}"> View Vehicle Model </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>

            <li class="menu">
                <a href="#riders" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <!--                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-truck"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>-->
                        <span class="sidebar-icon"><i class="fas fa-users"></i></span>
                        <span>Riders</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="riders" data-parent="#accordionExample">
                    <li class="">
                        <a href="{{ url('admin/rider/create') }}"> Add Rider </a>
                    </li>
                    <li>
                        <a href="{{ url('admin/rider/') }}"> View Riders </a>
                    </li>
                </ul>
            </li>
            <li class="menu">
                <a href="#drivers" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <!--                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-truck"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>-->
                        <span class="sidebar-icon"><i class="fas fa-users"></i></span>
                        <span>Drivers</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="drivers" data-parent="#accordionExample">
                    <li>
                        <a href="{{ url('admin/driver/create') }}"> Add Driver </a>
                    </li>
                    <li>
                        <a href="{{ url('admin/driver/') }}"> View Drivers </a>
                    </li>
                </ul>
            </li>
            <li class="menu">
                <a href="#rides" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <!--                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-truck"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>-->
                        <span class="sidebar-icon"><i class="fas fa-car"></i></span>
                        <span>Rides</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="rides" data-parent="#accordionExample">
                    <li>
                        <a href="{{ url('admin/ride/') }}"> View Rides </a>
                    </li>
                </ul>
            </li>
            <li class="menu">
                <a href="#ride_rating" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <!--                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-truck"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>-->
                        <span class="sidebar-icon"><i class="fas fa-star"></i></span>
                        <span>Ratings</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="ride_rating" data-parent="#accordionExample">
                    <li>
                        <a href="{{ url('admin/ride_rating/') }}"> View Ratings </a>
                    </li>
                </ul>
            </li>
            <li class="menu">
                <a href="#service_area" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <!--                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-truck"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>-->
                        <span class="sidebar-icon"><i class="fas fa-globe"></i></span>
                        <span>Service Area</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="service_area" data-parent="#accordionExample">
                    <li>
                        <a href="{{ url('admin/service_area/country') }}"> Service Countries </a>
                    </li>
                    <li>
                        <a href="{{ url('admin/service_area/region') }}"> Service Regions </a>
                    </li>
                    <li>
                        <a href="{{ url('admin/service_area/city') }}"> Service Cities </a>
                    </li>
                </ul>
            </li>
            <li class="menu">
                <a href="#promo_code" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <span class="sidebar-icon"><i class="fas fa-money-bill"></i></span>
                        <span>Promo Codes</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="promo_code" data-parent="#accordionExample">
                    <li>
                        <a href="{{ url('admin/promo_code/') }}"> View Promo Codes</a>
                    </li>
                    <li>
                        <a href="{{ url('admin/promo_code/create') }}">Add Promo Code</a>
                    </li>
                </ul>
            </li>
            <li class="menu">
                <a href="#content_management" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <span class="sidebar-icon"><i class="fas fa-file"></i></span>
                        <span>Content Management</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="content_management" data-parent="#accordionExample">
                    <li>
                        <a href="{{ url('admin/content_management/terms_conditions/') }}">Terms & Condition</a>
                    </li>
                    <li>
                        <a href="{{ url('admin/content_management/privacy_policy/') }}">Privacy Policy</a>

                    </li>
                    <li>
                        <a href="{{ url('admin/content_management/user_agreement/') }}">User Agreement</a>
                    </li>
                </ul>
            </li>
            <li class="menu">
                <a href="#tickets" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <span class="sidebar-icon"><i class="fas fa-envelope"></i></span>
                        <span>Complaints</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="tickets" data-parent="#accordionExample">
                    <li>
                        <a href="{{ url('admin/ticket') }}">View Complaints</a>
                    </li>
                </ul>
            </li>
            <li class="menu">
                <a href="#setting" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <!--                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-truck"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>-->
                        <span class="sidebar-icon"><i class="fas fa-cogs"></i></span>
                        <span>Setting</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="setting" data-parent="#accordionExample">
                    <li>
                        <a href="#app_setting" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> App Setting <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg> </a>
                        <ul class="collapse list-unstyled sub-submenu" id="app_setting" data-parent="#setting">
                            <li>
                                <a href="{{ url('admin/rider_menu/create') }}"> Add Rider Menu </a>
                            </li>
                            <li>
                                <a href="{{ url('admin/rider_menu/') }}"> View Rider Menus </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#config" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> System Setting <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg> </a>
                        <ul class="collapse list-unstyled sub-submenu" id="config" data-parent="#setting">
                            <li>
                                <a href="{{ url('admin/config/general') }}"> General Configuration </a>
                            </li>
                            <li>
                                <a href="{{ url('admin/config/commission') }}"> Commission & Rides </a>
                            </li>

                        </ul>
                    </li>
                    <li>
                        <a href="#app_pages" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> Pages <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg> </a>
                        <ul class="collapse list-unstyled sub-submenu" id="app_pages" data-parent="#setting">
                            <li>
                                <a href="{{ url('admin/config/about_page') }}"> About </a>
                            </li>
                            <li>
                                <a href="{{ url('admin/config/help_page') }}"> Help </a>
                            </li>
                            <li>
                                <a href="{{ url('admin/config/eula_page') }}"> EULA </a>
                            </li>
                            <li>
                                <a href="{{ url('admin/config/terms_and_condition_page') }}"> T & C </a>
                            </li>
                            <li>
                                <a href="{{ url('admin/config/privacy_policy_page') }}"> Privacy Policy </a>
                            </li>
                        </ul>
                    </li>
                </ul>

            </li>

    </nav>


</div>