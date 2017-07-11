<?php

stream_set_blocking(STDIN, 0);
$stdin = file_get_contents("php://stdin");
echo $stdin;
