<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>{app:page:title}</title>
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="custom/themes/ehe-ini.de/css/print.css" type="text/css" media="print" />
  </head>
  <body class="standard">
    <div class="wrapAll">
      <div class="branding">
        <a href="#" class="toggleMenu">
          <img src="custom/themes/ehe-ini.de/images/menu.png" alt="Menü anzeigen" title="Menü anzeigen" />
        </a>
        <a href="index.html">
          <img src="custom/themes/ehe-ini.de/images/logo.png" alt="Ehe Initiative" title="Ehe Initiative" />
          <h1>Ehe-Initiative e.V.</h1>
        </a>
      </div>
      <bb:menu key="main" class="main" page_id="null">
        <bb:link>
          <bb:menu key="sub" class="sub" page_id="parent">
            <bb:link>
              {->getEvents($page_id)}
            </bb:link>
            <bb:active>
              {->getEvents($page_id)}
            </bb:active>
          </bb:menu>
        </bb:link>
        <bb:active>
          <bb:menu key="sub" class="sub" page_id="parent">
            <bb:link>
              {->getEvents($page_id)}
            </bb:link>
            <bb:active>
              {->getEvents($page_id)}
            </bb:active>
          </bb:menu>
        </bb:active>
      </bb:menu>
      <div class="content">
        <div class="jcarousel-wrapper">

          <a href="#" class="jcarousel-control-prev">&lsaquo;</a>
          <a href="#" class="jcarousel-control-next">&rsaquo;</a>

          <img src="custom/themes/ehe-ini.de/images/decker.png" class="decker" />
          <div class="jcarousel">
            <ul>
              {slideShowImages}
            </ul>
          </div>

          <p class="jcarousel-pagination">
          </p>

        </div>

        <bb:location key="main" number="1" class="main" />
      </div>
      <div class="footer">
        <ol class="socialMedia">
          <li><a href="http://www.facebook.com/eheini"><img src="custom/themes/ehe-ini.de/images/facebook.png" /></a></li>
          <li><a href="de/Kontakt.html"><img src="custom/themes/ehe-ini.de/images/phone.png" /></a></li>
          <li><a href="mailto:info@ehe-initiative.de"><img src="custom/themes/ehe-ini.de/images/mail.png" /></a></li>
        </ol>
        <p>Copyright &copy; 2015 Ehe-Initiative</p>
        <bb:menu key="footer" class="footer" page_id="null">
          <bb:link />
          <bb:active />
        </bb:menu>
      </div>
    </div>
  </body>
</html>
