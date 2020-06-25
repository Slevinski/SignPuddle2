<?php

$puddle = $_REQUEST['puddle'];

echo '.mode csv' . "\n";
echo '.separator "\t"' . "\n";

echo 'CREATE TABLE IF NOT EXISTS entry(id int primary key unique, user text, created_at text, updated_at text, sign text, signtext text, text text, source text, detail text);' . "\n";
echo '.import source/' . $puddle . '.txt entry' . "\n";
echo 'UPDATE entry set text=replace(text,\'\n\',X\'0A\');' . "\n";

echo 'CREATE TABLE IF NOT EXISTS term(id int, prime int, term text, lower text);' . "\n";
echo '.import source/' . $puddle . '.term.txt term' . "\n";
echo 'CREATE INDEX term_id on TERM (id);' . "\n";
echo '.quit' . "\n";

