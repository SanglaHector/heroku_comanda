<?php

namespace Components;

use \Firebase\JWT\JWT;

class Token 
{
    static function retornoToken($id,$tipo)
    {
        return Token::encodeJWT(Token::armoPayLoad($id,$tipo));
    }
    static function armoPayLoad($id,$tipo) 
    { 
        $inicio = time();
        return  array(
            'datos' => [
                'id' => $id,
                'inicio' => $inicio,
                'tipo' => $tipo
            ]
        );
    }
    public static function autenticarToken($token)
    {
        $jwt = Token::decodeJWT($token);
        if (isset($jwt->datos->id) && isset($jwt->datos->inicio)) // si toco el payload tengo que tocar aca tambien, ademas puedo poner mas validaciones como tipo de rol, tiempo, etc.
        {
            /*if (($jwt->datos->inicio + 600000000000) > time()) {
                return false;
            }*/
            return $jwt->datos->id; //ojo aca, que la autenticacion depende de como mande yo los tokens
        }
        return false;
    }
    public static function returnTipoToken($token)
    {
        $jwt = Token::decodeJWT($token);
        if (isset($jwt->datos->id) && isset($jwt->datos->inicio) && isset($jwt->datos->tipo))
        {
            return $jwt->datos->tipo;
        }
        return false;
    }
    static function encodeJWT($payload, $key = "comanda")
    {
        return JWT::encode($payload, $key);
    }
    static function decodeJWT($jwt, $key = "comanda")
    {
        return JWT::decode($jwt, $key, array('HS256'));
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
       /* $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1])*/
        if ($header != false) {
            if (isset($header[$key]) && !empty($header[$key])) {
                return $header[$key];
            }
        }
        return null;
    }
}