<style>
    .forthcoming-box {
        background: #f5f5f5;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .forthcoming-title {
        color: #8b0000;
        font-size: 20px;
        text-transform: uppercase;
        margin-bottom: 15px;
        border-bottom: 1px dashed #ccc;
        padding-bottom: 5px;
    }

    .forthcoming-events {
        height: 300px;
        overflow-y: auto;
    }

    .forthcoming-events ul {
        list-style: none;
        padding-left: 0;
        margin-bottom: 0;
    }

    .forthcoming-events li {
        background: #ffffff;
        margin-bottom: 10px;
        padding: 12px 12px 12px 110px;
        border-left: 4px solid #8b0000;
        position: relative;
        min-height: 100px;
        transition: background 0.3s ease;
    }

    .forthcoming-events li:hover {
        background: #eaf2ff;
    }

    .forthcoming-events .date-box {
        width: 90px;
        height: 90px;
        background: #8b0000;
        color: #fff;
        position: absolute;
        left: 10px;
        top: 10px;
        text-align: center;
        font-weight: bold;
        font-family: 'Poppins', sans-serif;
        display: flex;
        flex-direction: column;
        justify-content: center;
        border: 2px solid #8b0000;
    }

    .forthcoming-events .date-box .day {
        font-size: 22px;
        line-height: 1.2;
    }

    .forthcoming-events .date-box .month {
        font-size: 14px;
        text-transform: uppercase;
    }

    .forthcoming-events .date-box .year {
        font-size: 12px;
    }

    .forthcoming-events .event-title {
        font-size: 18px;
        font-weight: 500;
        margin: 0;
        color: #000;
    }

    @media (max-width: 768px) {
        .forthcoming-events li {
            padding-left: 100px;
        }

        .forthcoming-events .event-title {
            font-size: 16px;
        }

        .forthcoming-events .date-box {
            width: 70px;
            height: 70px;
            font-size: 12px;
        }

        .forthcoming-events .date-box .day {
            font-size: 18px;
        }
    }
</style>

<div class="forthcoming-box">
    <h4 class="forthcoming-title">Forthcoming Events</h4>
    <div class="forthcoming-events">
        <ul id="events-container">
            <li><p class="event-title">Loading events...</p></li>
        </ul>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
function loadForthcomingEvents() {
    $.ajax({
        url: 'ajax_forthcoming_events.php', 
        method: 'GET',
        success: function (response) {
            $('#events-container').html(response);
        },
        error: function () {
            $('#events-container').html('<li><p class="event-title">Failed to load events.</p></li>');
        }
    });
}

loadForthcomingEvents(); 
setInterval(loadForthcomingEvents, 1000); 
</script>
