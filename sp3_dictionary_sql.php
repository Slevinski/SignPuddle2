<?php

$collection = $_REQUEST['collection'];

echo 'PRAGMA encoding = "UTF-8";' . "\n";
echo '.mode tabs' . "\n";
echo '.separator "\t"' . "\n";

echo 'CREATE TABLE IF NOT EXISTS entry(id integer primary key, sign text, terms text, lower text, signtext text, text text, source text, detail text, user text, created_at text, updated_at text);' . "\n";
echo '.import txt/' . $collection . '.txt entry' . "\n";
echo 'UPDATE entry set text=replace(text,\'\n\',char(10));' . "\n";
echo 'UPDATE entry set detail=replace(detail,\'\\n\',char(10));' . "\n";

echo '.quit' . "\n";

