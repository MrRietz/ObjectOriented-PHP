<!doctype html>
<html class='no-js' lang='<?= $lang ?>'> <!-- Modernizr will replace the class 'no-js' with a list of features supported by the browser -->
  <head>
    <meta charset='utf-8'/>
    <title><?= get_title($title) ?></title>

    <?php if (isset($favicon)): ?>
        <link rel='shortcut icon' href='<?= $favicon ?>'/>
    <?php endif ?>

    <?php foreach ($stylesheets as $val): ?>
        <?php if (isset($inlinestyle)): ?><style><?= $inlinestyle ?></style><?php endif; ?>
        <link rel='stylesheet' type='text/css' href='<?= $val ?>'/>
    <?php endforeach; ?>
    <script src='<?= $modernizr ?>'></script>
  </head>
  <body>
      <?php if (isset($navbar)): ?>
          <?= get_navbar($navbar) ?>
          <?= echoActiveClassIfRequestMatches("home.php") ?>
      <?php endif; ?>
    <div class='container-fluid'>
      <div id='header'>
          <?= $header ?>
      </div>
      <div class='row' id='content'>
        <div class ='col-md-8' id='main'>
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title"><?= $title?></h3>
            </div>
            <div class="panel-body">
              <div class="container-fluid">
                  <?= $main ?>
              </div>
            </div>
          </div>
        </div>
              <?php if (isset($sidebar)): ?>
                  <div class='col-md-4'>
                    <div class='panel panel-primary' id='sidebar'>
                      <div class="panel-heading">
                        <h3 class="panel-title"> <?= $sidebarTitle ?></h3>
                      </div>
                      <div class="panel-body">
                          <?php if (isset($sidenav)): ?>
                              <?= get_navbar($sidenav) ?> 
                          <?php endif; ?>
                          <?= $sidebar ?>
                      </div>
                    </div>
                  </div>
              <?php endif; ?>
            </div>

          </div> 
          <div id='footer'><?= $footer ?></div>

          <?php if (isset($jquery)): ?>
              <script src='<?= $jquery ?>'></script>
              <script src='js/bootstrap.min.js'></script>
          <?php endif; ?>

          <?php if (isset($javascript_include)): foreach ($javascript_include as $val): ?>
                  <script src='<?= $val ?>'></script>
                  <?php
              endforeach;
          endif;
          ?>

          <?php if (isset($google_analytics)): ?>
              <script>
                  var _gaq = [['_setAccount', '<?= $google_analytics ?>'], ['_trackPageview']];
                  (function (d, t) {
                      var g = d.createElement(t), s = d.getElementsByTagName(t)[0];
                      g.src = ('https:' == location.protocol ? '//ssl' : '//www') + '.google-analytics.com/ga.js';
                      s.parentNode.insertBefore(g, s)
                  }(document, 'script'));
              </script>
          <?php endif; ?>


          </body>
          </html>


