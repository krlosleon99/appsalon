<?php

namespace Controllers;

use Model\Servicio;
use MVC\Router;

class ServicioController
{
    public static function index(Router $router)
    {
        isAdmin();

        if (!isset($_SESSION)) {
            session_start();
        }

        $servicios = Servicio::all();

        $router->render('servicios/index', [
            'nombre' => $_SESSION['nombre'],
            'servicios' => $servicios
        ]);
    }

    public static function crear(Router $router)
    {
        isAdmin();

        $servicio = new Servicio;
        $alertas = [];

        if (!isset($_SESSION)) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST);

            $alertas = $servicio->validar();

            if (empty($alertas)) {
                $servicio->guardar();

                Header('Location: /servicios');
            }
        }

        $router->render('servicios/crear', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function actualizar(Router $router)
    {
        isAdmin();

        if (!isset($_SESSION)) {
            session_start();
        }

        $id = $_GET['id'];
        if (!is_numeric($id)) return;
        $alertas = [];

        $servicio = Servicio::find($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // $servicio = new Servicio($_POST);
            $servicio->sincronizar($_POST);
            $alertas = $servicio->validar();

            if (empty($alertas)) {

                $servicio->guardar();
                Header('Location: /servicios');
            }
        }

        $router->render('servicios/actualizar', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function eliminar()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $servicio = Servicio::find($id);
            $servicio->eliminar();
            Header('Location: /servicios');
        }
    }
}
