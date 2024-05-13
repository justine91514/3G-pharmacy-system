<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Pharmacy App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        #accordionSidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%; /* Set the height to fill the viewport */
            z-index: 1000; /* Ensure the navbar appears above other content */
            width: 250px; /* Adjust the width as needed */
            border-right: 1px solid #ccc;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        /* Adjust padding for the main content area */
        #content {
            padding-left: 250px; /* Ensure the content is not hidden behind the sidebar */
            padding-bottom: 50px; /* Adjust this value based on the height of your footer */

        }

        .nav-item.active {
            background-color: black !important;
        }

        .nav-item.active span,
        .nav-item.active i {
            color: white !important;
        }

        #inventory-tab #collapseTwo .collapse-inner a:hover,
        #settings-tab #collapseUtilities .collapse-inner a:hover {
            background-color: lightgray !important;
        }

        .sidebar-brand-icon {
            margin-top: 40px; /* Adjust the margin-top value according to your preference */
        }



    
</style>

</head>
<body>


<ul class="navbar-nav bg-white sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon">
        <img src="img/3GMED.png" alt="Pharmacy Logo" width="500" height="500" style="max-width: 130px; max-height: 100px;">
        </div>
    </a>

    <div style="height: 45px;"></div>

    <hr class="sidebar-divider my-0">

    <li class="nav-item" id="pos-tab">
        <a class="nav-link" href="pos.php" onclick="toggleTab('pos-tab')">
            <i class="fas fa-cash-register" style="color: black;"></i>
            <span style="color: black;">POS</span>
        </a>
    </li>
    <hr class="sidebar-divider my-0">
    <li class="nav-item" id="transaction-history-tab">
        <a class="nav-link" href="transaction_history_pos.php" onclick="toggleTab('transaction-history-tab')">
            <i class="fas fa-history" style="color: black;"></i>
            <span style="color: black;">Transaction History</span>
        </a>
    </li>
    <hr class="sidebar-divider my-0">
    

    <div style="height: 250px;"></div>

    
</ul>

<!-- Include Bootstrap JS script -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+WyL/JYb6Ze72LATIcunF1+OfwGgmkHQs6"
        crossorigin="anonymous"></script>

        
<script>
    // Function to toggle active tab and store in sessionStorage
    function toggleTab(tabId) {
        var selectedTab = document.getElementById(tabId);
        var allTabs = document.querySelectorAll('.nav-item');

        allTabs.forEach(function (tab) {
            if (tab.id === tabId) {
                tab.classList.add('active');
            } else {
                tab.classList.remove('active');
            }
        });

        sessionStorage.setItem('activeTab', tabId);
    }

    // Function to set active tab based on sessionStorage
    function setActiveTab() {
        var activeTabId = sessionStorage.getItem('activeTab');
        if (activeTabId) {
            toggleTab(activeTabId);
        }
    }

    // Set active tab when the page loads
    document.addEventListener("DOMContentLoaded", setActiveTab);
</script>

</body>
</html>
