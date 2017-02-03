/*
Theme by: WebThemez.com
Note: Please use our back link in your site
*/
$( function() {
        var endDate = "Feb 28, 2017 00:00:00";

        $('.countdown.simple').countdown({ date: endDate });

        $('.countdown.styled').countdown({
          date: endDate,
          render: function(data) {
            $(this.el).html("<div>" + this.leadingZeros(data.days, 2) + " <span>days</span></div><div>" + this.leadingZeros(data.hours, 2) + " <span>hrs</span></div><div>" + this.leadingZeros(data.min, 2) + " <span>min</span></div><div>" + this.leadingZeros(data.sec, 2) + " <span>sec</span></div>");
          }
        });

        $('.countdown.callback').countdown({
          date: +(new Date) + 10000,
          render: function(data) {
            $(this.el).text(this.leadingZeros(data.sec, 2) + " sec");
          },
          onEnd: function() {
            $(this.el).addClass('ended');
          }
        }).on("click", function() {
          $(this).removeClass('ended').data('countdown').update(+(new Date) + 10000).start();
        });
      });

$(document).ready(function(){
    $('#subscribeForm input[type=button]').click(function() {
        if($('#subscribeForm')[0].checkValidity()) {
            // sendSubscriber($('#email').val());
        } else {
            $('<input type="submit">').hide().appendTo($('#subscribeForm')).click().remove();        }
    });
});

function sendSubscriber(email) {
    var req = new XMLHttpRequest();
    var url = "/site.php";
    var params = "email=" + email;

    req.onreadystatechange = function(){
        try {
            if (req.readyState == 4) {
                if (req.status == 200) {
                    $('#alert').html(req.responseText);
                    $('#alert').modal({showClose: false});
                } else {
                    console.log("Error: " + req.responseText + "Status:"  + req.statusText);
                    $('#alert').html("There is a very strange bug. We're working on fixing it. Try again later, after a cup of coffee:)");
                    $('#alert').modal({showClose: false});
                }
            }
        }
        catch(e) {
            console.log("Error: " + e.description);
            alert("error");        }
    };

    req.open("POST", url, true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send(params);

}