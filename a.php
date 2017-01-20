<pre><?php



var_dump($_POST);
?></pre>


<form method="post" action="http://ec.europa.eu/taxation_customs/vies/vatResponse.html" target="_blank">
    <input type="hidden" name="memberStateCode" value="ES">
    <input type="hidden" name="number" value="B08784894">
    <input type="hidden" name="requesterMemberStateCode" value="PL">
    <input type="hidden" name="requesterNumber" value="5221712807">
    <input type="hidden" name="action" value="check">
    <input type="submit" name="check" value="Weryfikuj">
</form>

array(11) {
["memberStateCode"]=>
string(2) "ES"
["number"]=>
string(9) "B08784894"
["requesterMemberStateCode"]=>
string(2) "PL"
["requesterNumber"]=>
string(10) "5221712807"
["action"]=>
string(5) "check"
["check"]=>
string(9) "Weryfikuj"
}
array(10) {
["memberStateCode"]=>
string(2) "PL"
["number"]=>
string(10) "5262153084"
["requesterMemberStateCode"]=>
string(2) "PL"
["requesterNumber"]=>
string(10) "5221712807"
["action"]=>
string(5) "check"
["check"]=>
string(9) "Weryfikuj"
}
<?php
echo 'kkkk'.get_magic_quotes_gpc().'yyyyyy';
phpinfo();
?>