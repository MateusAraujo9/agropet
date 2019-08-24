<?php

setcookie("tkuser", "", time()+0);
setcookie("logado", "false", time()+0);

header("Location: /");