<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
            <div class="logo">
    <a href="./" class="logo-link">
        <img src="uploads/logo.png" alt="Logo" class="logo-img">
        <span class="logo-text">SMK AL AMANAH</span>
    </a>
</div>


                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block">
                        <i class="bi bi-x bi-middle"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

                <li class="sidebar-item">
                    <a href="./" class="sidebar-link">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="profil.php" class="sidebar-link">
                        <i class="bi bi-person-fill"></i>
                        <span>Profil</span>
                    </a>
                </li>   
                <li class="sidebar-item">
                    <a href="ekstrakurikuler.php" class="sidebar-link">
                        <i class="bi bi-grid-fill"></i>
                        <span>Ekstrakurikuler</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="bobot.php" class="sidebar-link">
                        <i class="bi bi-list-check"></i>
                        <span>kriteria</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="input_nilai.php" class="sidebar-link">
                        <i class="bi bi-star-fill fs-4"></i>
                        <span>input nilai</span>
                    </a>
                </li>   
                <li class="sidebar-item">
                    <a href="nilai_hasil.php" class="sidebar-link">
                        <i class="bi bi-star-fill fs-4"></i>
                        <span>nilai</span>
                    </a>
                </li> 
                <li class="sidebar-item">
                    <a href="hasil.php" class="sidebar-link">
                        <i class="bi bi-bar-chart-fill"></i>
                        <span>Hasil</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="logout.php" class="sidebar-link">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>

        <button class="sidebar-toggler btn x">
            <i data-feather="x"></i>
        </button>
    </div>
</div>

<style>
/* Sidebar Styling */
#sidebar {
    position: fixed;
    top: 0;
    left: -300px; /* Initially hidden off-screen */
    width: 300px;
    height: 100%;
    background-color: #2c3e50;
    transition: all 0.4s ease-in-out; /* Smooth transition for sliding effect */
    box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
    z-index: 1000;
}

#sidebar.active {
    left: 0; /* Bring the sidebar into view */
}

.sidebar-wrapper {
    height: 100%;
    overflow-y: auto;
}

.sidebar-header {
    padding: 20px;
    background-color:rgb(247, 248, 250);
    color: white;
    font-weight: bold;
}

.sidebar-menu {
    padding: 20px 0;
}

.sidebar-item {
    padding: 12px 20px;
    margin-bottom: 10px;
    transition: 0.3s;
    border-radius: 8px;
}

.sidebar-link {
    color: #ecf0f1;
    text-decoration: none;
    display: flex;
    align-items: center;
    font-size: 16px;
}

.sidebar-link i {
    margin-right: 15px;
    transition: transform 0.3s ease;
}

.sidebar-link:hover i {
    transform: rotate(10deg); /* Rotate icon on hover */
}

.sidebar-item:hover {
    background-color: #1abc9c;
    cursor: pointer;
    transform: translateX(10px); /* Sidebar item shifts when hovered */
}

.submenu {
    list-style: none;
    padding-left: 20px;
}

.submenu-item {
    padding: 10px 0;
}

.sidebar-toggler {
    background-color: #34495e;
    border: none;
    color: #ecf0f1;
    font-size: 20px;
    cursor: pointer;
}

/* Button to hide sidebar */
.sidebar-hide {
    color: #ecf0f1;
}

/* Mobile responsiveness */
@media (max-width: 991px) {
    #sidebar {
        left: -300px;
    }
    
    #sidebar.active {
        left: 0;
    }
}

.logo {
    background-color: #fff; /* Warna putih */
    padding: 10px 20px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: flex-start;
}

.logo-link {
    display: flex;
    align-items: center;
    text-decoration: none;
}

.logo-img {
    height: 40px;
    margin-right: 10px;
}

.logo-text {
    color: #2c3e50;
    font-weight: bold;
    font-size: 16px;
    white-space: nowrap; /* Mencegah teks turun ke bawah */
}


</style>

<script>
    // Toggle sidebar visibility
    const sidebarToggle = document.querySelector('.sidebar-hide');
    const sidebar = document.getElementById('sidebar');

    sidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('active');
    });

    const sidebarToggler = document.querySelector('.sidebar-toggler');
    sidebarToggler.addEventListener('click', function() {
        sidebar.classList.remove('active');
    });
</script>
