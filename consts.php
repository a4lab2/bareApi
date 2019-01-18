<?php
// security
define('SECRET_KEY','1234567890');
// data type
define('BOOLEAN','1');
define('INTEGER','2');
define('STRING','3');

// errors
define('INVALID_REQUEST_METHOD',100);
define('INVALID_CONTENT_TYPE',111);
define('INVALID_REQUEST',112);
define('VALIDATE_PARAM_REQUIRED',113);
define('INVALID_PARAM_DATATYPE',114);
define('API_NAME_REQUIRED',115);
define('API_PARAM_REQUIRED',116);
define('API_DEST_NOT_EXIST',117);
define('INVALID_USER_PASS',118);

// success
define('SUCCESSS_RESPONSE',200);
//server error
define('AUTHORIZATION_HEADER_NOT_FOUND',300);
define('ACCESS_TOKEN_ERRORS',301);