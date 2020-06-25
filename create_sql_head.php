<?php

$puddle = $_REQUEST['puddle'];

echo '.mode csv' . "\n";
echo '.separator "\t"' . "\n";

echo 'CREATE TABLE IF NOT EXISTS puddle(code text primary key unique, language text, namespace text, subspace text, qqq text, name text, icon text, position int DEFAULT 100, user text, created_at text, view_pass int, add_pass int, edit_pass int, register_level int, upload_level int);' . "\n";
echo '.import source/' . $puddle . '.head.txt puddle' . "\n";

echo '.quit' . "\n";

