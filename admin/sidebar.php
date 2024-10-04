<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <?php
    $request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uriSegments = explode('/', trim($request, '/'));
    
    $active = [
        'admin' => '',
        'products' => '',
        'all_products' => '',
        'add_products' => '',
        'reviews' => '',
        'users' => '',
        'all_users' => '',
        'add_user' => '',
        'orders' => '',
        'coupons' => '',
        'all_coupons' => '',
        'add_coupons' => ''
    ];

    // Set active states
    switch ($uriSegments[0] ?? '') {
        case '':
        case 'index.php':
            $active['admin'] = 'active';
            break;
        case 'product':
            $active['products'] = 'active';
            if ($uriSegments[1] ?? '' === '' || $uriSegments[1] === 'index.php') {
                $active['all_products'] = 'active';
            } elseif ($uriSegments[1] === 'add.php') {
                $active['add_products'] = 'active';
            }
            break;
        case 'users':
            $active['users'] = 'active';
            if ($uriSegments[1] ?? '' === '' || $uriSegments[1] === 'index.php') {
                $active['all_users'] = 'active';
            } elseif ($uriSegments[1] === 'add.php') {
                $active['add_user'] = 'active';
            }
            break;
        case 'order':
            $active['orders'] = 'active';
            break;
        case 'review':
            $active['reviews'] = 'active';
            if ($uriSegments[1] ?? '' === '' || $uriSegments[1] === 'pending.php') {
                $active['reviews'] = 'active';
            }
            break;
        case 'coupon':
            $active['coupons'] = 'active';
            if ($uriSegments[1] ?? '' === '' || $uriSegments[1] === 'index.php') {
                $active['all_coupons'] = 'active';
            } 
            elseif ($uriSegments[1] === 'add.php') {
                $active['add_coupons'] = 'active';
            }
            break;
    }
    ?>

    <!-- Brand Logo -->
    <a href="<?php echo ADMIN_URL; ?>/index.php" class="brand-link">
        <img src="<?php echo ADMIN_URL; ?>/dist/img/AdminLTELogo.png" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Shop Admin</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <?php echo "<img src='" . BASE_URL . "/uploads/users/$imagename' class='img-circle elevation-2' alt='User Image'>" ?>
            </div>
            <div class="info">
                <a href="#" class="d-block">
                    <?php echo htmlspecialchars($username); ?>
                </a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="<?php echo ADMIN_URL; ?>/index.php" class="nav-link <?php echo $active['admin']; ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Orders -->
                <li class="nav-item <?php echo $active['orders'] ? 'menu-open' : ''; ?>">
                    <a href="<?php echo ADMIN_URL; ?>/order/index.php" class="nav-link <?php echo $active['orders']; ?>">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>Orders <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo ADMIN_URL; ?>/order/index.php" class="nav-link <?php echo $active['orders']; ?>">
                                <i class="fas fa-angle-right nav-icon"></i>
                                <p>All Orders</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Products -->
                <li class="nav-item <?php echo $active['products'] ? 'menu-open' : ''; ?>">
                    <a href="<?php echo ADMIN_URL; ?>/product/index.php" class="nav-link <?php echo $active['products']; ?>">
                        <i class="nav-icon fas fa-box"></i>
                        <p>Products <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo ADMIN_URL; ?>/product/index.php" class="nav-link <?php echo $active['all_products']; ?>">
                                <i class="fas fa-angle-right nav-icon"></i>
                                <p>All Products</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo ADMIN_URL; ?>/product/add.php" class="nav-link <?php echo $active['add_products']; ?>">
                                <i class="fas fa-angle-right nav-icon"></i>
                                <p>Add Products</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Reviews -->
                <li class="nav-item <?php echo $active['reviews'] ? 'menu-open' : ''; ?>">
                    <a href="<?php echo ADMIN_URL; ?>/review/index.php" class="nav-link <?php echo $active['reviews']; ?>">
                        <i class="nav-icon fa fa-star"></i>
                        <p>Reviews <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo ADMIN_URL; ?>/review/index.php" class="nav-link <?php echo $active['reviews']; ?>">
                                <i class="fas fa-angle-right nav-icon"></i>
                                <p>All Reviews</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Users -->
                <li class="nav-item <?php echo $active['users'] ? 'menu-open' : ''; ?>">
                    <a href="<?php echo ADMIN_URL; ?>/users/index.php" class="nav-link <?php echo $active['users']; ?>">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Users <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo ADMIN_URL; ?>/users/index.php" class="nav-link <?php echo $active['all_users']; ?>">
                                <i class="fas fa-angle-right nav-icon"></i>
                                <p>All Users</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo ADMIN_URL; ?>/users/add.php" class="nav-link <?php echo $active['add_user']; ?>">
                                <i class="fas fa-angle-right nav-icon"></i>
                                <p>Add New</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Coupons -->
                <li class="nav-item <?php echo $active['coupons'] ? 'menu-open' : ''; ?>">
                    <a href="<?php echo ADMIN_URL; ?>/coupon/index.php" class="nav-link <?php echo $active['coupons']; ?>">
                        <i class="nav-icon fas fa-ticket-alt"></i>
                        <p>Coupon <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo ADMIN_URL; ?>/coupon/index.php" class="nav-link <?php echo $active['all_coupons']; ?>">
                                <i class="fas fa-angle-right nav-icon"></i>
                                <p>All Coupons</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo ADMIN_URL; ?>/coupon/add.php" class="nav-link <?php echo $active['add_coupons']; ?>">
                                <i class="fas fa-angle-right nav-icon"></i>
                                <p>Add New Coupon</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
