<style>
  .holiday-box {
    background: #f5f5f5;
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
  }

  .holiday-title {
    color: #8b0000;
    font-size: 20px;
    text-transform: uppercase;
    margin-bottom: 15px;
    border-bottom: 1px dashed #ccc;
    padding-bottom: 5px;
  }

  .holiday-list {
    list-style: none;
    padding-left: 0;
    height: 285px;
    overflow-y: auto;
  }

  .holiday-list li {
    background: #fff;
    margin-bottom: 10px;
    padding: 12px 12px 12px 110px;
    border-left: 4px solid #8b0000;
    position: relative;
    min-height: 100px;
    transition: background 0.3s ease;
  }

  .holiday-list li:hover {
    background: #eaf2ff;
  }

  .holiday-date-box {
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

  .holiday-date-box .day {
    font-size: 22px;
    line-height: 1.2;
  }

  .holiday-date-box .month {
    font-size: 14px;
    text-transform: uppercase;
  }

  .holiday-date-box .year {
    font-size: 12px;
  }

  .holiday-name {
    font-size: 18px;
    font-weight: 500;
    margin: 0;
    color: #8b0000;
    text-transform: uppercase;
  }

  @media (max-width: 768px) {
    .holiday-list li {
      padding-left: 100px;
    }

    .holiday-date-box {
      width: 70px;
      height: 70px;
    }

    .holiday-date-box .day {
      font-size: 18px;
    }

    .holiday-name {
      font-size: 16px;
    }
  }
</style>

<div class="holiday-box">
  <h4 class="holiday-title">Holiday List</h4>
  <ul class="holiday-list" id="holiday-list">
    <li><p class="holiday-name">Loading holidays...</p></li>
  </ul>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
function loadHolidays() {
  $.ajax({
    url: 'ajax_holiday_list.php', 
    method: 'GET',
    cache: false,
    success: function (data) {
      $('#holiday-list').html(data);
    },
    error: function () {
      $('#holiday-list').html('<li><p class="holiday-name">Error loading holidays.</p></li>');
    }
  });
}

loadHolidays(); 
setInterval(loadHolidays, 1000); 
</script>
