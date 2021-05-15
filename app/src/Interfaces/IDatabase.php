<?php

namespace Interfaces;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
interface IDatabase{
    function addOne(Request $request, Response $resonse, $args);
    function getOne(Request $request, Response $response, $args);    
    function getAll(Request $request, Response $response, $args);
    function get(Request $request, Response $response, $args);
    function deleteOne(Request $request, Response $response, $args);    
    function deleteAll(Request $request, Response $response, $args);
    function updateOne(Request $request, Response $response, $args);
    function updateAll(Request $request, Response $response, $args);
   /* function delete(Request $request, Response $response, $args);
    function update(Request $request, Response $resonse, $args);*/
}