{h2}
{p}
{if($sendmail === false):}
<p style="color:red;">Bitte füllen Sie alle Felder und geben Sie eine korrekte E-Mail-Adresse an.</p>
{endif;}

<form method="post" action="{page_link}?s" class="subscribe">
  <fieldset>
    <p>{labelMail}</p>
    <input type="text" name="email" value="#post:email#" class="email#mandatory:lastname#" />
  </fieldset>
  {labelSalutation}
  {labelFirstname}
  {labelLastname}
  <fieldset>
    <input type="hidden" name="page_id" value="{page_id}" />
    <input type="hidden" name="cn_id" value="{cn_id}" />
    <input type="submit" value="{labelSend}" />
  </fieldset>
</form>