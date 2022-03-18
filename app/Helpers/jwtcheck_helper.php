<?php
use Firebase\JWT\JWT;


/**
 * get access token from header
 * */
function getBearerToken()
{
  //get Authorization header
  $headers = null;
  if (isset($_SERVER['Authorization'])) {
    $headers = trim($_SERVER["Authorization"]);
  } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
    $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
  } elseif (function_exists('apache_request_headers')) {
    $requestHeaders = apache_request_headers();
    // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
    $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
    if (isset($requestHeaders['Authorization'])) {
      $headers = trim($requestHeaders['Authorization']);
    }
  }

  // get the access token from the header
  if (!empty($headers)) {
    if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {                                                                                           return $matches[1];
    }
  }
  return null;
}


function validateToken($jwt)
{
  $key = "@li";
  JWT::decode($jwt, $key, array('HS256'));
}


if (!function_exists('jwtValidation')) {
  function jwtValidation()
  {
    $jwt = getBearerToken();
    //validate jwt
    try {
      validateToken($jwt);
      return true;
    } catch (Firebase\JWT\ExpiredException $e) {
      return 0;
    } catch (Exception $e) {
      //return $e->getMessage();
      return -1;
    }
  }
}


if (!function_exists('getJwt')) {
  function getJwt($user_id)
  {
    $usuarios_model = model(UsuariosModel::class);
    $usuario = $usuarios_model->obtenerUsuario($user_id);

    if($usuario){
      //generar jwt
      $time = time();
      $key = "@li";
      $token = array(
        'iat' => $time, // Tiempo en que inició el token
        'exp' => $time + (60 * 5), // Tiempo en que expirará el token (5 minutos)
        'user_id' => $user_id
      );
      $jwt = JWT::encode($token, $key);

      return $jwt;
    } else {
      return false;
    }
  }
}


if (!function_exists('refreshJWT')) {
  function refreshJWT($jwt)
  {
    //validate jwt
    try {
      validateToken($jwt);
      return $jwt;
    } catch (Firebase\JWT\ExpiredException $e) {
      $jwt_chunks = explode('.', $jwt);
      $payload = json_decode(base64_decode($jwt_chunks[1]));
      if(isset($payload->user_id)){
        return getJwt($payload->user_id);
      } else {
        return -1;
      }
    } catch (Exception $e) {
      //var_dump($e->getMessage());
      return -1;
    }

  }
}

