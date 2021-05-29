<?php

namespace Components;

use \Firebase\JWT\JWT;

class Token
{

    static function retornoToken($objeto, $tipo)
    {
        return Token::encodeJWT(Token::armoPayLoad(json_encode($objeto), $tipo));
    }
    static function armoPayLoad($objeto, $tipo) //ver como armar payload con los datos necesarios
    { //este 'dato' es todo el usuario, deberia guardar la fecha de inicio de sesión
        $inicio = time();
        return  array(
            'datos' => [
                'objeto' => $objeto,
                'inicio' => $inicio,
                'tipo' => $tipo
            ]
        );
        /*"datos"=> $datos,
            "inicio" => $inicio,
            "tipo" => $tipo*/
    }
    public static function autenticarToken($token)
    {
        $jwt = Token::decodeJWT($token);
        if (isset($jwt->datos->objeto) && isset($jwt->datos->inicio) && isset($jwt->datos->tipo)) // si toco el payload tengo que tocar aca tambien, ademas puedo poner mas validaciones como tipo de rol, tiempo, etc.
        {
            /*if (($jwt->datos->inicio + 600) > time()) {
                return false;
            }*/
            return json_decode($jwt->datos->objeto); //ojo aca, que la autenticacion depende de como mande yo los tokens
        }
        return false;
    }
    public static function getRole($token, $key = "comanda")
    {
        if ($token && !empty($token[0])) {
            $jwt = JWT::decode($token, $key, array('HS256'));
            return $jwt->datos->tipo;
        } else {
            return null;
        }
    }
    public static function getHeader($key)
    {
        $header = getallheaders();
        if ($header != false) {
            if (isset($header[$key]) && !empty($header[$key])) {
                return $header[$key];
            }
        }
        return null;
    }
    static function encodeJWT($payload, $key = "comanda")
    {
        return JWT::encode($payload, $key);
    }
    static function decodeJWT($jwt, $key = "comanda")
    {
        return JWT::decode($jwt, $key, array('HS256'));
    }
}
