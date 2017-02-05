<!doctype html>

<!-- TODO: Facebook share has wrong description -->

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<title>Political Revolution Volunteer Toolkit - The Political Revolution's one stop for digital tools. Don't see your
  app? Add it now.</title>
<meta property="og:image" content="http://www.revolutionkit.us/img/fb.png"/>
<meta property="og:url" content="http://www.revolutionkit.us"/>
<meta property="og:title" content="Political Revolution Volunteer Toolkit"/>
<meta property="og:description"
      content="Political Revolution Volunteer Toolkit - Revolution Kit - The Political Revolution's one stop for digital tools. Don't see your app? Add it now."/>
<link href='https://fonts.googleapis.com/css?family=Work Sans:400,700,800|Lato:400,300,100,700' rel='stylesheet'
      type='text/css'>
<link href='./css/volunteer-toolkit.css' rel='stylesheet' type='text/css'>
<body>
<div id='main-container'>
  <div id='title-hero'>
    <h1 class='neuton'>Political Revolution Volunteer Toolkit</h1>
    <h3 class='neuton'>All the online tools <span style='font-weight: 600'>you</span> can use to help progressives win
      throughout the United States.<br/>Take your pick, volunteer! Don't see your app? <a
          href='https://docs.google.com/forms/d/1UAizvRCcYD13byAAMarLpYs-OC8hLfQSgXLkKXbdjZ4/viewform' target='_blank'>Submit
        it here.</a></h3>
  </div>
  <div id='filters'>
    <form id='toolkit-filters'>
      <ul>
        <li class='lato'><input type='radio' name='f' value='All' id='All' checked="checked"/>
          <label for='All'>All</label></li>
        <li class='lato'><input type='radio' name='f' value='Official' id='Official'/>
          <label for='Official'>Official</label></li>
        <li class='lato'><input type='radio' name='f' value='Information' id='Information'/>
          <label for='Information'>Information</label></li>
        <li class='lato'><input type='radio' name='f' value='Activism' id='Activism'/>
          <label for='Activism'>Activism</label></li>
        <li class='lato'><input type='radio' name='f' value='Voting' id='Voting'/>
          <label for='Voting'>Voting</label></li>
        <li class='lato'><input type='radio' name='f' value='Phonebank' id='Phonebank'/>
          <label for='Phonebank'>Phonebank</label></li>
        <li class='lato'><input type='radio' name='f' value='Communication' id='Communication'/>
          <label for='Communication'>Communication</label></li>
        <li class='lato'><input type='radio' name='f' value='Games' id='Games'/>
          <label for='Games'>Games</label></li>
      </ul>
    </form>
  </div>
  <div id='canvas-area'>
    <p class='lato' id='loader'>Loading...</p>
  </div>
</div>
<footer class='lato'>
  <div class="fb-share-button" data-href="http://www.revolutionkit.us" data-layout="button">
    <a href="javascript:fbShare('http://www.revolutionkit.us', 'Fb Share', 'Facebook share popup', 'http://www.revolutionkit.us/img/fb.png', 520, 350)">Share</a>
  </div>
  &nbsp;
  <a href="https://twitter.com/share" class="twitter-share-button" {count} data-url="http://www.revolutionkit.us"
     data-text="All the online tools you can use to help progressives win throughout the United States. #PolRev">Tweet</a>&nbsp;
  <script>
    !function (d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
      if (!d.getElementById(id)) {
        js = d.createElement(s);
        js.id = id;
        js.src = p + '://platform.twitter.com/widgets.js';
        fjs.parentNode.insertBefore(js, fjs);
      }
    }(document, 'script', 'twitter-wjs');</script>
  <a href='http://www.political-revolution.com/donate' class='donate-button'>Donate</a>
  <span>&nbsp;&nbsp;&nbsp;
    &copy; <a href='http://www.reddit.com/r/political_revolution'
              style='display: inline-block'>Political Revolution</a> &bull; Contact <a
        href='mailto:tech@political-revolution.com'>tech@political-revolution.com</a></span>
