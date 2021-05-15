<?php
namespace Controllers;

use Interfaces\IDatabase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Models\Empleado;
use Models\Usuario;
use Components\Validaciones;
use Illuminate\Contracts\Validation\Validator;

class EmpleadoController /*implements IDatabase*/
{
    function addOne(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        //usuario
        $tipo = 2;//'empleado'
        $nombre = $body['nombre'];
        $apellido = $body['apellido'];
        $email = $body['email'];//que no se repita
        $clave = $body['clave'];
        //empleado
        $sector = $body['sector'];//consulta
        $contacto = $body['contacto'];
        $direccion = $body['direccion'];
        $DNI = $body['DNI'];
        $respuesta = "Alta correcta";
        
        $upperCase = Validaciones::validarString(array($nombre,$apellido,$email,$clave,$sector,$contacto,$direccion,$DNI));
        $numeros = Validaciones::validarNumerico(array($DNI,$contacto));
        
        if(!is_array($upperCase) || !is_array($numeros))
        {
            $respuesta = "Datos en formato incorrecto";
        }else
        {
            if(Validaciones::validarNuevoUsuario(strtoupper($email)))
            {
                if(is_numeric(Validaciones::validarSector(strtoupper($sector))))
                {
                    //alta usuario
                    $usuario = new Usuario();
                    $usuario->tipo = $tipo;
                    $usuario->nombre = strtoupper($nombre);
                    $usuario->apellido = strtoupper($apellido);
                    $usuario->email = strtoupper($email);
                    $usuario->clave = strtoupper($clave);
                    $usuario->save();
                    //alta empleado
                    $empleado = new Empleado();
                    $id = Usuario::retornarUltimoId();
                    $empleado->id = $id;
                    $empleado->id_sector = Validaciones::validarSector(strtoupper($sector));
                    $empleado->contacto = strtoupper($contacto);
                    $empleado->direccion = strtoupper($direccion);
                    $empleado->DNI = $DNI;
                    $empleado->save();
                    $respuesta = json_encode($empleado);
                }else{
                    $respuesta = "Sector incorrecto";
                }
            }else{
                $respuesta = "Usuario existente";
            }
        }
        $response->getBody()->write($respuesta);
        return $response;
    }
    static function getOne(Request $request, Response $response, $args)
    {
        if(isset($args['id']))
        {
            $id = Validaciones::validarNumerico(array($args['id']));
            if(is_array($id))
            {
                $id= (int) $args['id'];
                $users = Empleado::where('id',$id)
                ->first();
                if(is_null($users))
                {
                    return 'No existen empleados con ese Id';
                }else
                {
                    return $users;
                }
            }
        }
        return 'Error en dato';
    }
    //static function get
    static function validarCampo($campo)
    {   
        $array = Validaciones::validarString(array('id','sector','contacto','direccion','DNI'));
        $dato = Validaciones::validarString($campo);
        if(is_array($array) && is_array($dato))
        {
            foreach ($array as $key ) {
                if($key == $dato[0]){
                    return true;
                }          
            }
        }
        return false;
        
    }
    function getAll(Request $request, Response $response, $args)
    {
        if(empty($args))
        {
            echo 'busco todo';
            $users = Empleado::all([
                'id',
                'id_sector',
                'contacto',
                'direccion',
                'DNI'
            ]);
            $response->getBody()->write(json_encode($users));
        }else if(count($args) == 1 || Validaciones::validarNumerico($args[0])){
            //buscar por id
            $user = EmpleadoController::getOne($request,$response,$args);
            $response->getBody()->write(json_encode($user));
        }else{
            //var_dump(count($args));
            if(EmpleadoController::validarCampo($args))
            {
                echo 'busco por otra cosa';
                $user = EmpleadoController::get($request,$response,$args);
                $response->getBody()->write(json_encode($user));
            }
            else{
                $response->getBody()->write('No se puede buscar por el campo solicitado');
            }
        }
        return $response;
    }
    static function get(Request $request, Response $response, $args)
    {
        return 'Clave valor';
    }
    function deleteOne(Request $request, Response $response, $args)
    {
    }
    function deleteAll(Request $request, Response $response, $args)
    {
    }
    function delete(Request $request, Response $response, $args)
    {
    }
    function updateOne(Request $request, Response $response, $args)
    {
    }
    function updateAll(Request $request, Response $response, $args)
    {
    }
    function update(Request $request, Response $response, $args)
    {
    }
}