---------------------------------------------------------------
Anmeldung zu {echo $eventData->Veranstaltungsart;} {echo $eventData->Veranstaltungstitel;} am {echo strftime('%A, %d. %B %Y', $eventData->Veranstaltungsstartdatum);}
{if(!empty($eventData->Veranstaltungsstartdatum2)):}
 und am {echo strftime('%A, %d. %B %Y', $eventData->Veranstaltungsstartdatum2);}
{endif;}
---------------------------------------------------------------


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Anmeldung zu {echo $eventData->Veranstaltungsart;} {echo $eventData->Veranstaltungstitel;} am {echo strftime('%A, %d. %B %Y', $eventData->Veranstaltungsstartdatum);}{if(!empty($eventData->Veranstaltungsstartdatum2)):} und am {echo strftime('%A, %d. %B %Y', $eventData->Veranstaltungsstartdatum2);}{endif;}</title>
    <style type="text/css">
      body {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        margin:0 !important;
        width: 100% !important;
        -webkit-text-size-adjust: 100% !important;
        -ms-text-size-adjust: 100% !important;
        -webkit-font-smoothing: antialiased !important;
      }
      .tableContent img {
        border: 0 !important;
        display: block !important;
        outline: none !important;
      }
      a{
        color:#382F2E;
      }

      p, h1{
        color:#382F2E;
        margin:0;
      }
      p{
        text-align:left;
        color:#999999;
        font-size:14px;
        font-weight:normal;
        line-height:19px;
      }

      a.link1{
        color:#382F2E;
      }
      a.link2{
        font-size:16px;
        text-decoration:none;
        color:#ffffff;
      }

      h2{
        text-align:left;
        color:#222222;
        font-size:19px;
        font-weight:normal;
      }
      div,p,ul,h1{
        margin:0;
      }

      .bgBody{
        background: #ffffff;
      }
      .bgItem{
        background: #ffffff;
      }

    </style>

    <script type="colorScheme" class="swatch active">
{
    "name":"Default",
    "bgBody":"ffffff",
    "link":"382F2E",
    "color":"999999",
    "bgItem":"ffffff",
    "title":"222222"
}
</script>

  </head>
  <body paddingwidth="0" paddingheight="0"   style="padding-top: 0; padding-bottom: 0; padding-top: 0; padding-bottom: 0; background-repeat: repeat; width: 100% !important; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased;" offset="0" toppadding="0" leftpadding="0">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableContent bgBody" align="center"  style='font-family:Helvetica, Arial,serif;'>


      <tr><td height='35'></td></tr>

      <tr>
        <td>
          <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" class='bgItem'>
            <tr>
              <td width='40'></td>
              <td width='520'>
                <table width="520" border="0" cellspacing="0" cellpadding="0" align="center">

                  <!-- =============================== Header ====================================== -->


                  <tr><td height='75'></td></tr>
                  <!-- =============================== Body ====================================== -->

                  <tr>
                    <td class='movableContentContainer ' valign='top'>

                      <div class='movableContent'>
                        <table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
                          <tr>
                            <td valign='top' align='center'>
                              <div class="contentEditableContainer contentTextEditable">
                                <div class="contentEditable">
                                  <p style='text-align:center;margin:0;font-family:Georgia,Time,sans-serif;font-size:26px;line-height:34px;color:#222222;'>Anmeldung zu {echo $eventData->Veranstaltungsart;} {echo $eventData->Veranstaltungstitel;} am {echo strftime('%A, %d. %B %Y', $eventData->Veranstaltungsstartdatum);}{if(!empty($eventData->Veranstaltungsstartdatum2)):} und am {echo strftime('%A, %d. %B %Y', $eventData->Veranstaltungsstartdatum2);}{endif;}</p>
                                </div>
                              </div>
                            </td>
                          </tr>
                        </table>
                      </div>

                      <div class='movableContent'>
                        <table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
                          <tr>
                            <td valign='top' align='center'>
                              <div class="contentEditableContainer contentImageEditable">
                                <div class="contentEditable">
                                  <img src="cid:logo.png" width='182' height='119' alt='' data-default="placeholder" data-max-width="560">
                                </div>
                              </div>
                            </td>
                          </tr>
                        </table>
                      </div>

                      <div class='movableContent'>
                        <table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
                          <tr><td height='55'></td></tr>
                          <tr>
                            <td align='left'>
                              <div class="contentEditableContainer contentTextEditable">
                                <div class="contentEditable" align='center'>
                                  <h2>Vielen Dank für Ihre Anmeldung.</h2>
                                </div>
                              </div>
                            </td>
                          </tr>

                          <tr><td height='15'> </td></tr>

                          <tr>
                            <td align='left'>
                              <div class="contentEditableContainer contentTextEditable">
                                <div class="contentEditable" align='center'>
                                  <p >

                                    Personendaten:<br /><br />

                                    {$salutation_label}: {$salutation}<br />
                                    {$firstname_label}: {$firstname}<br />
                                    {$lastname_label}: {$lastname}<br />
                                    {$birthdate_label}: {$birthdate}<br />
                                    {$street_label}: {$street}<br />
                                    {$city_label}: {$city}<br />
                                    {$fon_label}: {$fon}<br />
                                    {$email_label}: {$email}<br />
                                    {$salutation_partner_label}: {$salutation_partner}<br />
                                    {$firstname_partner_label}: {$firstname_partner}<br />
                                    {$lastname_partner_label}: {$lastname_partner}<br />
                                    {$birthdate_partner_label}: {$birthdate_partner}<br />
                                    {$weddingdate_label}: {$weddingdate}<br />
                                    {$engagementdate_label}: {$engagementdate}<br /><br />

                                    {if($eventData->Veranstaltungsart == 'Ehe-Intensiv'):}
                                    Zimmerbuchung:<br /><br />

                                    {if($room == 1):}
                                    Doppelzimmer Variante 1<br />mit Waschbecken auf dem Zimmer (Dusche/WC auf der Etage)<br /><br />
                                    {else:}
                                    Doppelzimmer Variante 2<br />mit Waschbecken, Dusche, WC<br />auf dem Zimmer<br /><br />
                                    {endif;}

                                    {endif;}

                                    {$remarks_label}:<br /><br />

                                    {$remarks}<br />
                                  </p>
                                </div>
                              </div>
                            </td>
                          </tr>

                          <tr><td height='55'></td></tr>

                          <tr>
                            <td align='center'>
                              <table>
                                <tr>
                                  <td align='center' bgcolor='#1A54BA' style='background:#1A54BA; padding:15px 18px;-webkit-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px;'>
                                    <div class="contentEditableContainer contentTextEditable">
                                      <div class="contentEditable" align='center'>
                                        <a target='_blank' href='http://www.ehe-initiative.de' class='link2' style='color:#ffffff;'>Zur Homepage</a>
                                      </div>
                                    </div>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                          <tr><td height='20'></td></tr>
                        </table>
                      </div>

                      <div class='movableContent'>
                        <table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
                          <tr><td height='65'></td></tr>
                          <tr><td  style='border-bottom:1px solid #DDDDDD;'></td></tr>

                          <tr><td height='25'></td></tr>

                          <tr>
                            <td>
                              <table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
                                <tr>
                                  <td valign='top' align='left' width='370'>
                                    <div class="contentEditableContainer contentTextEditable">
                                      <div class="contentEditable" align='center'>
                                        <p  style='text-align:left;color:#CCCCCC;font-size:12px;font-weight:normal;line-height:20px;'>
                                          <span style='font-weight:bold;'>Ehe-Initiative e.V.</span>
                                          <br>
                                          Büro: Friedrich & Heidi Erhardt<br />
                                          Im Gässle 5<br />
                                          79312 Emmendingen<br />
                                          <br>
                                        </p>
                                      </div>
                                    </div>
                                  </td>

                                  <td width='30'></td>

                                  <td valign='top' width='52'>
                                    <div class="contentEditableContainer contentFacebookEditable">
                                      <div class="contentEditable">
                                        <!--<a target='_blank' href="#"><img src="cid:facebook.png" width='52' height='53' alt='facebook icon' data-default="placeholder" data-max-width="52" data-customIcon="true"></a>-->
                                      </div>
                                    </div>
                                  </td>

                                  <td width='16'></td>

                                  <td valign='top' width='52'>
                                    <div class="contentEditableContainer contentTwitterEditable">
                                      <div class="contentEditable">
                                        <!--<a target='_blank' href="#"><img src="cid:twitter.png" width='52' height='53' alt='twitter icon' data-default="placeholder" data-max-width="52" data-customIcon="true"></a>-->
                                      </div>
                                    </div>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                      </div>



                    </td>
                  </tr>

                </table>
              </td>
              <td width='40'></td>
            </tr>
          </table>
        </td>
      </tr>

      <tr><td height='88'></td></tr>

    </table>

  </body>
</html>