</footer>
<script type='text/javascript' src="js/d3.js" charset="utf-8"></script>
<script type='text/javascript' src='js/jquery.js'></script>
<script type='text/javascript' src='js/deparam.js'></script>
<script type='text/javascript'>
  window.VolunteerToolkit = (function ($) {

    var VolunteerToolkit = function (initialFilter) {
      //initialFilter :: The filter that was inputted. If empty ignore
      this.DATA_URL = './data.php';
      this._currentFilter = initialFilter == undefined ? "All" : initialFilter;

      this.columnSize = 4; // Default
      this._initialized = false;

      this._pigeonhole = function (item) {
        var that = this;
        for (var x in that.columns) {
          var height = that.columns[x].reduce(function (sum, item) {
            return sum + $(item).height()
          }, 0);
        }
      };

      this.render = function (filter) {
        var that = this;
        if (!filter || filter == undefined) {
          filter = that._currentFilter;
        }
        else {
          that._currentFilter = filter;
        }

        //Setting columnSizes...
        var $canvasArea = $("#canvas-area");
        if ($canvasArea.width() > 900) {
          that.columnSize = 4;
        } else if ($canvasArea.width() > 600) {
          that.columnSize = 3;
        } else {
          that.columnSize = 2;
        }
        $canvasArea.attr("data-colcount", that.columnSize);

        var dataToShow = that.data;
        if (filter != 'All') {
          dataToShow = dataToShow.filter(function (d) {
            switch (filter) {
              case 'Official':
                return d.official;
              case 'Information':
                return d.info;
              case 'Activism':
                return d.activism;
              case 'Voting':
                return d.voting;
              case 'Phonebank':
                return d.phonebank;
              case 'Communication':
                return d.comms;
              case 'Games':
                return d.games;
            }
            ;
          });
        }

        // Append all items if necessary
        var items = d3.select("#canvas-area").selectAll("div.item")
          .data(dataToShow, function (d) {
            return d.url;
          });
        /* set url as ID */

        items.enter()
          .append("div")
          .classed("item", true)
          .classed("is-new", function (d) {
            return d.isNew;
          })
          .html(function (d, i) {
            var html = "<div class='site-image' style='background-image: url(" + d.image + ")'><a class='lato' target='_blank' href='" + d.url + "'></a></div>"
              + "<div class='content'>"
              + "<h2 class='neuton'><a target='_blank' href='" + d.url + "'>" + d.title + "</a></h2>"
              + "<p class='lato'>" + d.description + "</p>"
              + "<a class='lato' href='" + d.url + "' target='_blank'>Go to site</a>";

            if (d.isNew) {
              html = "<div class='is-new-tag'>New!</div>" + html;
            }
            ;
            return html;
          });

        items.exit()
          .each(function (d) {
            d3.select(this).transition().style("opacity", 0)
              .each("end", function () {
                d3.select(this).remove();
              });
          });


        var columns = [];

        items
          .each(function (d, ind) {
            //Find proper column to put.
            var target = 0;
            var bottom = -1;
            for (var i = 0; i < that.columnSize; i++) {
              if (columns[i] == undefined || !columns) {
                target = i;
                bottom = 0;
                break;
              }

              if (bottom == -1 || bottom > columns[i]) {
                target = i;
                bottom = columns[i];
              }
            }

            // assume that the column by this time has been chosen
            var left = (target * (100 / that.columnSize));
            // $(this).css({ top: (bottom+20)+"px", left: left+"px" });
            // if (that._initialized) {
            d3.select(this)
              .transition()
              .duration(500)
              .style("opacity", 1);
            //   .style("opacity", 1)
            //   .style("top", (bottom)+"px")
            //   .style("left", left+"px");
            // } else {
            d3.select(this)
              .style("top", (bottom) + "px")
              .style("left", left + "%");

            columns[target] = $(this).position().top + $(this).height() + 20;
          }); // end of items.each()..

        d3.select("#canvas-area").style("height", d3.max(columns) + "px");

        that._initialized = true;


      };

      this.initialize = function () {
        var that = this;
        d3.csv(that.DATA_URL,
          function (d) {
            return {
              // parse items in obj
              url: d.url,
              title: d.title,
              description: d.description,
              image: d.image,
              official: d.official == "1",
              info: d.info == "1",
              activism: d.activism == "1",
              voting: d.voting == "1",
              phonebank: d.phonebank == "1",
              comms: d.comms == "1",
              games: d.games == "1",
              isNew: d.isNew == "1"
            };
          },
          function (err, data) {
            that.data = data;

            //Push new items to the top
            var newItems = [];
            for (var i = that.data.length - 1; i > 0; i--) {
              if (that.data[i].isNew) {
                newItems.push(that.data.splice(i, 1)[0]);
              }
            }
            that.data = newItems.concat(that.data);


            that.render(this._currentFilter);

            d3.select("#loader").remove();
          });
      };

      this.initialize();
    };

    return {loaded: true, toolkit: VolunteerToolkit};
    //Load data

    //render data

    //React to filters
  })(jQuery);

  window.Manager = {};
  (function ($, window) {
    //listen to hashchange
    $("#toolkit-filters").on("submit", function () {
      window.location.hash = $(this).serialize();
      return false;
    });

    $("#toolkit-filters input[name=f]").on("change", function (event) {
      $("#toolkit-filters").submit();
    });

    $(window).on('hashchange', function () {
      var params = $.deparam(window.location.hash.substring(1));

      if (params.f) {
        $("input[name=f][id=" + params.f + "]").attr("checked", "checked");
      }
      if (!window.Manager.toolkit) {
        window.Manager.toolkit = new window.VolunteerToolkit.toolkit(params.f);
      } else {
        window.Manager.toolkit.render(params.f);
      }
    });
    $(window).trigger("hashchange");

    var rtime;
    var timeout = false;
    var delta = 200;

    $(window).on('resize', function () {
      rtime = new Date();
      if (timeout === false) {
        timeout = true;
        setTimeout(resizeEnd, delta);
      }
    });

    function resizeEnd() {
      if (new Date() - rtime < delta) {
        setTimeout(resizeEnd, delta);
      } else {
        timeout = false;
        window.Manager.toolkit.render();
      }
    }

  })(jQuery, window);
</script>
<script>
  function fbShare(url, title, descr, image, winWidth, winHeight) {
    var winTop = (screen.height / 2) - (winHeight / 2);
    var winLeft = (screen.width / 2) - (winWidth / 2);
    window.open('http://www.facebook.com/sharer.php?s=100&p[title]=' + title + '&p[summary]=' + descr + '&p[url]=' + url + '&p[images][0]=' + image, 'sharer', 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight);
  }
</script>
</body>
