 <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
            <li class="nav-item nav-category">*****************</li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('admin-panel')}}">
                    <i class="mdi mdi-home mdi-36px menu-icon"></i>
                    <span class="menu-title">{{__('sidebar.dashboard')}}</span>
                </a>
            </li>
            <li class="nav-item ">
                <a class="nav-link " data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                    <i class="mdi mdi-human-child mdi-36px"></i>
                    <span class="menu-title fw-bold ms-2">{{__('sidebar.user-system')}}</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="ui-basic">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link fs-6 fw-bold" href="{{route('admin.users.index')}}">
                                <i class="mdi mdi-human-male-female mdi-24px"></i>
                                {{__('sidebar.users')}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-6 fw-bold" href="{{route('admin.roles.index')}}">
                                <i class="mdi mdi-gesture-tap mdi-24px"></i>
                                {{__('sidebar.roles')}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-6 fw-bold" href="{{route('admin.permissions.index')}}">
                                <i class="mdi mdi-source-fork mdi-24px"></i>
                                {{__('sidebar.permissions')}}
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item ">
                <a class="nav-link " data-bs-toggle="collapse" data-bs-target="#ui-basic2" aria-expanded="false" aria-controls="ui-basic">
                    <i class="mdi mdi-food-turkey mdi-36px"></i>
                    <span class="menu-title fw-bold ms-2">{{__('sidebar.meals')}}</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="ui-basic2">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link fs-6 fw-bold" href="{{route('admin.meals.index')}}">
                                <i class="mdi mdi-hamburger mdi-24px me-2"></i>
                                {{__('sidebar.meal')}}
                            </a>
                        </li>
                        <li class="nav-item mt-2">
                            <a class="nav-link fs-6 fw-bold" href="{{route('admin.categories.index')}}">
                                <i class="mdi mdi-checkbox-marked-outline mdi-24px me-2"></i>
                                {{__('sidebar.category')}}
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </nav>
