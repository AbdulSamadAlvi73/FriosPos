		
		<!--**********************************
            Sidebar start
        ***********************************-->
        <div class="deznav" style="margin-top:-8px;">
            <div class="deznav-scroll">
				<ul class="metismenu" id="menu">
					
                    <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-025-dashboard"></i>
							<span class="nav-text">Dashboard</span>
						</a>
                        <ul aria-expanded="false">
							<li><a href="index.html">Inventory</a></li>
						</ul>

                    </li>
                    @role('corporate_admin')
                    <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
						<!-- <i class="flaticon-033-feather"></i> -->
						<i class="bi bi-box-seam-fill"></i>
						<span class="nav-text"><span>Franchise</span></span>
					</a>
					<ul aria-expanded="false">
							<li><a href="viewflavor.html">Franchises List</a></li>
							<li><a href="addflavor.html">Add Franchise</a></li>
						</ul>
					</li>
                    <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
						<!-- <i class="flaticon-033-feather"></i> -->
						<i class="bi bi-box-seam-fill"></i>
						<span class="nav-text"><span>Owner</span></span>
					</a>
					<ul aria-expanded="false">
							<li><a href="viewflavor.html">Owner List</a></li>
							<li><a href="addflavor.html">Add Owner</a></li>
						</ul>
					</li>
                    @endrole
                    @role('franchise_admin')
					<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
						<!-- <i class="flaticon-033-feather"></i> -->
						<i class="bi bi-box-seam-fill"></i>
						<span class="nav-text"><span>Flavors</span></span>
					</a>
					<ul aria-expanded="false">
							<li><a href="viewflavor.html">Flavors List</a></li>
							<li><a href="addflavor.html">Add Flavor</a></li>
						</ul>
					</li>
                    @endrole
                    @role('franchise_manager')
                    <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
						<!-- <i class="flaticon-033-feather"></i> -->
						<i class="bi bi-calendar-week-fill"></i>
						<span class="nav-text"><span>Event</span></span>
					</a>
					<ul aria-expanded="false">
							<li><a href="event.html">Events List</a></li>
							<li><a href="calender.html">Calender</a></li>
						</ul>
					</li>
                    <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
						<!-- <i class="flaticon-033-feather"></i> -->
						<i class="bi bi-bag-fill"></i>
						<span class="nav-text"><span>Staff</span></span>
					</a>
					<ul aria-expanded="false">
							<li><a href="vieworder.html">Staffs List</a></li>
							<li><a href="addorder.html">Add Staff</a></li>
						</ul>
					</li>
                    @endrole
                    @role('franchise_staff')
                    <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
						<!-- <i class="flaticon-033-feather"></i> -->
						<i class="bi bi-person-fill-add"></i>
						<span class="nav-text"><span>Customer</span></span>
					</a>
					<ul aria-expanded="false">
							<li><a href="viewcustomers.html">Customers List</a></li>
							<li><a href="addcustomer.html">Add Customer</a></li>
						</ul>
					</li>
					<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
						<!-- <i class="flaticon-033-feather"></i> -->
						<i class="bi bi-bag-fill"></i>
						<span class="nav-text"><span>Orders</span></span>
					</a>
					<ul aria-expanded="false">
							<li><a href="vieworder.html">Orders List</a></li>
							<li><a href="addorder.html">Add Order</a></li>
						</ul>
					</li>
                    @endrole
					
                </ul>
				<div class="copyright">
					<p><strong>Frios Management System</strong> © <span class="current-year">2023</span> All Rights Reserved</p>
					<!-- <p class="fs-12">Made with <span class="heart"></span> by DexignZone</p> -->
				</div>
			</div>
        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->