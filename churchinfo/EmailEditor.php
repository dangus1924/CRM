<?php
/*******************************************************************************
*
*  filename    : EmailEditor.php
*  description : Form for entering email subject and message
*
*  http://www.churchdb.org/
*
*  LICENSE:
*  (C) Free Software Foundation, Inc.
*
*  ChurchInfo is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 3 of the License, or
*  (at your option) any later version.
*
*  This program is distributed in the hope that it will be useful, but
*  WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
*  General Public License for more details.
*
*  http://www.gnu.org/licenses
*
*  This file best viewed in a text editor with tabs stops set to 4 characters
*
******************************************************************************/

// Include the function library
require "Include/Config.php";
require "Include/Functions.php";

$email_array = $_POST['emaillist'];
$bcc_list = implode(", ", $email_array);

// If editing, get Title and Message
$sEmailSubject = "";
$sEmailMessage = "";

if (array_key_exists ('mysql', $_POST) and $_POST['mysql'] == 'true') {

    // There is a subject and message already stored in mysql
    $sSQL = "SELECT * FROM email_message_pending_emp ".
            "WHERE emp_usr_id='".$_SESSION['iUserID']."' LIMIT 1";

    $aRow = mysql_fetch_array(RunQuery($sSQL));
    extract($aRow);

    $sEmailSubject = $emp_subject;
    $sEmailMessage = $emp_message;
}

// Security: Both global and user permissions needed to send email.
// Otherwise, re-direct them to the main menu.
if (!($bEmailSend && $bSendPHPMail))
{
	Redirect("Menu.php");
	exit;
}

// Set the page title and include HTML header
$sPageTitle = gettext("Compose Email");
require "Include/Header.php";


//Print the From, To, and Email
echo "<hr>\r\n";
echo '<p class="MediumText"><b>' . gettext("From:") . '</b> "' . $sFromName . '"'
. ' &lt;' . $sFromEmailAddress . '&gt;<br>';
echo '<b>' . gettext("To (blind):") . '</b> '  . $bcc_list . '<br>';


echo "\n<hr>";
echo '<div align="center"><table><tr><td align="center">';

echo '<br><h2>' . gettext("Send Email To People in Cart") . '</h2>'."\n";
echo '<form action="CartView.php#email" method="post">';


echo gettext('Subject:');
echo '<br><input type="text" name="emailsubject" size="80" value="';
echo htmlspecialchars($sEmailSubject) . '">'."\n";

echo '<br>' . gettext('Message:');
echo '<br><textarea name="emailmessage" rows="20" cols="72">';
echo htmlspecialchars($sEmailMessage) . '</textarea>'."\n";

echo '<br><input class="icButton" type="submit" name="submit" value="';
echo gettext('Save Email') . '"></form></td></tr></table></div>'."\n";

require "Include/Footer.php"; 

?>
