<div class="wrapper_divider">
    <div class="divider div-transparent div-dot"></div>
</div>
<br>

<style>
    .notification-list {
        height: 285px;
        overflow: hidden;
        padding: 0;
        margin: 0;
        list-style: none;
    }

    /* notice card */

    .notice-card {
        background: #ffffff;
        border-left: 4px solid #8b0000;
        padding: 10px 12px;
        margin-bottom: 10px;
        border-radius: 4px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
    }

    .notice-card a {
        color: #000;
        text-decoration: none;
    }

    .notice-card a:hover {
        color: #8b0000;
    }
</style>

<div class="holiday-box">

    <h4 class="holiday-title">Valedictorian List</h4>

    <div class="notification-list" id="notification-container">

        <div class="notice-card">
            Loading Valedictorian...
        </div>

    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function loadNotifications() {

        $.ajax({
            url: 'ajax_notification.php',
            method: 'GET',

            success: function(data) {

                $('#notification-container').html(data);

                startScroll();

            }

        });

    }

    loadNotifications();

    setInterval(loadNotifications, 30000);


    /* scrolling */

    function startScroll() {

        const box = document.getElementById("notification-container");

        let scroll = setInterval(function() {

            box.scrollTop += 1;

            if (box.scrollTop >= box.scrollHeight - box.clientHeight) {
                box.scrollTop = 0;
            }

        }, 60);

        box.addEventListener("mouseenter", function() {
            clearInterval(scroll);
        });

        box.addEventListener("mouseleave", function() {

            scroll = setInterval(function() {

                box.scrollTop += 1;

                if (box.scrollTop >= box.scrollHeight - box.clientHeight) {
                    box.scrollTop = 0;
                }

            }, 60);

        });

    }
</script>